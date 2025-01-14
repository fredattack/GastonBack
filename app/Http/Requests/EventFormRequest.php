<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;  // Allow all users, customize if needed
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title'              => 'required|string|max:255',
            'petId'              => 'required|exists:pets,id',
            'type'               => 'required|string|in:medical,feeding,appointment,training,social',
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
            'recurrence.occurences'          => 'nullable|integer',
            'recurrence.days'                => 'nullable|array',
            'recurrence.days.*'              => 'string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
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
