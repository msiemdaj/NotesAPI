<?php

namespace App\Repositories;

use App\Models\Note;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentNoteRepository implements NoteRepositoryInterface
{
    public function all(): LengthAwarePaginator
    {
        return Note::paginate(15);
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
        return $note->fresh();
    }

    public function delete(Note $note): void
    {
        $note->delete();
    }
}
