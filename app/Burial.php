<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Burial extends Model {

		protected $table = 'burials';
        protected $fillable = ['deceased_name', 'burial_date', 'notes','date_of_birth','date_of_death'];
        public function plot()
        {
            return $this->hasOne(Plot::class, 'burial_id');
        }
}
