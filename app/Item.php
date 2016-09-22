<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model {

	public function inventory()
    {
        return $this->hasMany('App\Inventory')->orderBy('id', 'DESC');
    }

    public function receivingtemp()
    {
        return $this->hasMany('App\ReceivingTemp')->orderBy('id', 'DESC');
    }

    public function changes()
    {
        return $this->hasMany('App\ItemEditLog')->orderBy('id', 'DESC');
    }

    public function category(){
        return $this->belongsTo('App\ItemCategory','item_category_id');
    }

    public function avatars(){
        return $this->hasMany('App\ItemAvatar','item_id');
    }
}
