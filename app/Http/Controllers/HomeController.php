<?php namespace App\Http\Controllers;

use App\Item, App\Customer, App\Sale;
use App\Supplier, App\Receiving, App\User;
use App;
use App\SaleItem;
use App\BankAccount;
use App\Expense;
use DB;
use App\TutaposSetting;

use App\PurchaseOrderItem;
use App\ItemEditLog;
use \Auth, \Redirect;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		$items = Item::where('type', 1)->count();
		$item_kits = Item::where('type', 2)->count();
		$customers = Customer::count();
		$suppliers = Supplier::count();
		$receivings = Receiving::count();
		$sales = Sale::count();
		$employees = User::count();
		return view('home')
			->with('items', $items)
			->with('item_kits', $item_kits)
			->with('customers', $customers)
			->with('suppliers', $suppliers)
			->with('receivings', $receivings)
			->with('sales', $sales)
			->with('employees', $employees);
	}



    public function dashboard()
    {
        if (Auth::check()) {
            if (Auth::user()->is_admin != 1) {
                return Redirect::to('customers');
            }
        }
        $sales = Sale::where('created_at', '>=', date("Y"). '-01-01 00:00:00')
                        ->where('created_at', '<=', date("Y"). '-12-31 23:59:59')
                        ->get();
        $salesReport = array();
        $bank = array();
        $bankAccounts = BankAccount::all();

        $totalStockAmount = 0;

        foreach ($bankAccounts as $account) {
            $bank[$account->id]['name'] = $account->account_name;
            $bank[$account->id]['in'] = 0;
            $bank[$account->id]['out'] = 0;
        }

        foreach ($sales as $sale) {
            $month = $sale->created_at->format('F');
            if (!isset($salesReport[$month])) {
                $salesReport[$month]['revenue'] = 0;
                $salesReport[$month]['cost'] = 0;
            }
            //get total sales of
            $revenue = SaleItem::where('sale_id', $sale->id)->sum('total_selling');
            $cost = SaleItem::where('sale_id', $sale->id)->sum('total_cost');

            $salesReport[$month]['revenue'] += $revenue;
            $salesReport[$month]['cost'] += $cost;
    
            if ($sale->bank_account_id) {
                $bank[$sale->bank_account_id]['in'] +=  $revenue;
            }

        }

        $bankExpenses = Expense::select('bank_account_id', DB::raw('SUM(value) as total_out'))
                                 ->whereNotNull('bank_account_id')
                                 ->groupBy('bank_account_id')->get();

        foreach ($bankExpenses as $bankExpense) {
            $bank[$bankExpense->bank_account_id]['out'] = $bankExpense->total_out;
        }
        
       
        $monthExpenses = Expense::select(DB::raw('DATE_FORMAT(spent_at, "%M") as monthname'), DB::raw('DATE_FORMAT(spent_at, "%Y-%m") as yearmonth'), DB::raw('SUM(value) as total_out'))
                                    ->where('spent_at', '>=', date("Y"). '-01-01')
                                    ->where('spent_at', '<=', date("Y"). '-12-31')
                                    ->groupBy('monthname', 'yearmonth')->orderBy('yearmonth')->get();

 

        $items = Item::where('type', 1)->get();
        $tutapos_settings = TutaposSetting::find(1)->first();
     
        foreach ($items as $item) {
            $item->number_ordered = $this->getOrdered($item->id);
            $item->ordered_price = $item->cost_price * $item->number_ordered;
            if ($item->shipping_factor > 0 && $item->margin_factor> 0) {
                $item->price_ref = $item->cost_price * $item->shipping_factor * $item->margin_factor;
            } else {
                $item->price_ref= $item->cost_price * $tutapos_settings->shipping_factor * $tutapos_settings->margin_factor;
            }


            $item->sold = $this->getSold($item->id);
            $item->revenue = $item->price_ref * $item->number_ordered;
            $item->est_gross_margin = ($item->price_ref - $item->cost_price) * $item->number_ordered;
            $item->persediaan = $item->quantity * $item->price_ref;
            $totalStockAmount += $item->persediaan;
        }
        
        foreach ($bank as $item) {
            $posisikascharts['x'][] = $item['name'];
            $posisikascharts['expense'][] =  $item['out'];
            $posisikascharts['revenue'][] =  $item['in'];
        }

        $totalRevenue = 0;
        $totalCost = 0;
        $totalProfit = 0;
        $totalPercentage = 0;
        foreach ($salesReport as $month => $item) {
            $salescharts['x'][] = $month;
            $salescharts['revenue'][] =  $item['revenue'];
            $salescharts['cost'][] =  $item['cost'];
            $salescharts['profit'][] =  $item['revenue'] - $item['cost'];
            $salescharts['margin'][] =  round((($item['revenue'] - $item['cost']) / $item['revenue'])  * 100, 2);

            $totalRevenue += $item['revenue'];
            $totalCost += $item['cost'];
            $totalProfit += $item['revenue'] - $item['cost'];
            $totalPercentage += round((($item['revenue'] - $item['cost']) / $item['revenue'])  * 100, 2);
        }
        $percentageAverage = round($totalPercentage /  count($salescharts['margin']), 2);

        $totalExpenses = 0;

        foreach ($monthExpenses as $expense) {
            $expensescharts['x'][] = $expense->monthname;
            $expensescharts['expenses'][] = $expense->total_out;
            $totalExpenses += $expense->total_out;
        }

       // echo "<pre>";print_r($salesReport); exit;
         //echo json_encode($posisikascharts['expense']);exit;
        return view('home.dashboard')->with('salesReport', $salesReport)
                                     ->with('bank', $bank)
                                     ->with('posisikascharts', $posisikascharts)
                                     ->with('salescharts', $salescharts)
                                     ->with('expensescharts', $expensescharts)
                                     ->with('monthExpenses', $monthExpenses)
                                     ->with(compact('totalRevenue', 'totalCost', 'totalProfit', 'percentageAverage', 'totalStockAmount', 'totalExpenses'));
    }



    public function getSold($id)
    {
        $count =  SaleItem::where('item_id', $id)->sum('quantity');
        if ($count != '') {
            return $count;
        } else {
            return 0;
        }
    }

    public function getOrdered($id)
    {
        $count =  PurchaseOrderItem::where('item_id', $id)->sum('quantity');
        if ($count != '') {
            return $count;
        } else {
            return 0;
        }
    }
}
