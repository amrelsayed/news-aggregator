<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     */
    public function test_model_has_expected_fields(): void
    {
        $user = User::factory()->create();

        $this->assertEquals([
            'name',
            'email',
            'email_verified_at',
            'updated_at',
            'created_at',
            'id',
        ], array_keys($user->toArray()));
    }
}
