<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ProjectFactory;
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
        $this->get($project->url() . '/edit')->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_create_a_project()
    {
        $this->actingAsUser();

        $this->get('projects/create')->assertStatus(200);

        $this->followingRedirects()
            ->post('projects', $attributes = factory(Project::class)->raw())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);
    }

    /** @test */
    public function a_user_can_see_all_projects_they_have_been_invited_to_on_their_dashboard()
    {
        $project = tap(ProjectFactory::create())->invite($this->actingAsUser());
 
        $this->get('projects')->assertSee($project->title);
    }

    /** @test */
    public function a_user_can_update_a_project()
    {
        $project = ProjectFactory::create();

        // Project update data
        $attributes = [
            'title' => 'Updated',
            'description' => 'Updated',
            'notes' => 'Updated'
        ];

        // Patch (Update) request to ProjectController@update
        $this->actingAs($project->owner)
            ->patch(route('updateProject', $project->id), $attributes)
            ->assertRedirect($project->url());

        $this->get($project->url() . '/edit')->assertOk();

        // Check the data within database
        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test */
    public function a_user_can_delete_a_project()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->delete($project->url())
            ->assertRedirect('projects');

        $this->assertDatabaseMissing('projects', $project->only('id'));
    }

    /** @test */
    public function unauthorized_users_cannot_delete_projects()
    {
        $project = ProjectFactory::create();

        $this->delete($project->url())
            ->assertRedirect('login');

        $this->actingAsUser();

        $this->delete($project->url())
             ->assertStatus(403);
    }
    
    /** @test */
    public function a_user_can_update_a_projects_general_notes()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->patch($project->url(), $attributes = ['notes' => 'Updated']);

        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test */
    public function a_user_can_view_his_project()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)->get($project->url())// Check if the data within the view
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
