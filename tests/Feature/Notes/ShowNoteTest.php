<?php

namespace Tests\Feature\Notes;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowNoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_their_own_note(): void
    {
        $user = User::factory()->create();
        $note = Note::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/notes/'.$note->id);

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $note->id)
            ->assertJsonPath('data.title', $note->title);
    }

    public function test_user_cannot_view_another_users_note(): void
    {
        $user = User::factory()->create();
        $note = Note::factory()->create(['user_id' => $user->id]);

        $user = User::factory()->create();
        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/notes/'.$note->id);

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_view_note(): void
    {
        $note = Note::factory()->create();

        $response = $this->getJson('/api/notes/'.$note->id);
        $response->assertStatus(401);
    }
}
