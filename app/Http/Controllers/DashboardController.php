<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Patient;
use App\Models\Report;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the role-based system statistics for dashboard.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $stats = [
            'total_patients' => Patient::count(),
            'todays_appointments' => Appointment::whereDate('appointment_date', today())->count(),
            'pending_appointments' => Appointment::pending()->count(),
            'confirmed_appointments' => Appointment::confirmed()->count(),
            'pending_orders' => Order::pending()->count(),
            'pending_results' => Result::pending()->count(),
            'completed_reports' => Report::count(),
            'paid_invoices' => Invoice::paid()->count(),
            'unpaid_invoices' => Invoice::unpaid()->count(),
            'total_revenue' => (float) Invoice::sum('paid_amount'),
        ];

        // Recent Activity feeds
        $recentOrders = Order::with(['patient', 'invoice'])
            ->latest()
            ->limit(5)
            ->get();

        $recentAppointments = Appointment::with('patient')
            ->latest('appointment_date')
            ->limit(5)
            ->get();

        return view('dashboard', compact('user', 'stats', 'recentOrders', 'recentAppointments'));
    }
}
