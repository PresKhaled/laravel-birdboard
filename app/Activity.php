<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = [];

    public function project()
    {
        $this->belongsTo(Project::class);
    }

    public function subject()
    {
        return $this->morphTo();
    }
}
