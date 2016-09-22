<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Item, App\ItemKit, App\ItemKitItem;
use \Auth, \Redirect, \Validator, \Input, \Session, \Response;
use Illuminate\Http\Request;
use App\TutaposSetting;

class ReceivingApiController extends Controller {

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
		//$items = Item::get();
		//$itemkits = ItemKit::with('itemkititem')->get();
		//$array = array_merge($items->toArray(), $itemkits->toArray());
		//return Response::json($array);
        $items = Item::where('type', 1)->get();
        $tutapos_settings = TutaposSetting::find(1)->first();
        foreach ($items as $item) {
            //if ($item->shipping_factor > 0 && $item->margin_factor> 0) {
             //   $item->price_ref = $item->cost_price * $item->shipping_factor * $item->margin_factor;
            //} else {
            //    $item->price_ref= $item->cost_price * $tutapos_settings->shipping_factor * $tutapos_settings->margin_factor;
            //}
            $item->price_ref  = $item->selling_price;
        }
		return Response::json($items);
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
