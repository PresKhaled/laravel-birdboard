<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function actingAsUser($realUser = null)
    {
        return $this->actingAs($realUser ?: factory('App\User')->create());
    }
}
