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
     * Mark the task as complete.
     */
    public function complete()
    {
        $this->update(['completed' => true]);

        $this->recordActivity('completed_task');
    }

    /**
     * Mark the task as incomplete.
     */
    public function incomplete()
    {
        $this->update(['completed' => false]);

        $this->recordActivity('incompleted_task');
    }

    /**
     * Record activity for a task.
     *
     * @param string $description
     */
    public function recordActivity($description)
    {
        $this->activity()->create([
            'project_id' => $this->project_id,
            'description' => $description
        ]);
    }

    /**
     * The activity feed for the task.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }
}
