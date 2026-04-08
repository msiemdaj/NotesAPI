<?php

namespace App\Services;

use App\Events\NoteCreated;
use App\Models\Note;
use App\Repositories\NoteRepositoryInterface;

class NoteService
{
    public function __construct(
        private readonly NoteRepositoryInterface $noteRepository,
    ) {}

    public function create(array $data)
    {
        $data['user_id'] = auth()->id();
        $note = $this->noteRepository->create($data);
        event(new NoteCreated($note));
        return $note;
    }

    public function update(Note $note, array $data): Note
    {
        return $this->noteRepository->update($note, $data);
    }
    public function delete(Note $note): void
    {
        $this->noteRepository->delete($note);
    }
}
