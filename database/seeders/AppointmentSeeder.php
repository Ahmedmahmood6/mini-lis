<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patient1 = Patient::where('phone', '01011112222')->first();
        $patient2 = Patient::where('phone', '01122223333')->first();

        // 1. Pending online appointment without patient_id
        Appointment::create([
            'patient_name' => 'Tarek Ahmed',
            'phone' => '01099998888',
            'gender' => 'male',
            'age' => 30,
            'appointment_date' => now()->addDays(1)->setHour(10)->setMinute(0),
            'status' => 'pending',
            'notes' => 'Patient booked online for morning checkup.',
        ]);

        // 2. Confirmed appointment linked to Patient 1
        Appointment::create([
            'patient_name' => $patient1->name,
            'phone' => $patient1->phone,
            'gender' => $patient1->gender,
            'age' => $patient1->age,
            'appointment_date' => now()->addHours(2),
            'status' => 'confirmed',
            'notes' => 'Confirmed by receptionist over phone.',
            'patient_id' => $patient1->id,
        ]);

        // 3. Cancelled appointment
        Appointment::create([
            'patient_name' => 'Nour El-Din',
            'phone' => '01188887777',
            'gender' => 'male',
            'age' => 45,
            'appointment_date' => now()->subDays(1),
            'status' => 'cancelled',
            'notes' => 'Patient called to cancel.',
        ]);

        // 4. Completed appointment linked to Patient 2
        Appointment::create([
            'patient_name' => $patient2->name,
            'phone' => $patient2->phone,
            'gender' => $patient2->gender,
            'age' => $patient2->age,
            'appointment_date' => now()->subHours(4),
            'status' => 'completed',
            'notes' => 'Patient arrived and order created.',
            'patient_id' => $patient2->id,
        ]);
    }
}
