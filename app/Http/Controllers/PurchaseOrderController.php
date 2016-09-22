<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Inventory;
use App\Customer;
use App\Supplier;
use App\PurchaseOrder;
use App\PurchaseOrderTemp;
use App\PurchaseOrderItem;
use App\Item, App\ItemKitItem;
use App\Http\Requests\PurchaseOrderRequest;
use \Auth, \Redirect, \Validator, \Input, \Session;
use Illuminate\Http\Request;
use PDF;
use App\Expense;
use App\BankAccount;
use App\TutaposSetting;
use App\ItemEditLog;

class PurchaseOrderController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$purchaseOrders = PurchaseOrder::all();
        foreach ($purchaseOrders as $po) {
            try {
               $bankAccount = BankAccount::find($po->bank_account_id);
               $po->bank_account_name = $bankAccount->account_name;
            } catch (\Exception $e) {
                $po->bank_account_name = '';
            }
        }
        return view('purchase_order.index')->with('purchaseOrders', $purchaseOrders);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{  

        try {
            $purchaseOrder = PurchaseOrder::orderBy('id', 'desc')->first();
            $newPoNumber = sprintf('%03d', $purchaseOrder->id) . '-'. date("dmy");
        } catch (\Exception $e) {
            $newPoNumber = sprintf('%03d', 1) . '-'. date("dmy");
        }
        $supplier = Supplier::lists('company_name', 'id');
        $bankAccounts = BankAccount::lists('account_name', 'id');
        return view('purchase_order.create')
                ->with('purchaseOrder', $purchaseOrder)
                ->with('newPoNumber', $newPoNumber)
                ->with('bankAccounts', $bankAccounts)
                ->with('supplier', $supplier);
    }

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(PurchaseOrderRequest $request)
	{
	    $po = new PurchaseOrder;
        $po->po_number = Input::get('po_number');
        $po->supplier_id = Input::get('supplier_id');
        $po->user_id = Auth::user()->id;
        $po->po_date = Input::get('po_date');
        $po->delivery_date = Input::get('delivery_date');
        $po->sub_total = Input::get('sub_total');
        $po->additional_charges = Input::get('additional_charges');
        $po->bill_acccount = '';
        $po->bill_amount =  0;
        $po->bank_account_id =  Input::get('bank_account_id');

        $po->comments = Input::get('comments');

        if (Input::hasFile('quotation')) {
            $destinationPath = public_path() .'/uploads/';
            $filename = date("Y_m_d_His"). "_". Input::file('quotation')->getClientOriginalName();
            Input::file('quotation')->move($destinationPath, $filename);
            $po->quotation_path =  $filename;
        }

        $po->save();

        $poItems = PurchaseOrderTemp::where('user_id', Auth::user()->id)->get();
        foreach ($poItems as $value) {
            $poItemsData = new PurchaseOrderItem;
            $poItemsData->purchase_order_id = $po->id;
            $poItemsData->item_id = $value->item_id;
            $poItemsData->cost_price = $value->cost_price;
            $poItemsData->selling_price = $value->selling_price;
            $poItemsData->quantity = $value->quantity;
            $poItemsData->total_cost = $value->total_cost;
            $poItemsData->total_selling = $value->total_selling;
            $poItemsData->save();
        }



        $potemp = PurchaseOrderTemp::where('user_id', Auth::user()->id);
        $potemp->delete();


        //create new expense:
        $expense = new Expense;
        $expense->spent_at = $po->po_date;
        $expense->description = 'Order Item';
        $expense->post = 'PO';
        $expense->value = $po->sub_total + $po->additional_charges;
        $expense->po_invoice_number = 'PO' . $po->po_number;
        $expense->account = $po->bill_acccount;
        $expense->remarks = 'auto created upon create PO';

        $expense->save();


        $this->adjustItemPriceByPo($po);
        
        Session::flash('message', 'You have successfully added purchase order');
        return Redirect::to('po');
	}

    public function adjustItemPriceByPo($po)
    {
        $poItems = PurchaseOrderItem::where('purchase_order_id', $po->id)->get();

        foreach ($poItems as $poItem) {
            $this->adjustItemCostPrice($poItem->item_id);
        }
    }

    public function adjustItemCostPrice($id)
    {
         $tutapos_settings = TutaposSetting::find(1)->first();
         $poItems = PurchaseOrderItem::where('item_id', $id)->get();
         $totalCost = 0;
         $totalPurchased = 0;
         foreach ($poItems as $poItem) {

            $totalCost += $poItem->total_cost;
            $totalPurchased += $poItem->quantity;
         }
 
         $newCostPrice = round($totalCost / $totalPurchased, 2);

         $item = Item::find($poItem->item_id);
         $oldCostPrice = round($item->cost_price, 2);
         $oldSellingPrice = $item->selling_price;

         if ($newCostPrice != $oldCostPrice) {
            $item->cost_price = $newCostPrice;
            $item->selling_price = $item->cost_price * $tutapos_settings->shipping_factor * $tutapos_settings->margin_factor;;
        
            $item->save();

            $log = new ItemEditLog;
            $log->item_id = $poItem->item_id;
            $log->user_id = Auth::user()->id;
            $log->field = 'cost_price';
            $log->old_value = $oldCostPrice;
            $log->new_value = $item->cost_price;
            $log->save();

             $log = new ItemEditLog;
            $log->item_id = $poItem->item_id;
            $log->user_id = Auth::user()->id;
            $log->field = 'selling_price';
            $log->old_value = $oldSellingPrice;
            $log->new_value = $item->selling_price;
            $log->save();

            
        }


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
        $purchaseOrder = PurchaseOrder::find($id);
        //truncate potemp table, insert with current po data
        $potemp = PurchaseOrderTemp::where('user_id', Auth::user()->id);
        $potemp->delete();
        
        $items = PurchaseOrderItem::where('purchase_order_id', $id)->get();
        
        foreach ($items as $item) {
            $PoTemps = new PurchaseOrderTemp;
            $PoTemps->item_id = $item->item_id;
            $PoTemps->cost_price = $item->cost_price;
            $PoTemps->selling_price = $item->selling_price;
            $PoTemps->quantity = $item->quantity;
            $PoTemps->total_cost = $item->total_cost;
            $PoTemps->total_selling = $item->total_selling;
            $PoTemps->user_id = Auth::user()->id;
            $PoTemps->save();
        }
        $supplier = Supplier::lists('company_name', 'id');
        
        $bankAccounts = BankAccount::lists('account_name', 'id');
        
        return view('purchase_order.edit')
                ->with('supplier', $supplier)
                ->with('bankAccounts', $bankAccounts)
                ->with('purchaseOrder', $purchaseOrder);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(PurchaseOrderRequest $request, $id)
	{
        $po = PurchaseOrder::find($id);

        $previous_delivery_date = $po->delivery_date;

        $po->po_number = Input::get('po_number');
        $po->supplier_id = Input::get('supplier_id');
        $po->user_id = Auth::user()->id;
        $po->po_date = Input::get('po_date');
        $po->delivery_date = Input::get('delivery_date');
        $po->sub_total = Input::get('sub_total');
        $po->additional_charges = Input::get('additional_charges');
        $po->bill_acccount = '';
        $po->bill_amount = 0;
        $po->comments = Input::get('comments');
        $po->bank_account_id =  Input::get('bank_account_id');

        if (Input::hasFile('quotation')) {
            $destinationPath = public_path() .'/uploads/';
            $filename = date("Y_m_d_His"). "_". Input::file('quotation')->getClientOriginalName();
            Input::file('quotation')->move($destinationPath, $filename);
            $po->quotation_path =  $filename;
        }

        $po->save();

        //clear previous items
        $deletedRows = PurchaseOrderItem::where('purchase_order_id', $po->id)->delete();

         $poItems = PurchaseOrderTemp::where('user_id', Auth::user()->id)->get();
        foreach ($poItems as $value) {
            $poItemsData = new PurchaseOrderItem;
            $poItemsData->purchase_order_id = $po->id;
            $poItemsData->item_id = $value->item_id;
            $poItemsData->cost_price = $value->cost_price;
            $poItemsData->selling_price = $value->selling_price;
            $poItemsData->quantity = $value->quantity;
            $poItemsData->total_cost = $value->total_cost;
            $poItemsData->total_selling = $value->total_selling;
            $poItemsData->save();

            $inventoryUpdatedMsg = '';
            //update stock
            if ( ($previous_delivery_date == '0000-00-00' || $previous_delivery_date == '')  && Input::get('delivery_date') > '0000-00-00') {
                //add stock to inventory

                 $items = Item::find($value->item_id);

                if ($items->type == 1) {
                    $inventories = new Inventory;
                    $inventories->item_id = $value->item_id;
                    $inventories->user_id = Auth::user()->id;
                    $inventories->in_out_qty = +($value->quantity);
                    $inventories->remarks = 'Received PO #'.$po->po_number;
                    $inventories->save();
                    //process item quantity
                    $items->quantity = $items->quantity + $value->quantity;
                    $items->save();
                } else {
                    $itemkits = ItemKitItem::where('item_kit_id', $value->item_id)->get();
                    foreach ($itemkits as $item_kit_value) {
                        $inventories = new Inventory;
                        $inventories->item_id = $item_kit_value->item_id;
                        $inventories->user_id = Auth::user()->id;
                        $inventories->in_out_qty = +($item_kit_value->quantity * $value->quantity);
                        $inventories->remarks = 'Received PO #'.$po->po_number;
                        $inventories->save();
                        //process item quantity
                        $item_quantity = Item::find($item_kit_value->item_id);
                        $item_quantity->quantity = $item_quantity->quantity + ($item_kit_value->quantity * $value->quantity);
                        $item_quantity->save();
                    }
                }

                $inventoryUpdatedMsg = ' Stock Inventory Updated!';
            }
        }

        $potemp = PurchaseOrderTemp::where('user_id', Auth::user()->id);
        $potemp->delete();

        $this->adjustItemPriceByPo($po);

        Session::flash('message', 'You have successfully updated purchase order.'. $inventoryUpdatedMsg);
        return Redirect::to('po');
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
	}


    public function pdf($id)
    {
        $purchaseOrder = PurchaseOrder::find($id);
        //truncate potemp table, insert with current po data
        PurchaseOrderTemp::truncate();
        $items = PurchaseOrderItem::where('purchase_order_id', $id)->get();
        
        $data['purchaseOrder'] =  $purchaseOrder;
        $data['items'] =  $items;

        $pdf = PDF::loadView('purchase_order.pdf', $data)->setPaper('a4', 'portrait')->setWarnings(false);
        return $pdf->stream();
    }

}
