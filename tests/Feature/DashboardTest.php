<?php

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can view dashboard statistics', function () {
    $user = User::factory()->create(['role' => 'admin']);
    Patient::factory()->count(5)->create();
    Appointment::factory()->create(['status' => 'pending', 'appointment_date' => now()]);
    Appointment::factory()->create(['status' => 'confirmed', 'appointment_date' => now()]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertStatus(200);
    $response->assertViewHas('stats', function ($stats) {
        return isset(
            $stats['total_patients'],
            $stats['todays_appointments'],
            $stats['pending_appointments'],
            $stats['confirmed_appointments'],
            $stats['pending_results'],
            $stats['completed_reports'],
            $stats['paid_invoices'],
            $stats['unpaid_invoices'],
            $stats['total_revenue']
        );
    });
});

test('unauthenticated guest is redirected from dashboard to login', function () {
    $response = $this->get(route('dashboard'));

    $response->assertRedirect(route('login'));
});
