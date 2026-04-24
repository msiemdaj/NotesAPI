<?php

namespace Tests\Feature\Notes;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexNoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_list_their_notes(): void
    {
        $user = User::factory()->create();
        Note::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/notes');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data',
                'pagination' => [
                    'current_page',
                    'last_page',
                    'per_page',
                    'total',
                ],
            ])
            ->assertJson(['success' => true]);

        $this->assertCount(3, $response->json('data'));
    }

    public function test_user_cannot_see_other_users_notes(): void
    {
        $user = User::factory()->create();
        Note::factory()->count(3)->create(['user_id' => $user->id]);

        $user = User::factory()->create();
        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/notes');

        $response->assertStatus(200);
        $this->assertCount(0, $response->json('data'));
    }

    public function test_unauthenticated_user_cannot_list_notes(): void
    {
        $response = $this->getJson('/api/notes');
        $response->assertStatus(401);
    }

    public function test_notes_index_returns_pagination_metadata(): void
    {
        $user = User::factory()->create();
        Note::factory()->count(20)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/notes');

        $response->assertStatus(200)
            ->assertJsonPath('pagination.per_page', 15)
            ->assertJsonPath('pagination.total', 20);
    }
}
