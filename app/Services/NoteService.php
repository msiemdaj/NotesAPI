<?php

namespace App\Services;

use App\Events\NoteCreated;
use App\Repositories\NoteRepositoryInterface;

class NoteService
{
    public function __construct(
        private readonly NoteRepositoryInterface $noteRepository,
    ) {}

    public function create(array $data)
    {
        $data['user_id'] = 1;
        $note = $this->noteRepository->create($data);
        event(new NoteCreated($note));
        return $note;
    }

    public function update(array $data)
    {

    }
}
