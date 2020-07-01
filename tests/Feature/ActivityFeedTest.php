<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
//use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ActivityFeedTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function creating_a_project_records_activity()
    {
        $project = ProjectFactory::create();// There's an Observer on Project model

        $this->assertCount(1, $project->activities);

        $this->assertEquals('created', $project->activities->first()->description);
    }

    /** @test */
    public function updating_a_project_records_activity()
    {
        $project = ProjectFactory::create();// There's an Observer on Project model

        $project->update(['title' => 'Updated']);

        $this->assertCount(2, $project->activities);

        $this->assertEquals('updated', $project->activities->last()->description);
    }

    /** @test */
    public function creating_a_new_task_records_project_activity()
    {
        $project = ProjectFactory::create();

        $project->addTask('Some task');

        $this->assertCount(2, $project->activities);

        $this->assertEquals('created_task', $project->activities->last()->description);
    }

    /** @test */
    public function completing_a_new_task_records_project_activity()
    {
        $project = ProjectFactory::withTasks(1)->create();

        // Update completed status - true -
        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->url(), [
                'body' => 'Come get some!',
                'completed' => true
            ]);

        $this->assertCount(3, $project->activities);

        $this->assertEquals('completed_task', $project->activities->last()->description);
    }
}
