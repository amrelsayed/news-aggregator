<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        // Prepare
        $this->user = User::factory()->create([
            'name' => 'Amr Elsayed',
            'email' => 'test@unit.com',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_user_can_login_with_correct_credentials(): void
    {
        // Arrange
        $response = $this->postJson(route('login'), [
            'email' => $this->user->email,
            'password' => 'password'
        ]);

        // Assert
        $response->assertOk()
            ->assertExactJsonStructure([
                'token',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'created_at'
                ],
            ]);
    }

    public function test_user_cannot_login_with_wrong_credentials(): void
    {
        $response = $this->postJson(route('login'), [
            'email' => $this->user->email,
            'password' => 'passwordsdf'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('email');
    }

    public function test_login_fails_with_missing_email(): void
    {
        $response = $this->postJson(route('login'), [
            'password' => 'password'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('email');
    }

    public function test_login_fails_with_missing_password(): void
    {
        $response = $this->postJson(route('login'), [
            'email' => $this->user->email
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrorFor('password');
    }
}
