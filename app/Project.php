<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use RecordsActivity;

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
}
