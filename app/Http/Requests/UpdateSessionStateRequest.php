<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSessionStateRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var \App\Models\TrainingSession|null $session */
        $session = $this->route('trainingSession');

        return $session ? $this->user()?->can('update', $session) ?? false : false;
    }

    public function rules(): array
    {
        return [
            'state' => ['required', 'in:waiting,live,paused,ended'],
        ];
    }
}
