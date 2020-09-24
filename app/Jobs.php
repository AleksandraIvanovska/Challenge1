<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
    protected $table='jobs';
    public $timestamps=true;
    public $incrementing=true;

    protected $fillable = [
        'title',
        'started_at',
        'finished_at',
        'description'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'started_at',
        'finished_at',
    ];


    public function candidates() {
        return $this->belongsToMany('App\Candidate','candidate_job','job_id','candidate_id')
            ->withTimestamps();
    }

}
