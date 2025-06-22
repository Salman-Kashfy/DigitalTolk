<?php

namespace App\Modules\Translation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Translation\Services\TranslationService;
//use App\Modules\Translation\Http\Requests\SearchTranslationsRequest;
//use App\Modules\Translation\Http\Requests\ExportTranslationsRequest;
use App\Modules\Translation\Http\Requests\CreateTranslationRequest;
use App\Modules\Translation\Http\Requests\UpdateTranslationRequest;
use App\Modules\Translation\Http\Resources\TranslationResource; // Resource for API responses
use App\Modules\Translation\DTOs\CreateTranslationData;
use App\Modules\Translation\DTOs\UpdateTranslationData;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TranslationController extends Controller
{
    private TranslationService $service;

    public function __construct(TranslationService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {

        // The service's search method returns a LengthAwarePaginator
        $translations = $this->service->searchTranslations(
            $request->input('query'),
            $request->input('tags'),
            $request->input('locale'),
            $request->input('group'),
            (int) $request->input('per_page', 15)
        );

        // Use a TranslationResource to format the collection for API response
        return TranslationResource::collection($translations);
    }

    public function store(CreateTranslationRequest $request)
    {
        $data = CreateTranslationData::fromArray($request->validated());
        $translation = $this->service->createTranslation($data);
        return new TranslationResource($translation);
    }

    public function show(int $id)
    {
        try {
            $translation = $this->service->findById($id);

            if (!$translation) {
                throw new ModelNotFoundException('Translation not found.');
            }

            return new TranslationResource($translation);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    public function update(UpdateTranslationRequest $request, int $id)
    {
        try {
            $data = UpdateTranslationData::fromArray($request->validated());
            $translation = $this->service->updateTranslation($id, $data);
            return new TranslationResource($translation);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    public function destroy(int $id)
    {
        try {
            $deleted = $this->service->deleteTranslation($id);

            if (!$deleted) {
                // If soft-deleted, it might still return false if already deleted,
                // or if ID not found, but service should throw ModelNotFoundException
                // or handle it. Assuming service handles not found by returning false.
                throw new ModelNotFoundException('Translation not found or already deleted.');
            }

            // Return a 204 No Content response for successful deletion
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    public function export(Request $request)
    {
        $data = $this->service->getExportData(
            $request->input('locale'),
            $request->input('groups'),
            $request->input('tags')
        );
        return response()->json($data);
    }
}
