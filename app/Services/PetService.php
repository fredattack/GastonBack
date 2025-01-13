<?php
namespace App\Services;

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
}
