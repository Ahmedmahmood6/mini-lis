<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTestRequest;
use App\Http\Requests\UpdateTestRequest;
use App\Models\Test;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestController extends Controller
{
    /**
     * Display a listing of laboratory tests catalog.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $category = $request->input('category');

        $tests = Test::query()
            ->when($search, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            })
            ->when($category, fn ($q, $cat) => $q->where('category', $cat))
            ->orderBy('category')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $categories = Test::distinct()->pluck('category');

        return view('admin.tests.index', compact('tests', 'search', 'category', 'categories'));
    }

    /**
     * Show the form for creating a new lab test.
     */
    public function create(): View
    {
        return view('admin.tests.create');
    }

    /**
     * Store a newly created lab test in catalog.
     */
    public function store(StoreTestRequest $request): RedirectResponse
    {
        $test = Test::create($request->validated());

        return redirect()
            ->route('admin.tests.index')
            ->with('success', "Test '{$test->name}' ({$test->code}) added to catalog successfully.");
    }

    /**
     * Display the specified lab test details.
     */
    public function show(Test $test): View
    {
        $test->loadCount('orderItems');

        return view('admin.tests.show', compact('test'));
    }

    /**
     * Show the form for editing the specified lab test.
     */
    public function edit(Test $test): View
    {
        return view('admin.tests.edit', compact('test'));
    }

    /**
     * Update the specified lab test in storage.
     */
    public function update(UpdateTestRequest $request, Test $test): RedirectResponse
    {
        $test->update($request->validated());

        return redirect()
            ->route('admin.tests.index')
            ->with('success', "Test '{$test->name}' updated successfully.");
    }

    /**
     * Remove the specified lab test from storage safely.
     */
    public function destroy(Test $test): RedirectResponse
    {
        // Safe check: If test is already used in existing orders, deactivate it instead of failing foreign key constraint
        if ($test->orderItems()->exists()) {
            $test->update(['is_active' => false]);

            return redirect()
                ->route('admin.tests.index')
                ->with('warning', "Test '{$test->name}' cannot be hard deleted because it is associated with existing orders. It has been deactivated instead.");
        }

        $name = $test->name;
        $test->delete();

        return redirect()
            ->route('admin.tests.index')
            ->with('success', "Test '{$name}' deleted from catalog successfully.");
    }
}
