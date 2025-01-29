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

    public function getById($id) {
        return new PetResource($this->petRepository->find($id));
    }

    public function create($data) {
        return new PetResource($this->petRepository->create($data));
    }

    public function update(array $data,int|string $id) {
        return new PetResource($this->petRepository->update($id,$data, ));
    }

    public function delete($id) {
      return   $this->petRepository->delete($id);
    }
}
