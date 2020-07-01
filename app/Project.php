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

    /**
     * Record activity for a project.
     *
     * @param string $type
     */
    public function recordActivity($description)
    {
        // TODO: Fix the task 'incompleted_task' activity records, when update task 'body'.
        $this->activities()->create(compact('description'));
    }
}
