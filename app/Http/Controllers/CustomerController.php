<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Customer;
use App\Http\Requests\CustomerRequest;
use \Auth, \Redirect, \Validator, \Input, \Session;
use Image;
use Illuminate\Http\Request;
use App\Store;
use App\User;

class CustomerController extends Controller {

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
            if (Auth::user()->is_admin == 1) {
                $customers = Customer::all();
            } else {
                $customers = Customer::where('created_by', Auth::user()->id)->get();
            }
 
            foreach ($customers as $customer) {
                try { 
                    $store = Store::find($customer->store_id);
                    $customer->store_name = $store->store_name;
                    $user = User::find($customer->created_by);
                    $customer->created_by_name = $user->name;
                } catch (\Exception $e) {
                    $customer->store_name = '';
                }

            }
			return view('customer.index')->with('customer', $customers);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{      
            if (Auth::user()->is_admin == 1) {
                $stores = Store::lists('store_name', 'id');
            } else {
                $userStores = json_decode(Auth::user()->store_json);
                $stores = Store::whereIn('id', $userStores)->lists('store_name', 'id');

            }
			return view('customer.create')->with('stores', $stores);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(CustomerRequest $request)
	{
	            // store
	            $customers = new Customer;
	            $customers->name = Input::get('name');
	            $customers->email = Input::get('email');
	            $customers->phone_number = Input::get('phone_number');
	            $customers->address = Input::get('address');
	            $customers->city = Input::get('city');
	            $customers->state = Input::get('state');
	            $customers->zip = Input::get('zip');
	            $customers->company_name = Input::get('company_name');
	            $customers->store_id = Input::get('store_id');
                $customers->dropshipper = Input::get('dropshipper');
                $customers->created_by = Auth::user()->id;
	            $customers->save();
	            // process avatar
	            $image = $request->file('avatar');
				if(!empty($image)) {
					$avatarName = 'cus' . $customers->id . '.' . 
					$request->file('avatar')->getClientOriginalExtension();

					$request->file('avatar')->move(
					base_path() . '/public/images/customers/', $avatarName
					);
					$img = Image::make(base_path() . '/public/images/customers/' . $avatarName);
					$img->resize(100, null, function ($constraint) {
    					$constraint->aspectRatio();
					});
					$img->save();
					$customerAvatar = Customer::find($customers->id);
					$customerAvatar->avatar = $avatarName;
		            $customerAvatar->save();
	        	}
	            Session::flash('message', 'You have successfully added customer');
	            return Redirect::to('/sales/create?customer_id='.$customers->id);
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
		$customers = Customer::find($id);
        if (Auth::user()->is_admin == 1) {
            $stores = Store::lists('store_name', 'id');
        } else {
            $userStores = json_decode(Auth::user()->store_json);
            $stores = Store::whereIn('id', $userStores)->lists('store_name', 'id');

        }
        return view('customer.edit')
            ->with('customer', $customers)->with('stores', $stores);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(CustomerRequest $request, $id)
	{
	            $customers = Customer::find($id);
	            $customers->name = Input::get('name');
	            $customers->email = Input::get('email');
	            $customers->phone_number = Input::get('phone_number');
	            $customers->address = Input::get('address');
	            $customers->city = Input::get('city');
	            $customers->state = Input::get('state');
	            $customers->zip = Input::get('zip');
	            $customers->company_name = Input::get('company_name');
                $customers->store_id = Input::get('store_id');
	            $customers->dropshipper = Input::get('dropshipper');
	            $customers->save();
	            // process avatar
	            $image = $request->file('avatar');
				if(!empty($image)) {
					$avatarName = 'cus' . $id . '.' . 
					$request->file('avatar')->getClientOriginalExtension();

					$request->file('avatar')->move(
					base_path() . '/public/images/customers/', $avatarName
					);
					$img = Image::make(base_path() . '/public/images/customers/' . $avatarName);
					$img->resize(100, null, function ($constraint) {
    					$constraint->aspectRatio();
					});
					$img->save();
					$customerAvatar = Customer::find($id);
					$customerAvatar->avatar = $avatarName;
		            $customerAvatar->save();
	        	}
	            // redirect
	            Session::flash('message', 'You have successfully updated customer');
	            return Redirect::to('customers');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		try
		{
			$customers = Customer::find($id);
	        $customers->delete();
	        // redirect
	        Session::flash('message', 'You have successfully deleted customer');
	        return Redirect::to('customers');
        }
    	catch(\Illuminate\Database\QueryException $e)
		{
    		Session::flash('message', 'Integrity constraint violation: You Cannot delete a parent row');
    		Session::flash('alert-class', 'alert-danger');
	        return Redirect::to('customers');	
    	}
	}

}
