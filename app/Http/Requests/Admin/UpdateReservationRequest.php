<?php

namespace App\Http\Requests\Admin;

use App\Support\ReservationWorkflow;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    /**
     * @return array<string, list<mixed>>
     */
    public function rules(): array
    {
        return [
            'status' => [
                'sometimes',
                'required',
                'string',
                Rule::in(ReservationWorkflow::acceptedStatuses()),
            ],
            'deposit_paid_at' => ['sometimes', 'nullable', 'date'],
            'balance_paid_at' => ['sometimes', 'nullable', 'date'],
            'appointment_at' => ['sometimes', 'nullable', 'date'],
            'handover_address' => ['sometimes', 'nullable', 'string', 'max:500'],
            'travel_fee' => ['sometimes', 'nullable', 'numeric', 'min:0', 'max:99999.99'],
            'admin_notes' => ['sometimes', 'nullable', 'string', 'max:10000'],
            'is_test' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        foreach ([
            'deposit_paid_at',
            'balance_paid_at',
            'appointment_at',
            'handover_address',
            'admin_notes',
        ] as $field) {
            if ($this->input($field) === '') {
                $this->merge([$field => null]);
            }
        }

        if ($this->input('travel_fee') === '') {
            $this->merge(['travel_fee' => 0]);
        }
    }
}
