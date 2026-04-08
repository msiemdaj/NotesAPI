<?php

namespace App\Repositories;

use App\Models\Note;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface NoteRepositoryInterface
{
    public function allForUser(User $user): LengthAwarePaginator;
    public function findForUser(User $user, int $id): Note;
    public function create(array $data): Note;
    public function update(Note $note, array $data): Note;
    public function delete(Note $note): void;
}
