<?php

namespace App\Http\Requests;

use App\Models\TrainingSession;
use Illuminate\Foundation\Http\FormRequest;

class SendCoachingHintRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var TrainingSession|null $session */
        $session = $this->route('trainingSession');

        return $this->user() !== null
            && $session !== null
            && $this->user()->can('view', $session);
    }

    public function rules(): array
    {
        return [
            'category' => ['required', 'in:confidence,clarity,pace,filler,question,follow_up,general'],
            'severity' => ['required', 'in:low,medium,high'],
            'content' => ['required', 'string', 'max:500'],
            'target_user_id' => ['nullable', 'exists:users,id'],
            'meta' => ['nullable', 'array'],
        ];
    }
}
