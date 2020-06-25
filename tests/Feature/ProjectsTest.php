<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function only_authenticated_user_can_create_a_project()
    {
        $this->post('projects', factory('App\Project')->raw())->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_create_a_project()
    {
        $this->withoutExceptionHandling();

        $this->actingAs(factory('App\User')->create());

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph
        ];

        $this->post('projects', $attributes)->assertRedirect('projects');

        $this->assertDatabaseHas('projects', $attributes);

        $this->get('projects')->assertSee($attributes['title']);
    }

    /** @test */
    public function a_project_must_have_an_owner()
    {
        //$this->withoutExceptionHandling();

        $attributes = factory('App\Project')->raw(['owner_id' => '']);//

        $this->post('projects')->assertSessionHasErrors('owner_id');
    }

    /** @test */
    public function a_project_must_have_a_title()
    {
        //$attributes = factory('App\Project')->raw();//['title' => '']

        $this->post('projects')->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_must_have_a_description()
    {
        //$attributes = factory('App\Project')->raw();//['description' => '']

        $this->post('projects')->assertSessionHasErrors('description');
    }

    /** @test */
    public function a_user_can_view_a_project()
    {
        $this->withoutExceptionHandling();

        $project = factory('App\Project')->create();

        $this->get('project/' . $project->id)
            ->assertSee($project->title)
            ->assertSee($project->description);
    }
}
