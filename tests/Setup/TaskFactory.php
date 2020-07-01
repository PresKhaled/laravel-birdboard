<?php

namespace Tests\Setup;

use App\Task;

class TaskFactory
{
    public function create(array $attributes = [])
    {
        return factory(Task::class)->create($attributes);
    }
}
