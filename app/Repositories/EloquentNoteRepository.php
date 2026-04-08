<?php

namespace App\Repositories;

use App\Models\Note;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentNoteRepository implements NoteRepositoryInterface
{
    public function allForUser(User $user): LengthAwarePaginator
    {
        return $user->notes()->paginate(15);
    }

    public function find(int $id): Note
    {
        return Note::find($id);
    }

    public function create(array $data): Note
    {
        return Note::create($data);
    }

    public function update(Note $note, array $data): Note
    {
        $note->update($data);
        return $note;
    }

    public function delete(Note $note): void
    {
        $note->delete();
    }
}
