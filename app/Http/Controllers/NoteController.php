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
        return (NoteResource::collection($this->noteRepository->allForUser(auth()->user())))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNoteRequest $request)
    {
        return (new NoteResource($this->noteService->create(auth()->user(), $request->validated())))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        Gate::authorize('view', $note);

        return (new NoteResource($note))
            ->response()
            ->setStatusCode(200);
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

        return (new NoteResource($updated))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Note $note)
    {
        Gate::authorize('delete', $note);
        $this->noteService->delete($note);

        return response()->noContent();
    }
}
