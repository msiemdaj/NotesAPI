<?php

namespace App\Listeners;

use App\Events\NoteCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LogNoteCreated implements ShouldQueue
{
    public function handle(NoteCreated $event): void
    {
        Log::channel('notes')->info('Note created', [
            'note_id'    => $event->note->id,
            'user_id'    => $event->note->user_id,
            'created_at' => $event->note->created_at,
        ]);
    }
}
