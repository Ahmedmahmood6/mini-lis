<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'patient_id' => ['required', 'exists:patients,id'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'in:cash,card,online'],
            'notes' => ['nullable', 'string'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $order = $this->route('order');
            if ($order) {
                $totalAmount = (float) $order->orderItems()->sum('price');
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
