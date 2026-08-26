<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of staff accounts (receptionists & technicians).
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $users = User::query()
            ->whereIn('role', ['receptionist', 'technician'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'search'));
    }

    /**
     * Show the form for creating a new staff account.
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created staff account in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Staff member created successfully.');
    }

    /**
     * Show the form for editing an existing staff account.
     */
    public function edit(User $user): View
    {
        abort_if($user->isAdmin(), 403, 'Admin accounts cannot be managed from this UI.');

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified staff account in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        abort_if($user->isAdmin(), 403, 'Admin accounts cannot be managed from this UI.');

        $validated = $request->validated();

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Staff member updated successfully.');
    }

    /**
     * Remove the specified staff account from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        abort_if($user->isAdmin() || $user->id === auth()->id(), 403, 'Admin accounts cannot be deleted.');

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Staff member deleted successfully.');
    }
}
