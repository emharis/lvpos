<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model {

	public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function supplier()
    {
        return $this->belongsTo('App\Supplier');
    }

}
