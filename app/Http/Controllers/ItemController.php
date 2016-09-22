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

class ItemController extends Controller {

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
		// edited by ERies Herman
	    $items = Item::where('type', 1)->orderBy('created_at','desc')->get();
	    // end of edited
	    // $items = Item::where('type', 1)->get();
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
        }
		return view('item.index')->with('item', $items);
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

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{  
        $tutapos_settings = TutaposSetting::find(1)->first();
        $shippingFactor =  $tutapos_settings->shipping_factor;
        $marginFactor =  $tutapos_settings->margin_factor;
        $retailPriceFactor = $tutapos_settings->retail_price_factor;

        /**
         * Edited by Eries Herman
         **/
        $itemCategories = \DB::table('item_category')->select('id','name')->get();
        $selectCategory =[]; 
        foreach($itemCategories as $dt){
        	$selectCategory[$dt->id] = $dt->name;
        }

        
         /**
         * End of Edited by Eries Herman
         **/

		return view('item.create')->with('shippingFactor', $shippingFactor)
                                  ->with('marginFactor', $marginFactor)
                                  ->with('retailPriceFactor', $retailPriceFactor)
                                  ->with('selectCategories', $selectCategory);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(ItemRequest $request)
	{

		return \DB::transaction(function()use($request){
			$items = new Item;
            $items->upc_ean_isbn = Input::get('upc_ean_isbn');
            $items->item_name = Input::get('item_name');
            $items->size = Input::get('size');
            $items->weight = Input::get('weight');
            $items->length = Input::get('length');
            $items->width = Input::get('width');
            $items->height = Input::get('height');
            $items->description = Input::get('description');
            $items->cost_price = Input::get('cost_price');
            $items->selling_price = Input::get('selling_price');
            $items->quantity = Input::get('quantity');
            $items->shipping_factor = Input::get('shipping_factor');
            $items->margin_factor = Input::get('margin_factor');
            $items->formula_remarks = Input::get('formula_remarks');
            $items->item_category_id = Input::get('item_category');
            $items->product_feature = Input::get('product_feature');
            $items->save();
            // process inventory
            if(!empty(Input::get('quantity')))
			{
				$inventories = new Inventory;
				$inventories->item_id = $items->id;
				$inventories->user_id = Auth::user()->id;
				$inventories->in_out_qty = Input::get('quantity');
				$inventories->remarks = 'Manual Edit of Quantity';
				$inventories->save();
			}
   //          // process avatar
   //          $image = $request->file('avatar');
			// if(!empty($image))
			// {
			// 	$avatarName = 'item' . $items->id . '.' . 
			// 	$request->file('avatar')->getClientOriginalExtension();

			// 	$request->file('avatar')->move(
			// 	base_path() . '/public/images/items/', $avatarName
			// 	);
			// 	$img = Image::make(base_path() . '/public/images/items/' . $avatarName);
			// 	$img->resize(100, null, function ($constraint) {
			// 		$constraint->aspectRatio();
			// 	});
			// 	$img->save();
			// 	$itemAvatar = Item::find($items->id);
			// 	$itemAvatar->avatar = $avatarName;
	  //           $itemAvatar->save();
   //      	}

        	/** 
        	 * Edit by Eries Herman
        	 * Process Avatar
        	 **/
            // $image = $request->file('avatar');
			if(count($request->avatar) > 0)
			{
				$loop_idx =1;
				foreach($request->avatar as $dt){

					if($dt){
						$avatarName = 'item' . $items->id .'_' . $loop_idx . '.' . 
						$dt->getClientOriginalExtension();

						$dt->move(
						base_path() . '/public/images/items/', $avatarName
						);
						$img = Image::make(base_path() . '/public/images/items/' . $avatarName);
						$img->resize(100, null, function ($constraint) {
							$constraint->aspectRatio();
						});
						$img->save();

						if($loop_idx == 1 ){
							// update avatar utama
							$itemAvatar = Item::find($items->id);
							$itemAvatar->avatar = $avatarName;
				            $itemAvatar->save();	
						}

						// insert ke table item_avatar
						\DB::table('item_avatars')->insert([
								'item_id' => $items->id,
								'avatar' => $avatarName
							]);
					}
					
					$loop_idx++;					
				}
				
        	}

        	 /** 
        	 * End of Edit by Eries Herman
        	 **/

            Session::flash('message', 'You have successfully added item');
            return Redirect::to('items/create');
		});

		    
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
			$items = Item::find($id);
            $tutapos_settings = TutaposSetting::find(1)->first();
            $shippingFactor =  $tutapos_settings->shipping_factor;
            $marginFactor =  $tutapos_settings->margin_factor;

	        return view('item.edit')
	            ->with('item', $items)
                ->with('shippingFactor', $shippingFactor)
                                  ->with('marginFactor', $marginFactor);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(ItemRequest $request, $id)
	{

		return \DB::transaction(function()use($request,$id){
			$items = Item::find($id);

            $this->createItemEditLog($items);

            // process inventory
			$inventories = new Inventory;
			$inventories->item_id = $id;
			$inventories->user_id = Auth::user()->id;
			$inventories->in_out_qty = Input::get('quantity')- $items->quantity;
			$inventories->remarks = 'Manual Edit of Quantity';
			$inventories->save();
			// save update
            $items->upc_ean_isbn = Input::get('upc_ean_isbn');
            $items->item_name = Input::get('item_name');
            $items->size = Input::get('size');
            $items->weight = Input::get('weight');
            $items->length = Input::get('length');
            $items->width = Input::get('width');
            $items->height = Input::get('height');
            $items->description = Input::get('description');
            $items->cost_price = Input::get('cost_price');
            $items->selling_price = Input::get('selling_price');
            $items->quantity = Input::get('quantity');
            $items->shipping_factor = Input::get('shipping_factor');
            $items->margin_factor = Input::get('margin_factor');
            $items->formula_remarks = Input::get('formula_remarks');
            $items->item_category_id = Input::get('item_category');
            $items->product_feature = Input::get('product_feature');
            $items->save();
   //          // process avatar
   //          $image = $request->file('avatar');
			// if(!empty($image)) {
			// 	$avatarName = 'item' . $id . '.' . 
			// 	$request->file('avatar')->getClientOriginalExtension();

			// 	$request->file('avatar')->move(
			// 	base_path() . '/public/images/items/', $avatarName
			// 	);
			// 	$img = Image::make(base_path() . '/public/images/items/' . $avatarName);
			// 	$img->resize(100, null, function ($constraint) {
			// 		$constraint->aspectRatio();
			// 	});
			// 	$img->save();
			// 	$itemAvatar = Item::find($id);
			// 	$itemAvatar->avatar = $avatarName;
	  //           $itemAvatar->save();
   //      	}

            /** 
        	 * Edit by Eries Herman
        	 * Process Avatar
        	 **/
            // $image = $request->file('avatar');
			if(count($request->avatar) > 0)
			{

				// delete data sebelumnya
				\DB::table('item_avatars')->where('item_id',$items->id)->delete();

				$loop_idx =1;
				foreach($request->avatar as $dt){

					if($dt){
						$avatarName = 'item' . $items->id .'_' . $loop_idx . '.' . 
						$dt->getClientOriginalExtension();

						$dt->move(
						base_path() . '/public/images/items/', $avatarName
						);
						$img = Image::make(base_path() . '/public/images/items/' . $avatarName);
						$img->resize(100, null, function ($constraint) {
							$constraint->aspectRatio();
						});
						$img->save();

						if($loop_idx == 1 ){
							// update avatar utama
							$itemAvatar = Item::find($items->id);
							$itemAvatar->avatar = $avatarName;
				            $itemAvatar->save();	
						}

						// insert ke table item_avatar
						\DB::table('item_avatars')->insert([
								'item_id' => $items->id,
								'avatar' => $avatarName
							]);
					}
					
					$loop_idx++;					
				}
				
        	}

        	 /** 
        	 * End of Edit by Eries Herman
        	 **/

            Session::flash('message', 'You have successfully updated item');
            return Redirect::to('items');

		});
            
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
			$items = Item::find($id);
	        $items->delete();

	        Session::flash('message', 'You have successfully deleted item');
	        return Redirect::to('items');
	}


    public function createItemEditLog($items)
    {
        
        foreach (Input::get() as $key => $value) {
            if (isset($items->{$key})) {
                if ($value != $items->{$key}) {
                    $log = new ItemEditLog;
                    $log->item_id = $items->id;
                    $log->user_id = Auth::user()->id;
                    $log->field = $key;
                    $log->old_value = $items->{$key};
                    $log->new_value = $value;
                    $log->save();
                }
            }
        }
    }

}
