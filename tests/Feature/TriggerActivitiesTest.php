<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
//use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;

use App\Task;

class TriggerActivitiesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function creating_a_project()
    {
        $project = ProjectFactory::create();// There's an Observer on Project model

        $this->assertCount(1, $project->activities);

        $this->assertEquals('created', $project->activities->first()->description);
    }

    /** @test */
    public function updating_a_project()
    {
        $project = ProjectFactory::create();// There's an Observer on Project model

        $project->update(['title' => 'Updated']);

        $this->assertCount(2, $project->activities);

        $this->assertEquals('updated', $project->activities->last()->description);
    }

    /** @test */
    public function creating_a_task()
    {
        $project = ProjectFactory::create();

        $project->addTask('Some task');

        $this->assertCount(2, $project->activities);

        tap($project->activities->last(), function ($activity) {
            $this->assertEquals('created_task', $activity->description);

            $this->assertInstanceOf(Task::class, $activity->subject);

            $this->assertEquals('Some task', $activity->subject->body);
        });
    }

    /** @test */
    public function completing_a_task()
    {
        $project = ProjectFactory::withTasks(1)->create();

        // Update completed status - true -
        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->url(), [
                'body' => 'Come get some!',
                'completed' => true
            ]);

        $this->assertCount(3, $project->activities);

        tap($project->activities->last(), function ($activity) {
            $this->assertEquals('completed_task', $activity->description);

            $this->assertInstanceOf(Task::class, $activity->subject);
        });
    }

    /** @test */
    public function incompleting_a_task()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->url(), [
                'body' => 'Some words',
                'completed' => true
            ]);

        $this->assertCount(3, $project->activities);

        $this->patch($project->tasks->first()->url(), [
            'body' => 'Some words',
            'completed' => false
        ]);

        $project->refresh();// :sparkles:

        $this->assertCount(4, $project->activities);

        $this->assertEquals('incompleted_task', $project->activities->last()->description);
    }

    /** @test */
    public function deleting_a_task()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $project->tasks->first()->delete();

        $this->assertCount(3, $project->activities);
    }
}
