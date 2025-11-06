<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model {

	protected $table = 'reservation';
	protected $fillable = ['name','address','number','date','reservation_no','status','payment_method','plot_id','name_deceased','relationship'];

}
