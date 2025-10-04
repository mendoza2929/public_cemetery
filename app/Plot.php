<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Plot extends Model {
	protected $table = 'plots';
	protected $fillable = ['section_id', 'number', 'lat', 'lng', 'status', 'burial_id'];
  public function section() {
        return $this->belongsTo(Section::class);
    }
    public function burial() {
        return $this->belongsTo(Burial::class);
    }

    public function getStatusColorAttribute() {
        $colors = [
            'available' => '#30a52aff', // Brown
            'sold' => '#ff000053', // Red
            'reserved' => '#FFFF00', // Yellow
            'quitclaim' => '#ff00e6ff', // Green
            'restricted' => '#ff0000ff', // Orange
            'sold_with_burial' => '#800080' // Purple
        ];
        return $colors[$this->status] ?: '#000000'; // Fixed syntax
    }

}
