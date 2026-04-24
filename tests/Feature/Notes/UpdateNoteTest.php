<?php

namespace Tests\Feature\Notes;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateNoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_their_own_note(): void
    {
        $user = User::factory()->create();
        $note = Note::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson('/api/notes/'.$note->id, [
                'title' => 'New title',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.title', 'New title');

        $this->assertDatabaseHas('notes', [
            'id' => $note->id,
            'title' => 'New title',
        ]);
    }

    public function test_user_cannot_update_another_users_note(): void
    {
        $user = User::factory()->create();
        $note = Note::factory()->create(['user_id' => $user->id]);

        $user = User::factory()->create();
        $response = $this->actingAs($user, 'sanctum')
            ->putJson('/api/notes/'.$note->id, [
                'title' => 'New title',
            ]);

        $response->assertStatus(403);
    }

    public function test_update_fails_when_title_is_too_short(): void
    {
        $user = User::factory()->create();
        $note = Note::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->putJson('/api/notes/'.$note->id, [
                'title' => 'Ne',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('title');
    }
}
