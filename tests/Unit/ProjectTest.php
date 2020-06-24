<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;// Good move
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function a_project_has_a_url()
    {
        $project = factory('App\Project')->create();

        $this->assertEquals(route('project', $project->id), $project->url());
    }
}
