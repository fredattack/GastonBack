<?php
namespace App\Services;

use App\Http\Resources\PetResource;
use App\Http\Resources\PetResourceCollection;
use App\Repositories\PetRepository;

class PetService {
    protected $petRepository;

    public function __construct(PetRepository $petRepository) {
        $this->petRepository = $petRepository;
    }

    public function getAllPets() {
        return new PetResourceCollection($this->petRepository->all());
    }

    //Generate crud methods here
    public function getById($id) {
        return new PetResource($this->petRepository->find($id));
    }

    public function create($data) {
        return new PetResource($this->petRepository->create($data));
    }

    public function update($data, $id) {
        return new PetResource($this->petRepository->update($data, $id));
    }

    public function delete($id) {
        $this->petRepository->delete($id);
    }
}
