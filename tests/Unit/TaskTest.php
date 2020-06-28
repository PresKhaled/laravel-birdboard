<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
//use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use App\Task;
use App\Project;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_project()
    {
        $task = factory(Task::class)->create();

        $this->assertInstanceOf(Project::class, $task->project);
    }

    /** @test */
    public function it_has_a_url()
    {
        $task = factory(Task::class)->create();

        $this->assertEquals(route('task', [
            'project' => $task->project->id,
            'task' => $task->id
        ]), $task->url());
    }
}
