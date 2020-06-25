<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = [];
    
    public function url()
    {
        return route('project', $this->id);
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }
}
