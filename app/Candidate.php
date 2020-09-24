<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Candidate extends Model
{

    protected $table='candidates';
    public $timestamps=true;
    public $incrementing=true;

    protected $fillable = [
        'name',
        'birth_date',
        'skills'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];


    public function locations() {
        return $this->belongsToMany('App\Locations','candidate_location','candidate_id','location_id')
            ->withTimestamps();
    }

    public function jobs() {
        return $this->belongsToMany('App\Jobs','candidate_job','candidate_id','job_id')
            ->withTimestamps();
    }


}
