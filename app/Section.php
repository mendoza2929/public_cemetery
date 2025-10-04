<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model {

	protected $table = 'sections';
	protected $fillable = ['cemetery_id', 'name', 'lat', 'lng'];
    public function cemetery() {
        return $this->belongsTo(Cemetery::class);
    }
    public function plots() {
        return $this->hasMany(Plot::class);
    }

}
