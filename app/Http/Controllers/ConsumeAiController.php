<?php

namespace App\Http\Controllers;

use App\Services\ConsumeAiService;

use Carbon\Carbon;
use Illuminate\Http\Request;

class ConsumeAiController extends Controller
{
    //constructor injection
    public function __construct(protected ConsumeAiService $consumeAiService)
    {
    }
    public function __invoke(Request $request)
    {
//        ray($request->all())->die();
        ray()->clearScreen();
        //{
        //  "prompt": "Melchior doit manger 40 gr de paté ce midi",
        //  "filters": {}
        //}

//        $data = [
//            'score' => '100%',
//            'requestType' => 'createEvent',
//            'description' => 'Melchior doit manger 40 gr de paté ce midi',
//            'response' => [
//                'id' => '',
//                'title' => 'Repas de Melchior',
//                'petId' => 14,
//                'type' => 'feeding',
//                'start_date' => Carbon::parse( '2025-01-14T13:00:00Z'),
//                'end_date' => '',
//                'is_recurring' => false,
//                'is_full_day' => false,
//                'recurrence' => [
//                    'frequency_type' => 'daily',
//                    'has_end_recurrence' => false,
//                    'end_recurrence_date' => '',
//                    'occurrences' => '',
//                    'frequency' => 1,
//                    'days' => []
//                ],
//                'notes' => '40 gr de paté'
//            ]
//        ];


//        return json_encode( $data);
        return $this->consumeAiService->consumeAi($request->input('prompt'));
    }
}
