<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Preference;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetUserPreferencesTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_can_retrieve_user_preferences()
    {
        $category = Category::factory()->create();

        Preference::create([
            'user_id' => $this->user->id,
            'preferencable_id' => $category->id,
            'preferencable_type' => Category::class,
        ]);

        $response = $this->actingAs($this->user)->getJson('/api/preferences');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'user_id',
                    'preferencable_id',
                    'preferencable_type',
                    'preferencable',
                ],
            ],
        ]);

        $response->assertJsonFragment([
            'user_id' => $this->user->id,
            'preferencable_type' => Category::class,
        ]);
    }
}
