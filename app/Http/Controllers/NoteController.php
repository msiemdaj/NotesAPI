<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Http\Resources\NoteResource;
use App\Repositories\NoteRepositoryInterface;
use App\Services\NoteService;
use Illuminate\Http\Request;

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
        return (NoteResource::collection($this->noteRepository->all()))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNoteRequest $request)
    {
        return (new NoteResource($this->noteService->create($request->validated())))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        return (new NoteResource($this->noteRepository->find($id)))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNoteRequest $request, int $id)
    {
        $updated = $this->noteService->update(
            $this->noteRepository->findForUser($request->user(), $id),
            $request->validated()
        );

        return (new NoteResource($updated))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id)
    {
        $this->noteService->delete($this->noteRepository->findForUser($request->user(), $id));
        return response()->json(
            null, 204
        );
    }
}
