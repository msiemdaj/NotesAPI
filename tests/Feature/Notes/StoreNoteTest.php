<?php

namespace Tests\Feature\Notes;

use App\Events\NoteCreated;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Tests\TestCase;

class StoreNoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_a_note(): void
    {
        Event::fake();
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/notes', [
                'title' => 'Test note',
                'body'  => 'Note test body',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['id', 'title', 'body', 'created_at', 'updated_at'],
            ])
            ->assertJsonPath('data.title', 'Test note');

        $this->assertDatabaseHas('notes', [
            'title'   => 'Test note',
            'user_id' => $user->id,
        ]);

        Event::assertDispatched(NoteCreated::class);
    }

    public function test_store_fails_when_title_is_missing(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/notes', [
                'body'  => 'Note test body',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);;
    }

    public function test_store_fails_when_title_is_too_short(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/notes', [
                'title' => 'Te',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_store_fails_when_title_is_too_long(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/notes', [
                'title' => Str::random(260),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_unauthenticated_user_cannot_create_note(): void
    {
        $response = $this->postJson('/api/notes', [
            'title' => 'Test note',
        ]);

        $response->assertStatus(401);
    }
}
