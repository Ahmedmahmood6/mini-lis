<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    /**
     * Display a listing of financial invoices.
     */
    public function index(Request $request): View
    {
        $status = $request->input('status');
        $search = $request->input('search');

        $invoices = Invoice::query()
            ->with(['order.patient'])
            ->when($status, fn ($q) => $q->where('payment_status', $status))
            ->when($search, function ($q, $search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('order', function ($o) use ($search) {
                        $o->where('order_number', 'like', "%{$search}%")
                            ->orWhereHas('patient', function ($p) use ($search) {
                                $p->where('name', 'like', "%{$search}%")
                                    ->orWhere('phone', 'like', "%{$search}%");
                            });
                    });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('reception.invoices.index', compact('invoices', 'status', 'search'));
    }

    /**
     * Display the specified invoice with details.
     */
    public function show(Invoice $invoice): View
    {
        $invoice->load([
            'order.patient',
            'order.orderItems.test',
            'order.creator',
        ]);

        return view('reception.invoices.show', compact('invoice'));
    }

    /**
     * Mark an invoice as paid (process payment).
     * Backend recalculates net amount and handles status transitions safely.
     */
    public function markAsPaid(Request $request, Invoice $invoice): RedirectResponse
    {
        $validated = $request->validate([
            'payment_method' => ['required', 'in:cash,card,online'],
            'discount' => ['nullable', 'numeric', 'min:0'],
        ]);

        // Backend recalculates total from actual order items to prevent frontend manipulation
        $totalAmount = (float) $invoice->order->orderItems()->sum('price');
        $discount = (float) ($validated['discount'] ?? $invoice->discount);
        $netAmount = max(0.00, $totalAmount - $discount);

        $invoice->update([
            'total_amount' => $totalAmount,
            'discount' => $discount,
            'net_amount' => $netAmount,
            'paid_amount' => $netAmount,
            'payment_status' => 'paid',
            'payment_method' => $validated['payment_method'],
        ]);

        return redirect()
            ->back()
            ->with('success', "Invoice {$invoice->invoice_number} has been marked as PAID successfully.");
    }

    /**
     * Recalculate invoice totals from backend database records.
     */
    public function recalculate(Invoice $invoice): RedirectResponse
    {
        $totalAmount = (float) $invoice->order->orderItems()->sum('price');
        $netAmount = max(0.00, $totalAmount - (float) $invoice->discount);

        $paymentStatus = 'unpaid';
        if ($invoice->paid_amount >= $netAmount && $netAmount > 0) {
            $paymentStatus = 'paid';
        } elseif ($invoice->paid_amount > 0) {
            $paymentStatus = 'partially_paid';
        }

        $invoice->update([
            'total_amount' => $totalAmount,
            'net_amount' => $netAmount,
            'payment_status' => $paymentStatus,
        ]);

        return redirect()
            ->back()
            ->with('success', "Invoice {$invoice->invoice_number} totals recalculated successfully.");
    }
}
