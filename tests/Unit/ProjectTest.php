<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;// Good move
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function a_project_has_a_url()
    {
        $project = factory('App\Project')->create();

        $this->assertEquals(route('project', $project->id), $project->url());
    }

    /** @test */
    public function it_belongs_to_an_owner()
    {
        $project = factory('App\Project')->create();

        $this->assertInstanceOf('App\User', $project->owner);
    }

    /** @test */
    public function it_can_add_a_task()
    {
        $project = factory('App\Project')->create();

        $task = $project->addTask('Test task');

        $this->assertCount(1, $project->tasks);
        $this->assertTrue($project->tasks->contains($task));
    }
}
