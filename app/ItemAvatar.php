<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemAvatar extends Model {

	protected $table = 'item_avatars';

	public function items(){
		return $this->belongsTo('App\Items','item_id');
	}

}
