<?php

namespace App\Http\Requests;

use App\Models\Test;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'patient_id' => ['required', 'exists:patients,id'],
            'appointment_id' => ['nullable', 'exists:appointments,id'],
            'test_ids' => ['required', 'array', 'min:1'],
            'test_ids.*' => ['required', 'exists:tests,id'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'in:cash,card,online'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $testIds = (array) $this->input('test_ids', []);
            if (! empty($testIds)) {
                $totalAmount = (float) Test::whereIn('id', $testIds)->sum('price');
                $discount = (float) $this->input('discount', 0.00);
                $netAmount = max(0.00, $totalAmount - $discount);
                $paidAmount = (float) $this->input('paid_amount', 0.00);

                if ($paidAmount < $netAmount) {
                    $validator->errors()->add(
                        'paid_amount',
                        'Paid amount must equal the net total amount ('.number_format($netAmount, 2).' EGP).'
                    );
                }
            }
        });
    }
}
