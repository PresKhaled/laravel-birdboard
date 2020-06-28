<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ProjectFactory;

class ProjectTasksTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    
    /** @test */
    public function guests_cannot_add_tasks_to_projects()
    {
        $project = factory('App\Project')->create();

        $this->post($project->url() . '/task')->assertRedirect('login');
    }

    /** @test */
    public function only_the_owner_of_a_project_may_add_tasks()
    {
        //$this->withoutExceptionHandling();

        $this->actingAsUser();// Sign in

        $project = factory('App\Project')->create();

        // Store request - data -
        $attributes = [
            'body' => 'Some task'
        ];
        
        // Store task associated with the current project (of differnet - unauthorized - user) into database
        $this->post(route('saveTask', $project->id), $attributes)
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', $attributes);
    }

    /** @test */
    public function only_the_owner_of_a_project_may_update_a_task()
    {
        $this->actingAsUser();// Sign in

        $project = ProjectFactory::withTasks(1)->create();

        $attributes = ['body' => 'Updated'];

        // Update the task associated with the current project (of differnet - unauthorized - user) into database
        $this->patch(route('updateTask', [
            'project' => $project->id,
            'task' => $project->first()->id
        ]), $attributes)
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', $attributes);
    }

    /** @test */
    public function a_project_can_have_tasks()
    {
        $project = ProjectFactory::withTasks(1)->create();

        // Create a task with the associated project
        //$task = factory('App\Task')->create(['project_id' => $project->id]);

        // Grab the task saved in the database via (Setup/Factory - Fluent API -) from Eloquent Model relationship
        $task = $project->tasks->first();

        // Store the task
        $this->actingAs($project->owner)->post($project->url(), ['body' => $task->body]);

        // Show the task within the view
        $this->get($project->url())
            ->assertSee($task->body);
    }

    /** @test */
    public function a_task_can_be_updated()
    {
        $project = ProjectFactory::withTasks(1)->create();

        // Update request - patch - data
        $attributes = [
            'body' => 'Updated',
            'completed' => true
        ];

        // Try to update the task, should be success
        $this->actingAs($project->owner)->patch(route('updateTask', [
            'project' => $project->id,
            'task' => $project->tasks->first()->id
        ]), $attributes);

        $this->assertDatabaseHas('tasks', $attributes);
    }

    /** @test */
    public function a_task_requires_a_body()
    {
        $project = ProjectFactory::create();

        $attributes = factory('App\Task')->raw(['body' => '']);

        // Check validate, try to save the task without "body".
        $this->actingAs($project->owner)->post($project->url() . '/task', $attributes)->assertSessionHasErrors('body');
    }
}
