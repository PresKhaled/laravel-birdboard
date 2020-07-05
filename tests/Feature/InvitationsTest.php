<?php

namespace Tests\Feature;

use Facades\Tests\Setup\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\User;
use App\Project;

class InvitationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function none_owners_may_not_invite_users()
    {
        $this->actingAs(factory(User::class)->create())
            ->post(ProjectFactory::create()->url() . '/invitation')
            ->assertStatus(403);
    }

    /** @test */
    public function a_project_owner_can_invite_a_user()
    {
        $project = ProjectFactory::create();

        $userToInvite = factory(User::class)->create();

        $this->actingAs($project->owner)
            ->post($project->url() . '/invitation', [
                'email' => $userToInvite->email
            ])
            ->assertRedirect($project->url());

        $this->assertTrue($project->members->contains($userToInvite));
    }

    /** @test */
    public function the_email_address_must_be_associated_with_a_valid_birdboard_account()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->post($project->url() . '/invitation', [
                'email' => 'notauser@none.none'
            ])
            ->assertSessionHasErrors([
                'email' => 'The user you are inviting must have a Birdboard account.'
            ]);
    }

    /** @test */
    public function invited_users_may_update_project_details()
    {
        $project = ProjectFactory::create();

        $project->invite($newUser = factory(User::class)->create());

        $this
            ->actingAs($newUser)
            ->post(action('TaskController@store', $project), $task = ['body' => 'Some task']);

        $this->assertDatabaseHas('tasks', $task);
    }
}
