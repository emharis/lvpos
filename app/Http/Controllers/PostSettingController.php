<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PostSetting;
use App\Http\Requests\PostSettingRequest;
use \Auth, \Redirect, \Validator, \Input, \Session;
use Image;
use Illuminate\Http\Request;

class PostSettingController extends Controller {

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
        $settings = PostSetting::all();
 
		return view('post_setting.index')->with('settings', $settings);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('post_setting.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(PostSettingRequest $request)
	{
        $setting = new PostSetting;
        $setting->name = Input::get('name');
  
        $setting->save();
    
        Session::flash('message', 'You have successfully added option');
        return Redirect::to('postsettings');
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
		$setting = PostSetting::find($id);
        return view('post_setting.edit')
            ->with('setting', $setting);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(PostSettingRequest $request, $id)
	{
        $setting = PostSetting::find($id);
        $setting->name = Input::get('name');
        $setting->save();
    
        Session::flash('message', 'You have successfully updated option');
        return Redirect::to('postsettings');
	}

 

}
