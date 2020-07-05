<?php

namespace Tests\Unit;

use App\User;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_has_projects()
    {
        $user = factory('App\User')->create();

        $this->assertInstanceOf(Collection::class, $user->projects);
    }

    /** @test */
    public function a_user_has_accessible_projects()
    {
        $mohamed = $this->actingAsUser();

        ProjectFactory::ownedBy($mohamed)->create();

        $this->assertCount(1, $mohamed->sharedProjects());

        $ahmed = factory(User::class)->create();
        $mahmoud = factory(User::class)->create();

        $project = tap(ProjectFactory::ownedBy($ahmed)->create())->invite($mahmoud);

        $this->assertCount(1, $mohamed->sharedProjects());

        $project->invite($mohamed);

        $this->assertCount(2, $mohamed->sharedProjects());
    }
}
