<?php

namespace App\Services;

use App\Enums\EventType;
use App\Models\Pet;
use OpenAI\Laravel\Facades\OpenAI;

class ConsumeAiService
{
    public function consumeAi($prompt)
    {
        $superPrompt = $this->getSuperPrompt();
        $message = [
            ['role' => 'developer', 'content' => $superPrompt],
            ['role' => 'user', 'content' => $prompt],
        ];

        $result = OpenAI::chat()->create( [
            'model' => 'gpt-3.5-turbo',
            'messages' => $message,
        ] );

        return $result->choices[0]->message->content;
    }

    /**
     * @return string
     */
    protected function getSuperPrompt(): string
    {
        $typedResponse = [
            "eventResponse" => $this->getEventStructure(),
            "petResponse" => $this->getPetStructure()
        ];

        $parameters = [
            'language' => 'fr_BE',
            'today' => now()->format( 'Y-m-d' ),
            'eventType' => EventType::asArray(),
            'pets' => Pet::pluck( 'name', 'id' )->toArray(),
            'typedResponse' => $typedResponse,
            'timereference' => [
                'morning' => '08:00:00UTC',
                'midday' => '13:00:00UTC',
                'evening' => '19:00:00UTC',
            ]
        ];

        return json_encode( [
            "instructions" => "Generate a response based on the provided message and parameters. Response must respect requirements.responseFormat",
            "parameters" => $parameters,
            "requirements" => [
                "score" => "Certainty percentage determined dynamically based on input message and parameters.",
                "description" => "A short summary of the input message.",
                "response" => " la réponse deva avoir le format suivant : [
                    score => 'XX%',
                    requestType => createPet | updatePet | createEvent | updateEvent,
                    description => une description précisse du message du user,
                    response => object de type 'eventResponse' ou  object de type 'petResponse',
                ]",
                "If recurrence is mentioned, set isRecurring to true and populate recurrence details.",
                "If an animal's name is specified, ensure it appears in the title field if present.",
                "pets" => "add an array of arrays of pets  for each pets concerned.",
                "Si un moment de la journee est évoquée choisissez l'heure par rapport parameters.timereference",
                "response must be a simple object formated by one of the typedResponse structures. ",
                "respecte le plus possible 'parameters.language' pour la langue de la réponse.",
                "!!!!! La réponse sera un json brut sans style ou decoration markedown!!!!! "
            ]
        ] );
    }

    /**
     * @return array
     */
    protected function getPetStructure(): array
    {
        $petStructure = [
            'id' => '',
            'name' => '',
            'birthDate' => "// Format : 'YYYY-MM-DD'",
            'breed' => '',
            'is_active' => true,
            'order' => 0,
            'ownerId' => '',
            'species' => "// Exemple : 'dog' | 'cat' | 'pig' |",
        ];
        return $petStructure;
    }

    /**
     * @return array
     */
    protected function getEventStructure(): array
    {
        return [
            'id' => '',
            'title' => 'value as a string and must contain if possiblme the name of the pet',
            'petId' => 'value as an array of integers based on parameters.pets if type is not medical or feeding',
            'type' => "// Exemple : 'medical' | 'feeding' | 'appointment' | ...'",
            'start_date' => 'value as DateTime',
            'end_date' => 'value as DateTime || null',
            'is_recurring' => 'value as boolean',
            'is_full_day' => 'value as boolean',
            "pets" => [
                ["pivot" => [
                    "pet_id" => 'value as an integer based on parameters.pets',
                    "item" => "value as a string",
                    "quantity" => "value as a string",
                    "notes" => "value as a string || null"
                ]]
            ],
            'recurrence' => [
                'frequency_type' => 'daily',
                'end_recurrence_date' => 'value as DD-MM-YYYY hh:ii || null',
                'occurrences' => 'value as an integer || null',
                'frequency' => 1,
                'days' => 'value as array of strings Exemple : ["monday", "tuesday"]'
            ],
            'notes' => 'value as a string it must déscribe synteticaly the details of the event what to do or what to expect.',
        ];
    }
}
