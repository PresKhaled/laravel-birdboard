<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Project extends Model
{
    protected $guarded = [];

    /**
     * The project's old attributes.
     *
     * @var array
     */
    public $old = [];
    
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

    // Project activities
    public function activities()
    {
        return $this->hasMany(Activity::class)->latest();
    }

    // TODO: Change the name and the place of this method, maybe UseCase or Services class?
    public function activitiesDiffForHumans(string $description): string
    {
        return [
            // Project activities with colors classes
            "created" => "Project created",
            "created_color" => "text-info",

            "updated" => "Project updated",
            "updated_color" => "text-success",

            // Task activities with colors classes
            "created_task" => "Task added",
            "created_task_color" => "text-info",

            "completed_task" => "Task completed",
            "completed_task_color" => "text-success",

            "incompleted_task" => "Task incompleted",
            "incompleted_task_color" => "text-danger"

        ][$description];
    }

    /**
     * Fetch the changes to the model.
     *
     * @param  string $description
     * @return array|null
     */
    protected function activityChanges($description)
    {
        if ($description == 'updated') {
            return [
                'before' => Arr::except(array_diff($this->old, $this->getAttributes()), 'updated_at'),
                'after' => Arr::except($this->getChanges(), 'updated_at')
            ];
        }
    }

    /**
     * Record activity for a project.
     *
     * @param string $type
     */
    public function recordActivity($description)
    {
        // TODO: Fix the task 'incompleted_task' activity records, when update task 'body'.
        //$this->activities()->create(compact('description'));

        $this->activities()->create([
            'description' => $description,
            'changes' => $this->activityChanges($description)
        ]);
    }
}
