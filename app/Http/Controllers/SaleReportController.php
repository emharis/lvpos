<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Sale;
use \Auth, \Redirect, \Validator, \Input, \Session;
use Illuminate\Http\Request;
use App\User;
use App\SaleItem;
use App\BankAccount;

class SaleReportController extends Controller {

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

         $salesReport = Sale::where('id', '>', 0);


        if (Auth::user()->is_admin == 1) {
            if (Input::get('user_id')) {
                 $salesReport = $salesReport->where('user_id', Input::get('user_id'));
            }

        } else {
             $salesReport = $salesReport->where('user_id', Auth::user()->id);
        }

        if (Input::get('from')) {
                 $salesReport = $salesReport->where('created_at', '>=', Input::get('from') . ' 00:00:00');
        }

        if (Input::get('to')) {
                 $salesReport = $salesReport->where('created_at', '<=', Input::get('to') . ' 23:59:59');
        }
        $salesReport = $salesReport->get();

        $sumTotal = 0;
        $sumCost = 0;
        $sumProfit = 0;
        $sumPriceRef = 0;

        foreach ($salesReport as $report) {
            $sumTotal += SaleItem::where('sale_id', $report->id)->sum('total_selling');
            $sumCost += SaleItem::where('sale_id', $report->id)->sum('total_cost');
            $sumPriceRef  += SaleItem::where('sale_id', $report->id)->sum('total_price_ref');
            
            try {
                $report->bank_account = BankAccount::find($report->bank_account_id)->account_name;

            } catch (\Exception $e) {
                $report->bank_account = '';
            }
            
            try {
                $report->price_ref = SaleItem::where('sale_id', $report->id)->sum('total_price_ref');

            } catch (\Exception $e) {
                $report->price_ref = 0.00;
            }

        }

        $sumProfit = $sumTotal - $sumPriceRef;

        $employees = User::lists('name', 'id');
		return view('report.sale')->with('saleReport', $salesReport)
                                  ->with('employees', $employees)
                                  ->with(compact('sumTotal', 'sumCost', 'sumProfit'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
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

}
