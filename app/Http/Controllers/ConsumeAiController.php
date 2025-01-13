<?php

namespace App\Http\Controllers;

use App\Services\ConsumeAiService;
use Illuminate\Http\Request;

class ConsumeAiController extends Controller
{
    public function __invoke(Request $request,ConsumeAiService $consumeAiService)
    {
        ray()->clearScreen();
        return $consumeAiService->consumeAi($request->input('prompt'));
    }
}
