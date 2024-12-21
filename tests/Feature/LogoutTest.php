<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'Amr Elsayed',
            'email' => 'test@unit.com',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_authenticated_user_can_logout(): void
    {
        $token = $this->user->createToken($this->user->name)->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->post(route('logout'));

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'user logged out',
            ]);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable' => $this->user->id,
        ]);
    }

    public function test_unauthenticated_user_cannot_logout(): void
    {
        $response = $this->post(route('logout'));

        $response->assertStatus(401);
    }
}
