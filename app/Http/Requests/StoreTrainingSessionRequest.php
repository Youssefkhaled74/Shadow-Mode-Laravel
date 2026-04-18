<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTrainingSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \App\Models\TrainingSession::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:120'],
            'scenario_type' => ['required', 'in:interview,sales,negotiation,communication'],
            'description' => ['nullable', 'string', 'max:1200'],
            'scheduled_for' => ['nullable', 'date', 'after:now'],
            'coach_id' => ['nullable', 'exists:users,id'],
            'settings' => ['nullable', 'array'],
        ];
    }
}
