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

        $task = $project->addTask('Some task');// Save a task associated with current project to database, hasMany->create() relationship

        $this->assertCount(1, $project->tasks);// Check if the tasks collection have an index

        $this->assertTrue($project->tasks->contains($task));
    }
}
