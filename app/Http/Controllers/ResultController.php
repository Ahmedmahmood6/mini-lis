<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Result;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ResultController extends Controller
{
    /**
     * Display pending lab work queue for technician.
     */
    public function index(Request $request): View
    {
        $status = $request->input('status', 'in_progress');

        $orders = Order::query()
            ->with(['patient', 'orderItems.test', 'orderItems.result'])
            ->withCount('orderItems')
            ->when($status === 'completed', fn ($q) => $q->completed())
            ->when($status !== 'completed', fn ($q) => $q->whereIn('status', ['pending', 'in_progress']))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('lab.orders.index', compact('orders', 'status'));
    }

    /**
     * Show result entry form for an order and its test items.
     */
    public function editOrderResults(Order $order): View
    {
        $order->load([
            'patient',
            'orderItems.test',
            'orderItems.result',
            'creator',
        ]);

        return view('lab.orders.edit', compact('order'));
    }

    /**
     * Store/Update test results for an order.
     * If all test results are filled, order status automatically transitions to 'completed'.
     */
    public function updateOrderResults(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'results' => ['required', 'array', 'min:1'],
            'results.*.order_item_id' => ['required', 'exists:order_items,id'],
            'results.*.result_text' => ['required', 'string', 'max:1000'],
            'results.*.notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($validated, $order, $request) {
            foreach ($validated['results'] as $itemData) {
                $result = Result::where('order_item_id', $itemData['order_item_id'])->first();

                if ($result) {
                    $result->update([
                        'result_text' => $itemData['result_text'],
                        'notes' => $itemData['notes'] ?? null,
                        'status' => 'entered',
                        'technician_id' => $request->user()->id,
                    ]);
                }
            }

            // Check if all test items in this order now have entered results
            $pendingResultsCount = Result::whereIn('order_item_id', $order->orderItems()->pluck('id'))
                ->whereNull('result_text')
                ->count();

            if ($pendingResultsCount === 0) {
                $order->update(['status' => 'completed']);
            } else {
                $order->update(['status' => 'in_progress']);
            }
        });

        return redirect()
            ->route('lab.orders.index')
            ->with('success', "Lab results for Order {$order->order_number} saved successfully.");
    }
}
