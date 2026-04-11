<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use App\Repositories\NoteRepositoryInterface;
use App\Services\NoteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class NoteController extends Controller
{
    public function __construct(
        private readonly NoteRepositoryInterface $noteRepository,
        private readonly NoteService $noteService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notes = $this->noteRepository->allForUser(auth()->user());

        return $this->success(
            NoteResource::collection($notes->getCollection())->resolve(),
            'Data retrieved successfully',
            200,
            $notes
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNoteRequest $request)
    {
        $note = $this->noteService->create(
            auth()->user(),
            $request->validated()
        );

        return $this->success(
            new NoteResource($note),
            'Note added successfully',
            201,
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        Gate::authorize('view', $note);

        return $this->success(
            new NoteResource($note),
            'Data retrieved successfully',
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNoteRequest $request, Note $note)
    {
        $updated = $this->noteService->update(
            $note,
            $request->validated()
        );

        return $this->success(
            new NoteResource($updated),
            'Note updated successfully',
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        Gate::authorize('delete', $note);
        $this->noteService->delete($note);

        return response()->noContent();
    }
}
