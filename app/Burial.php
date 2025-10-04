<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Burial extends Model {

		protected $table = 'burials';
	public function plot()
    {
        return $this->hasOne(Plot::class, 'burial_id');
    }
}
