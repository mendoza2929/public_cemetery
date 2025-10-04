<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Cemetery extends Model {
	protected $table = 'cemeteries';
	protected $fillable = ['name', 'lat', 'lng', 'entrance_lat', 'entrance_lng'];
	public function sections() {
        return $this->hasMany(Section::class);
    }

}
