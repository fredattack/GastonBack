<?php

namespace Tests\Feature;

use App\Models\Pet;
use App\Models\User;
use App\Http\Resources\PetResource;
use App\Http\Resources\PetResourceCollection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PetControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    #[Test]
    public function it_can_list_all_pets()
    {
        Pet::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->getJson('/api/v1-0-0/pets');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id', 'name', 'species', 'breed', 'birthDate',
                        'isActive', 'order', 'ownerId', 'photo', 'galerie', 'createdAt'
                    ]
                ],
                'meta' => ['total', 'generated_at']
            ]);
    }

    #[Test]
    public function it_can_create_a_pet()
    {
        $this->withExceptionHandling();


        $payload = [
            'name' => 'Gaston',
            'species' => 'cat',
            'breed' => 'Cornish rex',
            'birth_date' => '2020-05-15',
            'is_active' => true,
            'gender' => "male",
            'order' => 1,
            'owner_id' => $this->user->id,
        ];

        $this->assertDatabaseCount('pets', 0);

        $response = $this->actingAs($this->user)->postJson('/api/v1-0-0/pets', $payload);

        $response->assertCreated();
//            ->assertJson((new PetResource(Pet::first()))->response()->getData(true));

        $this->assertDatabaseCount('pets', 1);
        $this->assertCount(1,
            Pet::where('name', 'Gaston')
                ->where('species','cat')
                ->where('breed','Cornish rex')
                ->get());
    }



    #[Test]
    public function it_can_update_a_pet()
    {
        $pet = Pet::factory()->create(['name' => 'Marcel']);

        $this->assertDatabaseHas('pets', ['name' => 'Marcel']);
        $payload = [ 'name' => 'Gaston',
            'species' => 'cat',
            'breed' => 'Cornish rex',
            'birth_date' => '2020-05-15',
            'is_active' => true,
            'gender' => "male",
            'order' => 1,
            'owner_id' => $this->user->id,];

        $response = $this->actingAs($this->user)->putJson("/api/v1-0-0/pets/{$pet->id}", $payload);

        $response->assertOk();
        $this->assertDatabaseMissing ('pets', ['name' => 'Marcel']);
        $this->assertDatabaseHas('pets', ['name' => 'Gaston']);



    }

    #[Test]
    public function it_can_delete_a_pet()
    {
        $pet = Pet::factory()->create();

        $this->assertDatabaseHas('pets', ['id' => $pet->id]);
        $this->assertDatabaseCount('pets', 1);
        $response = $this->actingAs($this->user)->deleteJson("/api/v1-0-0/pets/{$pet->id}");
        $response->assertNoContent();

        $this->assertDatabaseCount('pets', 0);
    }
}
