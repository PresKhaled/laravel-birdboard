<?php

namespace Tests\Setup;

use App\Task;

class TaskFactory
{
    public function create()
    {
        return factory(Task::class)->create();
    }
}
