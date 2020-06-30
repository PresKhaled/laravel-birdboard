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

    public function addTask(string $body)
    {
        return $this->tasks()->create(compact('body'));
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
