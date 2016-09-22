<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Item;
use App\Inventory;
use App\PurchaseOrderItem;
use App\ItemEditLog;
use App\SaleItem;
use App\Http\Requests\ItemRequest;
use \Auth, \Redirect, \Validator, \Input, \Session;
use Image;
use Illuminate\Http\Request;
use App\TutaposSetting;

class ItemCategoryController extends Controller {

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
	   $itemcategories = \DB::table('VIEW_ITEM_CATEGORY')->get();
		return view('item_category.index',[
				'itemcategories' => $itemcategories
			]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{  
        
		return view('item_category.create');
	}

	/**
	 * Store/Save a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function insert(Request $request)
	{
        \DB::table('item_category')->insert([
        		'name' => $request->name
        	]);
    
        Session::flash('message', 'You have successfully added item category');
        return Redirect::to('itemcategories');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$itemcategory = \DB::table('item_category')->find($id);
        return view('item_category.edit',[
        		'itemcategory' => $itemcategory
        	]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request)
	{
        \DB::table('item_category')
        	->where('id',$request->id)
        	->update([
        			'name' => $request->name
        		]);
    
        Session::flash('message', 'You have successfully updated item category');
        return Redirect::to('itemcategories');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function delete($id)
	{
			\DB::table('item_category')->delete($id);

	        Session::flash('message', 'You have successfully deleted item category');
	        return Redirect::to('itemcategories');
	}

    

}
