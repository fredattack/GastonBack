<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PetFormRequest;
use App\Models\Pet;
use App\Services\PetService;

class PetController extends Controller {


    public function __construct(protected PetService $petService) {}


    public function index() {
        return response()->json($this->petService->getAllPets());
    }
    public function show(Pet $pet) {
        return response()->json($this->petService->getById($pet));
    }
    public function store(PetFormRequest $request) {
        return response()->json($this->petService->create($request->all()), 201);
    }
    public function update(PetFormRequest $request, Pet $pet) {
        return response()->json($this->petService->update($request->validated(), $pet->id));
    }
    public function destroy(Pet $pet) {

        try {
            $this->petService->delete($pet->id);
        }
        catch (\Exception $e) {
            return response()->json(['message' => 'Pet not found'], 404);
        }
        return response()->json("Pet deleted", 204);
    }
}

