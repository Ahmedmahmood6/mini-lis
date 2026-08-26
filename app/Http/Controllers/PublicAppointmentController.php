<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookAppointmentRequest;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Test;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PublicAppointmentController extends Controller
{
    /**
     * Show the public online appointment booking form.
     */
    public function create(): View
    {
        $tests = Test::active()->get(['id', 'code', 'name', 'price']);

        return view('booking', compact('tests'));
    }

    /**
     * Store a new online booking request from guest patient.
     */
    public function store(BookAppointmentRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $appointmentDateTime = "{$validated['preferred_date']} {$validated['preferred_time']}";

        // 1. Find existing patient by phone OR create new patient profile automatically
        $patient = Patient::firstOrCreate(
            ['phone' => $validated['phone']],
            [
                'name' => $validated['name'],
                'gender' => $validated['gender'] ?? 'male',
                'age' => $validated['age'] ?? 0,
            ]
        );

        // 2. Create the appointment with status = pending
        $appointment = Appointment::create([
            'patient_name' => $validated['name'],
            'phone' => $validated['phone'],
            'gender' => $validated['gender'] ?? $patient->gender,
            'age' => $validated['age'] ?? $patient->age,
            'appointment_date' => $appointmentDateTime,
            'test_id' => $validated['test_ids'][0] ?? null,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
            'patient_id' => $patient->id,
        ]);

                if (! empty($validated['test_ids'])) {
            $appointment->tests()->sync($validated['test_ids']);
        }

        return redirect()
            ->back()
            ->with('success', "Thank you, {$appointment->patient_name}! Your appointment booking request has been submitted successfully (ID: #{$appointment->id}). Our receptionist will contact you shortly.");
    }
}
