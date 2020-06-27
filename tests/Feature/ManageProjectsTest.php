<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Project;
use Tests\TestCase;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function guests_cannot_manage_projects()
    {
        $project = factory('App\Project')->create();

        $this->get('projects')->assertRedirect('login');// Show all projects
        $this->get('projects/create')->assertRedirect('login');// Create project view
        $this->get($project->url())->assertRedirect('login');// Access or view a project
        $this->post('projects', $project->toArray())->assertRedirect('login');// Save a new project into database
    }

    /** @test */
    public function a_user_can_create_a_project()
    {
        $this->actingAsUser();// Sign in

        $this->get('projects/create')->assertStatus(200);// Authorized to enter a create project view

        // Project data
        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->sentence,
            'notes' => null
        ];
        
        $response = $this->post('projects', $attributes);// Save project into database

        $this->assertDatabaseHas('projects', $attributes);// Check database.projects has this data

        $project = Project::where($attributes)->first();// Get saved project from database

        $response->assertRedirect($project->url());// Redirect to project view

        $this->get($project->url())// A view contains the project data
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);
    }

    /** @test */
    public function a_user_can_update_a_project()
    {
        $this->actingAsUser();// Sign in

        $project = factory('App\Project')->create(['owner_id' => auth()->id()]);// Create a project with current user

        // Project update data
        $attributes = [
            'notes' => 'Updated'
        ];

        // Patch (Update) request to ProjectController@update
        $this->patch(route('updateProject', $project->id), $attributes)->assertRedirect($project->url());

        // Check the data within database
        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test */
    public function a_user_can_view_his_project()
    {
        $this->actingAsUser();// Sign in

        $project = factory('App\Project')->create(['owner_id' => auth()->id()]);// Create a new project associated with the current user

        $this->get($project->url())// Check if the data within the view
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    /** @test */
    public function an_authenticated_user_cannot_view_the_projects_of_others()
    {
        $this->actingAsUser();// Sign in

        $project = factory('App\Project')->create();

        $this->get($project->url())->assertStatus(403);// Check if the user is the owner of the project, otherwise throw unauthorized exception.
    }

    /** @test */
    public function an_authenticated_user_cannot_update_the_projects_of_others()
    {
        $this->actingAsUser();// Sign in

        $project = factory('App\Project')->create();

        $this->patch($project->url())->assertStatus(403);// Check if the user is the owner of the project, otherwise throw unauthorized exception.
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        $this->actingAsUser();

        $attributes = factory('App\Project')->raw(['title' => '']);

        $this->post('projects', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_a_description()
    {
        $this->actingAsUser();// Sign in

        $attributes = factory('App\Project')->raw(['description' => '']);

        $this->post('projects', $attributes)->assertSessionHasErrors('description');
    }
}
