<?php

namespace Tests\Feature;

use App\Models\Pet;
use App\Models\User;
use App\Http\Resources\PetResource;
use App\Http\Resources\PetResourceCollection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Création d'un utilisateur pour l'authentification
        $this->user = User::factory()->create();
    }

    /** @test */
    public function it_can_list_all_pets()
    {
        Pet::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->getJson('/api/pets');

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

    /** @test */
    public function it_can_create_a_pet()
    {
        $payload = [
            'name' => 'Luna',
            'species' => 'cat',
            'breed' => 'Maine Coon',
            'birth_date' => '2020-05-15',
            'is_active' => true,
            'order' => 1,
            'owner_id' => $this->user->id,
            'photo' => 'https://example.com/photo.jpg',
            'galerie' => json_encode(['https://example.com/photo1.jpg'])
        ];

        $response = $this->actingAs($this->user)->postJson('/api/pets', $payload);

        $response->assertCreated()
            ->assertJson((new PetResource(Pet::first()))->response()->getData(true));

        $this->assertDatabaseHas('pets', [
            'name' => 'Luna',
            'species' => 'cat'
        ]);
    }

    /** @test */
    public function it_can_show_a_pet()
    {
        $pet = Pet::factory()->create();

        $response = $this->actingAs($this->user)->getJson("/api/pets/{$pet->id}");

        $response->assertOk()
            ->assertJson((new PetResource($pet))->response()->getData(true));
    }

    /** @test */
    public function it_can_update_a_pet()
    {
        $pet = Pet::factory()->create();

        $payload = ['name' => 'Updated Name'];

        $response = $this->actingAs($this->user)->putJson("/api/pets/{$pet->id}", $payload);

        $response->assertOk()
            ->assertJsonFragment(['name' => 'Updated Name']);

        $this->assertDatabaseHas('pets', ['name' => 'Updated Name']);
    }

    /** @test */
    public function it_can_delete_a_pet()
    {
        $pet = Pet::factory()->create();

        $response = $this->actingAs($this->user)->deleteJson("/api/pets/{$pet->id}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('pets', ['id' => $pet->id]);
    }
}
