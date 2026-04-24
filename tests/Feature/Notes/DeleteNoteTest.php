<?php

namespace Tests\Feature\Notes;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteNoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_delete_their_own_note(): void
    {
        $user = User::factory()->create();
        $note = Note::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson('/api/notes/'.$note->id);

        $response->assertStatus(204);
        $this->assertSoftDeleted('notes', ['id' => $note->id]);
    }

    public function test_user_cannot_delete_another_users_note(): void
    {
        $user = User::factory()->create();
        $note = Note::factory()->create(['user_id' => $user->id]);

        $user = User::factory()->create();
        $response = $this->actingAs($user, 'sanctum')
            ->deleteJson('/api/notes/'.$note->id);

        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_delete_note(): void
    {
        $note = Note::factory()->create();
        $response = $this->deleteJson('/api/notes/'.$note->id);
        $response->assertStatus(401);
    }

}
