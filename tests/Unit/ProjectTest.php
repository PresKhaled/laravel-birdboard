<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;// Good move
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\Tests\Setup\ProjectFactory;
use App\User;

class ProjectTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function a_project_has_a_url()
    {
        $project = ProjectFactory::create();

        $this->assertEquals(route('project', $project->id), $project->url());
    }

    /** @test */
    public function it_belongs_to_an_owner()
    {
        $project = ProjectFactory::create();

        $this->assertInstanceOf('App\User', $project->owner);
    }

    /** @test */
    public function it_can_invite_a_user()
    {
        $project = ProjectFactory::create();

        $project->invite($user = factory(User::class)->create());

        $this->assertTrue($project->members->contains($user));
    }

    /** @test */
    public function it_can_add_a_task()
    {
        $project = ProjectFactory::create();

        $task = $project->addTask('Some task');// Save a task associated with current project to database, hasMany->create() relationship

        $this->assertCount(1, $project->tasks);// Check if the tasks collection have an index

        $this->assertTrue($project->tasks->contains($task));
    }
}
