<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model {

	protected $table = 'item_category';

	public function items(){
		return $this->hasMany('App\Items','item_category_id');
	}

}
