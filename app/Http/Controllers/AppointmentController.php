<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Appointment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    /**
     * Display a listing of appointments for receptionist.
     */
    public function index(Request $request): View
    {
        $status = $request->input('status');

        $appointments = Appointment::query()
            ->with(['patient', 'test'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderByRaw("CASE WHEN status = 'pending' THEN 1 WHEN status = 'confirmed' THEN 2 ELSE 3 END")
            ->latest('appointment_date')
            ->paginate(15)
            ->withQueryString();

        return view('reception.appointments.index', compact('appointments', 'status'));
    }

    /**
     * Display appointment details.
     */
    public function show(Appointment $appointment): View
    {
        $appointment->load(['patient', 'test', 'order']);

        return view('reception.appointments.show', compact('appointment'));
    }

    /**
     * Confirm appointment.
     */
    public function confirm(Appointment $appointment): RedirectResponse
    {
        if ($appointment->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending appointments can be confirmed.');
        }

        $appointment->update([
            'status' => 'confirmed',
        ]);

        return redirect()
            ->back()
            ->with('success', "Appointment #{$appointment->id} for {$appointment->patient_name} has been confirmed.");
    }

    /**
     * Cancel appointment with reason.
     */
    public function cancel(Request $request, Appointment $appointment): RedirectResponse
    {
        if (! in_array($appointment->status, ['pending', 'confirmed'])) {
            return redirect()->back()->with('error', 'Only pending or confirmed appointments can be cancelled.');
        }

        $request->validate([
            'cancellation_reason' => ['required', 'string', 'max:1000'],
        ]);

        $appointment->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->input('cancellation_reason'),
        ]);

        return redirect()
            ->back()
            ->with('success', "Appointment #{$appointment->id} has been cancelled.");
    }

    /**
     * Update appointment details (date/time, notes, status).
     */
    public function update(UpdateAppointmentRequest $request, Appointment $appointment): RedirectResponse
    {
        $appointment->update($request->validated());

        return redirect()
            ->route('reception.appointments.index')
            ->with('success', "Appointment #{$appointment->id} updated successfully.");
    }
}
