<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientController extends Controller
{
    /**
     * Display a listing of patients with search.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $patients = Patient::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('national_id', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('reception.patients.index', compact('patients', 'search'));
    }

    /**
     * Show the form for creating a new patient.
     */
    public function create(): View
    {
        return view('reception.patients.create');
    }

    /**
     * Store a newly created patient in storage.
     */
    public function store(StorePatientRequest $request): RedirectResponse
    {
        $patient = Patient::create($request->validated());

        return redirect()
            ->route('reception.patients.index')
            ->with('success', "Patient {$patient->name} created successfully.");
    }

    /**
     * Display the specified patient profile with history.
     */
    public function show(Patient $patient): View
    {
        $patient->load(['appointments' => fn ($q) => $q->latest(), 'orders' => fn ($q) => $q->latest()]);

        return view('reception.patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified patient.
     */
    public function edit(Patient $patient): View
    {
        return view('reception.patients.edit', compact('patient'));
    }

    /**
     * Update the specified patient in storage.
     */
    public function update(UpdatePatientRequest $request, Patient $patient): RedirectResponse
    {
        $patient->update($request->validated());

        return redirect()
            ->route('reception.patients.index')
            ->with('success', "Patient {$patient->name} updated successfully.");
    }

    /**
     * Remove the specified patient from storage (Admin only).
     */
    public function destroy(Request $request, Patient $patient): RedirectResponse
    {
        if (! $request->user()->isAdmin()) {
            abort(403, 'Only administrators can delete patient records.');
        }

        $name = $patient->name;
        $patient->delete();

        return redirect()
            ->route('reception.patients.index')
            ->with('success', "Patient {$name} deleted successfully.");
    }

    /**
     * Search patients via AJAX/JSON for order creation.
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        if (! $query || strlen($query) < 2) {
            return response()->json([]);
        }

        $patients = Patient::query()
            ->where('name', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->orWhere('national_id', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name', 'phone', 'gender', 'age', 'national_id']);

        return response()->json($patients);
    }
}
