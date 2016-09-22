<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\TutaposSetting;
use \Auth, \Redirect, \Validator, \Input, \Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Store;
use App\Http\Requests\StoreRequest;

class TutaposSettingController extends Controller
{
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
        $tutapos_settings = TutaposSetting::find(1)->first();
        $stores = Store::all();

        return view('tutapos-setting.index')->with('tutapos_settings', $tutapos_settings)
                                            ->with('stores', $stores);
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
        $tutapos_settings = TutaposSetting::find($id);
        $tutapos_settings->languange = Input::get('language');
        $tutapos_settings->price_ref_formula = Input::get('price_ref_formula');
        $tutapos_settings->shipping_factor = Input::get('shipping_factor');
        $tutapos_settings->margin_factor = Input::get('margin_factor');
        $tutapos_settings->retail_price_factor = Input::get('retail_price_factor');
        $tutapos_settings->save();

        Session::flash('message', 'You have successfully saved settings');
        return Redirect::to('settings');
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


    public function addStore(StoreRequest $request)
    {
        $store = new Store;
        $store->store_name = Input::get('store_name');
        $store->save();

        Session::flash('message', 'You have successfully add store');
        return Redirect::to('settings');
    }
}
