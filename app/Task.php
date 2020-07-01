<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];

    /**
     * The relationships that should be touched on save.
     *
     * @var array
     */
    protected $touches = ['project'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function url()
    {
        return route('task', [
            'project' => $this->project->id,
            'task' => $this->id
        ]);
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'completed' => 'boolean'
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($task) {
            $task->project->recordActivity('created_task');
        });
    }

    /**
     * Mark the task as complete.
     */
    public function complete()
    {
        $this->update(['completed' => true]);

        $this->project->recordActivity('completed_task');
    }
}
