<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Locations extends Model
{
    protected $table='locations';
    public $timestamps=true;
    public $incrementing=true;

    protected $fillable = [
        'city',
        'country'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function locations() {
        return $this->belongsToMany('App\Candidates','candidate_location','location_id','candidate_id')
            ->withTimestamps();
    }
}
