<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Expense;
use App\Http\Requests\ExpenseRequest;
use \Auth, \Redirect, \Validator, \Input, \Session;
use Image;
use Illuminate\Http\Request;
use App\User;
use App\BankAccount;
use App\PostSetting;

class ExpenseController extends Controller {

	public function __construct()
	{
		$this->middleware('auth');
	}


    public function syncPostStringToTable()
    {
        $expenses = Expense::whereNull('post_id')->get();
        foreach ($expenses as $expense) {
            try {
                $postSetting = PostSetting::where('name', trim(strtolower($expense->post)))->first();
                if ($postSetting->id) {
                    $post_id = $postSetting->id;
                    
                }
            } catch (\Exception $e) {
                $postSetting = new PostSetting;
                $postSetting->name = trim($expense->post);
                $postSetting->save();

                $post_id =  $postSetting->id;
            }
            $update = Expense::find($expense->id);
            $update->post_id =  $post_id;
            $update->save();
        }
        
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{    

        //sync post to post_id
        $this->syncPostStringToTable();

        $expenses = Expense::where('id', '>', 0);


        if (Auth::user()->is_admin == 1) {
            if (Input::get('user_id')) {
                 $expenses = $expenses->where('user_id', Input::get('user_id'));
            }
             if (Input::get('post_id')) {
                 $expenses = $expenses->where('post_id', Input::get('post_id'));
            }

        } else {
             $expenses = $expenses->where('user_id', Auth::user()->id);
        }

        if (Input::get('from')) {
                 $expenses = $expenses->where('spent_at', '>=', Input::get('from'));
        }

        if (Input::get('to')) {
                 $expenses = $expenses->where('spent_at', '<=', Input::get('to'));
        }
        $expenses = $expenses->get();

        foreach ($expenses as $expense) {
            try {
               $bankAccount = BankAccount::find($expense->bank_account_id);
               $expense->bank_account_name = $bankAccount->account_name;
            } catch (\Exception $e) {
                $expense->bank_account_name = '';
            }

            try {
               $postSetting = PostSetting::find($expense->post_id);
               $expense->post_name = $postSetting->name;
            } catch (\Exception $e) {
                $expense->post_name = '';
            }
        }
        $employees = User::lists('name', 'id');
        $posts = PostSetting::lists('name', 'id');
        return view('expense.index')->with('expenses', $expenses)
		                            ->with('posts', $posts)
                                    ->with('employees', $employees);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $bankAccounts = BankAccount::lists('account_name', 'id');
        $postSettings = PostSetting::lists('name', 'id');
		return view('expense.create')->with('bankAccounts', $bankAccounts)
                                     ->with('postSettings', $postSettings);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(ExpenseRequest $request)
	{
        $expense = new Expense;
        $expense->spent_at = Input::get('spent_at');
        $expense->description = Input::get('description');
        $expense->post_id = Input::get('post_id');
        $expense->value = Input::get('value');
        $expense->po_invoice_number = Input::get('po_invoice_number');
        $expense->account = '';
        $expense->remarks = Input::get('remarks');
        $expense->user_id =  Auth::user()->id;
        $expense->bank_account_id =  Input::get('bank_account_id');
        $expense->no_resi =  Input::get('no_resi');
        $expense->shipping_method =  Input::get('shipping_method');
        $expense->received =  Input::get('received');
       
        $expense->save();
    
        Session::flash('message', 'You have successfully added expenses');
        return Redirect::to('expenses');
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
		$expenses = Expense::find($id);
        $bankAccounts = BankAccount::lists('account_name', 'id');
          $postSettings = PostSetting::lists('name', 'id');
        return view('expense.edit')
            ->with('expenses', $expenses)->with('bankAccounts', $bankAccounts)
            ->with('postSettings', $postSettings);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(ExpenseRequest $request, $id)
	{
        $expense = Expense::find($id);
        $expense->spent_at = Input::get('spent_at');
        $expense->description = Input::get('description');
        $expense->post_id = Input::get('post_id');
        $expense->value = Input::get('value');
        $expense->po_invoice_number = Input::get('po_invoice_number');
        $expense->account = '';
        $expense->remarks = Input::get('remarks');
        $expense->bank_account_id =  Input::get('bank_account_id');
        $expense->no_resi =  Input::get('no_resi');
        $expense->shipping_method =  Input::get('shipping_method');
        $expense->received =  Input::get('received');
        $expense->save();
    
        Session::flash('message', 'You have successfully updated supplier');
        return Redirect::to('expenses');
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
		$suppliers = Supplier::find($id);
        $suppliers->delete();
        Session::flash('message', 'You have successfully deleted supplier');
        return Redirect::to('suppliers');
        }
    	catch(\Illuminate\Database\QueryException $e)
		{
    		Session::flash('message', 'Integrity constraint violation: You Cannot delete a parent row');
    		Session::flash('alert-class', 'alert-danger');
	        return Redirect::to('suppliers');	
    	}
	}

}
