<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Sale;
use App\SaleTemp;
use App\SaleItem;
use App\Inventory;
use App\Customer;
use App\Store;
use App\Item, App\ItemKitItem;
use App\Http\Requests\SaleRequest;
use \Auth, \Redirect, \Validator, \Input, \Session;
use Illuminate\Http\Request;
use App\Expense;
use Automattic\WooCommerce\Client as WoocommerceClient;
use App\BankAccount;
use PDF;

class SaleController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
        $this->woocommerce = new WoocommerceClient(
            env('WOOCOMMERCE_STORE_URL', 'http://example.org/'), 
            env('WOOCOMMERCE_CONSUMER_KEY', 'YOUR_CONSUMER_KEY'), 
            env('WOOCOMMERCE_CONSUMER_SECRET', 'YOUR_CONSUMER_SECRET'),
            [
                'version' => 'v3'
            ]
        );

	}


    /**
     * To sync sales with WooCommerce
     *
     */
    public function sync()
    {   
        print_r($this->woocommerce);
        exit;
        $orders = $this->woocommerce->get('orders');
        echo "<pre>";
        print_r($orders);
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        if (Auth::user()->is_admin == 1) {
 		 $sales = Sale::all();
        } else {
            $sales = Sale::where('user_id', Auth::user()->id)->get();
        }
        foreach ($sales as $sale) {
            $sale->sub_total = SaleItem::where('sale_id', $sale->id)->sum('total_selling');
            $sale->sub_total_price_ref = SaleItem::where('sale_id', $sale->id)->sum('total_price_ref');
            $sale->total = $sale->sub_total + $sale->shipping_price;

            try {
               $bankAccount = BankAccount::find($sale->bank_account_id);
               $sale->bank_account_name = $bankAccount->account_name;
            } catch (\Exception $e) {
                $sale->bank_account_name = '';
            }
  
        }
        return view('sale.index')->with('sales', $sales);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$sales = Sale::orderBy('id', 'desc')->first();

        //$customers = Customer::orderBy('id', 'desc')->take(5)->lists('name', 'id');
        
        $customerArray = array();

        //foreach ($customers  as $key => $name) {
        //    $customerArray[$key] = $name;
       // }

         if (Input::get('customer_id')) {
            $selectedcustomer  = Customer::where('id', Input::get('customer_id'))->get()->lists('name', 'id');
 
            $customerArray[Input::get('customer_id')] = $selectedcustomer[Input::get('customer_id')];
        } else {
            Session::flash('message', 'Select customer and click create sale');
            return Redirect::to('customers');
        }

        $bankAccounts = BankAccount::lists('account_name', 'id');
        return view('sale.create')
            ->with('sale', $sales)
            ->with('bankAccounts', $bankAccounts)
            ->with('customer', $customerArray);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(SaleRequest $request)
	{
	    $sales = new Sale;
        $sales->customer_id = Input::get('customer_id');
        $sales->user_id = Auth::user()->id;
        $sales->payment_type = Input::get('payment_type');
        $sales->comments = Input::get('comments');
        $sales->source = '';
        $sales->shipping_method = Input::get('shipping_method');
        $sales->shipping_price = Input::get('shipping_price');
        $sales->insurance = Input::get('insurance');

        $customer = Customer::find(Input::get('customer_id'));
        $sales->store_id = $customer->store_id;

        $sales->no_resi =  Input::get('no_resi');
        $sales->bank_account_id =  Input::get('bank_account_id');
        $sales->is_dropshipper =  Input::get('is_dropshipper');
        if (Input::get('closing_date')) {
            $sales->closing_date = Input::get('closing_date');
            $sales->created_at = Input::get('closing_date') . ' 00:00:01';
        }

        $sales->save();
        // process sale items
        $saleItems = SaleTemp::where('user_id', Auth::user()->id)->get();
		foreach ($saleItems as $value) {
			$saleItemsData = new SaleItem;
			$saleItemsData->sale_id = $sales->id;
			$saleItemsData->item_id = $value->item_id;
			$saleItemsData->cost_price = $value->cost_price;
			$saleItemsData->selling_price = $value->selling_price;
			$saleItemsData->quantity = $value->quantity;
			$saleItemsData->total_cost = $value->total_cost;
            $saleItemsData->total_selling = $value->total_selling;
            $saleItemsData->price_ref = $value->price_ref;
			$saleItemsData->total_price_ref =  $value->quantity * $value->price_ref;
			$saleItemsData->save();
			//process inventory
			$items = Item::find($value->item_id);
			if($items->type == 1)
			{
				$inventories = new Inventory;
				$inventories->item_id = $value->item_id;
				$inventories->user_id = Auth::user()->id;
				$inventories->in_out_qty = -($value->quantity);
				$inventories->remarks = 'SALE'.$sales->id;
				$inventories->save();
				//process item quantity
	            $items->quantity = $items->quantity - $value->quantity;
	            $items->save();
        	}
        	else
        	{
	        	$itemkits = ItemKitItem::where('item_kit_id', $value->item_id)->get();
				foreach($itemkits as $item_kit_value)
				{
					$inventories = new Inventory;
					$inventories->item_id = $item_kit_value->item_id;
					$inventories->user_id = Auth::user()->id;
					$inventories->in_out_qty = -($item_kit_value->quantity * $value->quantity);
					$inventories->remarks = 'SALE'.$sales->id;
					$inventories->save();
					//process item quantity
					$item_quantity = Item::find($item_kit_value->item_id);
		            $item_quantity->quantity = $item_quantity->quantity - ($item_kit_value->quantity * $value->quantity);
		            $item_quantity->save();
				}
        	}
		}

        if ($sales->shipping_price > 0) {
            //create new expense:
            $expense = new Expense;
            $expense->spent_at = date("Y-m-d");
            $expense->description = $customer->name;
            $expense->post = 'Local Shipping';
            $expense->value = $sales->shipping_price;
            $expense->po_invoice_number =  $sales->id ;
            $expense->account = Store::find($sales->store_id)->store_name;
            $expense->shipping_method = $sales->shipping_method;
            $expense->no_resi = $sales->no_resi;
            $expense->bank_account_id = $sales->bank_account_id;

            $expense->remarks =  Auth::user()->name . ' - ' . Store::find($sales->store_id)->store_name;
            $expense->user_id =  Auth::user()->id;
            $expense->save();

        }
        
        //SaleTemp::truncate();

		$saletemp =  SaleTemp::where('user_id', Auth::user()->id);
        $saletemp->delete();
        return Redirect::to('sales/'.$sales->id.'/pdf');
        //$itemssale = SaleItem::where('sale_id', $saleItemsData->sale_id)->get();
         //   Session::flash('message', 'You have successfully added sales');
            //return Redirect::to('receivings');
        //    return view('sale.complete')
        //    	->with('sales', $sales)
        //    	->with('saleItemsData', $saleItemsData)
        //    	->with('saleItems', $itemssale);

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
        $sales = Sale::find($id);
        $customers = Customer::where('id', $sales->customer_id)->take(1)->lists('name', 'id');
        $bankAccounts = BankAccount::lists('account_name', 'id');

        $saletemp = SaleTemp::where('user_id', Auth::user()->id);
        $saletemp->delete();
        
        $items = SaleItem::where('sale_id', $id)->get();
        
        foreach ($items as $item) {

            $SaleTemps = new SaleTemp;
            $SaleTemps->item_id = $item->item_id;
            $SaleTemps->cost_price = $item->cost_price;
            $SaleTemps->selling_price = $item->selling_price;
            $SaleTemps->quantity = $item->quantity;
            $SaleTemps->total_cost = $item->total_cost;
            $SaleTemps->total_selling = $item->total_selling;
            $SaleTemps->price_ref = $item->price_ref;
            $SaleTemps->user_id = Auth::user()->id;
            $SaleTemps->save();
        }


        return view('sale.edit')
                ->with('sale', $sales)
                ->with('customers', $customers)
                ->with('bankAccounts', $bankAccounts);

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
        $sales = Sale::find($id);

        $sales->customer_id = Input::get('customer_id');
        //$sales->user_id = Auth::user()->id;
        $sales->payment_type = Input::get('payment_type');
        $sales->comments = Input::get('comments');
        $sales->source = '';
        $sales->shipping_method = Input::get('shipping_method');
        $sales->shipping_price = Input::get('shipping_price');
        $sales->insurance = Input::get('insurance');

        $customer = Customer::find(Input::get('customer_id'));
        $sales->store_id = $customer->store_id;

        $sales->no_resi =  Input::get('no_resi');
        $sales->bank_account_id =  Input::get('bank_account_id');
        $sales->is_dropshipper =  Input::get('is_dropshipper');
        if (Input::get('closing_date')) {
            $sales->closing_date = Input::get('closing_date');
            $sales->created_at = Input::get('closing_date') . ' 00:00:01';
        }

        $sales->save();


        $previousItems = SaleItem::where('sale_id', $id)->get();
        
        $previousQuantity = array();
        foreach ($previousItems as $previousItem) {
            $previousQuantity[$previousItem->item_id] = $previousItem->quantity;
        }
 
        //clear previous items
        $deletedRows = SaleItem::where('sale_id', $id)->delete();

        $saleItems = SaleTemp::where('user_id', Auth::user()->id)->get();
        foreach ($saleItems as $value) {
            $saleItemsData = new SaleItem;
            $saleItemsData->sale_id = $sales->id;
            $saleItemsData->item_id = $value->item_id;
            $saleItemsData->cost_price = $value->cost_price;
            $saleItemsData->selling_price = $value->selling_price;
            $saleItemsData->quantity = $value->quantity;
            $saleItemsData->total_cost = $value->total_cost;
            $saleItemsData->total_selling = $value->total_selling;
            $saleItemsData->price_ref = $value->price_ref;
            $saleItemsData->total_price_ref =  $value->quantity * $value->price_ref;
            $saleItemsData->save();
            //process inventory
            $items = Item::find($value->item_id);
            if (isset($previousQuantity[$value->item_id]) && $items->type == 1) {
                if ($value->quantity  != $previousQuantity[$value->item_id]) {
                    $inventories = new Inventory;
                    $inventories->item_id = $value->item_id;
                    $inventories->user_id = Auth::user()->id;
                    $inventories->in_out_qty = -($value->quantity - $previousQuantity[$value->item_id]);
                    $inventories->remarks = 'UPDATE SALE'.$sales->id;
                    $inventories->save();
                    //process item quantity
                    $items->quantity = $items->quantity - ($value->quantity - $previousQuantity[$value->item_id]);
                    $items->save();
                }
                unset($previousQuantity[$value->item_id]);
            } else if ($items->type == 1) {
                $inventories = new Inventory;
                $inventories->item_id = $value->item_id;
                $inventories->user_id = Auth::user()->id;
                $inventories->in_out_qty = -($value->quantity);
                $inventories->remarks = 'UPDATE SALE'.$sales->id;
                $inventories->save();
                //process item quantity
                $items->quantity = $items->quantity - ($value->quantity);
                $items->save();
            }
        }

        foreach ($previousQuantity as $item_id => $leftQuantity) {
            $items = Item::find($item_id);
            $inventories = new Inventory;
            $inventories->item_id = $item_id;
            $inventories->user_id = Auth::user()->id;
            $inventories->in_out_qty = $leftQuantity;
            $inventories->remarks = 'UPDATE SALE'.$sales->id;
            $inventories->save();
            //process item quantity
            $items->quantity = $items->quantity + $leftQuantity;
            $items->save();
        }

        $saletemp =  SaleTemp::where('user_id', Auth::user()->id);
        $saletemp->delete();
        
           
        Session::flash('message', 'You have successfully updated sale.');
        return Redirect::to('sales');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
        $sales = Sale::find($id);
        //delete expense
        $expense = Expense::where('post', 'Local Shipping')
                            ->where('po_invoice_number', $sales->id)
                            ->delete();

        //revert inventory
        //
        

        $saleItems = SaleItem::where('sale_id', $sales->id)->get();
        foreach ($saleItems as $value) {
            //process inventory
            $items = Item::find($value->item_id);
            if ($items->type == 1) {
                $inventories = new Inventory;
                $inventories->item_id = $value->item_id;
                $inventories->user_id = Auth::user()->id;
                $inventories->in_out_qty = +($value->quantity);
                $inventories->remarks = 'DELETE SALE'.$sales->id;
                $inventories->save();
                //process item quantity
                $items->quantity = $items->quantity + $value->quantity;
                $items->save();
            }
        }

        //delete items:
        $saleItems = SaleItem::where('sale_id', $sales->id)->delete();


        //delet sale
        $sales->delete();
           
        Session::flash('message', 'You have successfully deleted the sale.');
        return Redirect::to('sales');
	}


    public function pdf($id)
    {
        $sales = Sale::find($id);
        $sales->sub_total = SaleItem::where('sale_id', $sales->id)->sum('total_selling');
        $sales->total = $sales->sub_total + $sales->shipping_price;
        $sales->store_name = Store::find($sales->store_id)->store_name;
        $itemssale = SaleItem::where('sale_id', $sales->id)->get();
         return view('sale.pdf')
                ->with('sales', $sales)
                ->with('saleItems', $itemssale);
    }


    public function printCard($id)
    {
        $sales = Sale::find($id);
        $sales->sub_total = SaleItem::where('sale_id', $sales->id)->sum('total_selling');
        $sales->total = $sales->sub_total + $sales->shipping_price;
        $sales->store_name = Store::find($sales->store_id)->store_name;
        $itemssale = SaleItem::where('sale_id', $sales->id)->get();


        $data['sales'] =  $sales;
        $data['saleItems'] =  $itemssale;

        $pdf = PDF::loadView('sale.print', $data)->setPaper('a4', 'portrait')->setWarnings(false);
        return $pdf->stream();

    }

    public function getCustomerSourceName()
    {
        $customer = Customer::find(Input::get('customer_id'));
        $store_id = $customer->store_id;
        echo Store::find($store_id)->store_name;
    }


}
