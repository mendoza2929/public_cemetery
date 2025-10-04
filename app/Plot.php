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
            'available' => '#A52A2A', // Brown
            'sold' => '#FF0000', // Red
            'reserved' => '#FFFF00', // Yellow
            'quitclaim' => '#00FF00', // Green
            'restricted' => '#FFA500', // Orange
            'sold_with_burial' => '#800080' // Purple
        ];
        return $colors[$this->status] ?: '#000000'; // Fixed syntax
    }

}
