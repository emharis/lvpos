<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PurchaseOrderTemp;
use App\Item;
use \Auth, \Redirect, \Validator, \Input, \Session, \Response;
use Illuminate\Http\Request;

class PurchaseOrderTempApiController extends Controller {

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
		
		return Response::json(PurchaseOrderTemp::where('user_id', Auth::user()->id)->with('item')->get());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//return view('sale.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$PoTemps = new PurchaseOrderTemp;
		$PoTemps->item_id = Input::get('item_id');
		$PoTemps->cost_price = Input::get('cost_price');
        $PoTemps->selling_price = Input::get('selling_price');
		$PoTemps->quantity = 1;
		$PoTemps->total_cost = Input::get('cost_price');
        $PoTemps->total_selling = Input::get('selling_price');
        $PoTemps->user_id = Auth::user()->id;
		$PoTemps->save();
		return $PoTemps;
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
		$PoTemps = PurchaseOrderTemp::find($id);
        $PoTemps->quantity = Input::get('quantity');
        $PoTemps->cost_price = Input::get('cost_price');
        $PoTemps->total_cost = Input::get('total_cost');
        $PoTemps->total_selling = Input::get('total_selling');
        $PoTemps->save();
        return $PoTemps;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		PurchaseOrderTemp::destroy($id);
	}

}
