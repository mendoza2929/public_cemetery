<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Burial extends Model {

		protected $table = 'burials';
        protected $fillable = ['deceased_name', 'burial_date', 'notes'];
        public function plot()
        {
            return $this->hasOne(Plot::class, 'burial_id');
        }
}
