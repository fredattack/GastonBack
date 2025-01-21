<?php

namespace App\Http\Requests;

use App\Enums\EventType;
use Illuminate\Foundation\Http\FormRequest;

class EventFormRequest extends FormRequest
{
    private const ALLOWED_TYPES = ['medical', 'feeding', 'appointment', 'training', 'social'];


    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $petIdRules = 'required_if:type,!medical,!feeding|nullable|exists:pets,id';

        return [
            'title'              => 'required|string|max:255',
            'petId'              => $petIdRules,
            'type'               => 'required|string|in:' . implode(',', EventType::asArray()),
            'start_date'         => 'required|date|after_or_equal:today',
            'end_date'           => 'nullable|date|after_or_equal:start_date',
            'is_recurring'       => 'required|boolean',
            'is_full_day'        => 'required|boolean',
            'notes'              => 'nullable|string|max:1000',

            // Nested recurrence validation
            'recurrence'                     => 'nullable|array',
            'recurrence.frequency_type'      => 'required_if:is_recurring,true|string|in:daily,weekly,monthly',
            'recurrence.frequency'           => 'required_if:is_recurring,true|integer|min:1',
            'recurrence.end_date' => 'nullable|date|after_or_equal:start_date',
            'recurrence.occurrences'          => 'nullable|integer',
            'recurrence.days'                => 'nullable|array',
            'recurrence.days.*'              => 'string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',

            'pets_details' => 'nullable|array',
            'pets_details.*.pet_id' => 'required|integer|exists:pets,id',
            'pets_details.*.item' => 'required|string|max:255',
            'pets_details.*.quantity' => 'required|string|max:255',
            'pets_details.*.notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The event title is required.',
            'petId.required' => 'The associated pet is required.',
            'petId.exists'   => 'The selected pet does not exist.',
            'type.in'        => 'The event type must be one of: medical, feeding, appointment, training, or social.',
            'start_date.after_or_equal' => 'The start date must be today or later.',
            'end_date.after_or_equal'   => 'The end date must be after the start date.',
            'recurrence.frequency_type.in' => 'The recurrence frequency type must be daily, weekly, or monthly.',
        ];
    }
}
