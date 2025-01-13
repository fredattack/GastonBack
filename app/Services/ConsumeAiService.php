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
        ray( $message );
        $result = OpenAI::chat()->create( [
            'model' => 'gpt-3.5-turbo',
            'messages' => $message,
        ] );

        return $result->choices[0]->message->content; // Hello! How can I assist you today?   // Code to consume AI
    }

    /**
     * @return string
     */
    protected function getSuperPrompt(): string
    {

        $eventStructure = [
            'id' => '',
            'title' => '',
            'petId' => '',
            'type' => "// Exemple : 'medical' | 'feeding' | 'appointment' | ...'",
            'start_date' => '',
            'end_date' => '',
            'is_recurring' => false,
            'is_full_day' => false,
            'recurrence' => [
                'frequency_type' => 'daily',
                'has_end_recurrence' => false,
                'end_recurrence_date' => '',
                'occurences' => '',
                'frequency' => 1,
                'days' => []
            ],
            'notes' => ''
        ];

        $petStructure = [
            'id' => '',
            'name' => '',
            'birthDate' => "// Format : 'YYYY-MM-DD'",
            'breed' => '',
            'createdAt' => "// Format : 'YYYY-MM-DD hh:ii' (now())",
            'is_active' => true,
            'order' => 0,
            'ownerId' => '',
            'species' => "// Exemple : 'dog' | 'cat'",
        ];

        // Combinaison des structures dans la réponse typée
        $typedResponse = [
            "eventResponse" => $eventStructure,
            "petResponse" => $petStructure
        ];

        $parameters = [
            'language' => 'fr_BE',
            'today' => now()->format( 'Y-m-d' ),
            'eventType' => EventType::asArray(),
            'pets' => Pet::pluck( 'name', 'id' )->toArray(),
            'typedResponse' => $typedResponse
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
                "response must be a simple object formated by one of the typedResponse structures. ",
                "respecte le plus possible 'parameters.language' pour la langue de la réponse.",
                "!!!!! La réponse sera un json brut sans style ou decoration markedown!!!!! "
            ]
        ]);
    }
}
