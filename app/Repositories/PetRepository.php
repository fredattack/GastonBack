<?php

namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use App\Models\Pet;

class PetRepository implements RepositoryInterface
{
    public function all()
    {
        $collection =  Pet::filter()->get();

        return $collection;

    }

    public function find($id)
    {
        return Pet::findOrFail( $id );
    }

    public function create(array $data)
    {
        return Pet::create( $data );
    }

    public function update($id, array $data)
    {
        $pet = Pet::find( $id );
        $pet->update( $data );
        return $pet;
    }

    public function delete($id)
    {
        $pet = Pet::find( $id );
        $pet->delete();

    }
}
