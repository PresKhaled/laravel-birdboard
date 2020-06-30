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
    public function creating_a_project_generates_activity()
    {
        $project = ProjectFactory::create();// There's an Observer on Project model

        $this->assertCount(1, $project->activities);

        $this->assertEquals('created', $project->activities->first()->description);
    }

    /** @test */
    public function updating_a_project_generates_activity()
    {
        $project = ProjectFactory::create();// There's an Observer on Project model

        $project->update(['title' => 'Updated']);

        $this->assertCount(2, $project->activities);

        $this->assertEquals('updated', $project->activities->last()->description);
    }
}
