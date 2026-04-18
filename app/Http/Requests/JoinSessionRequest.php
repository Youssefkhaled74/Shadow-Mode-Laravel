<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JoinSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'room_code' => ['required', 'string', 'size:8'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'room_code' => strtoupper((string) $this->input('room_code')),
        ]);
    }
}
