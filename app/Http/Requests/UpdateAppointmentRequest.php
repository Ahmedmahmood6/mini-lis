<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && in_array($this->user()->role, ['admin', 'receptionist'], true);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'in:pending,confirmed,cancelled,completed'],
            'appointment_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'cancellation_reason' => ['required_if:status,cancelled', 'nullable', 'string', 'max:1000'],
        ];
    }
}
