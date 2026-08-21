<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patients = [
            [
                'name' => 'Mohamed Ali',
                'phone' => '01011112222',
                'gender' => 'male',
                'age' => 35,
                'national_id' => '29101011200111',
                'address' => '123 Nile Street, Cairo',
            ],
            [
                'name' => 'Mona Mahmoud',
                'phone' => '01122223333',
                'gender' => 'female',
                'age' => 28,
                'national_id' => '29605051200222',
                'address' => '45 Al-Galaa Street, Giza',
            ],
            [
                'name' => 'Khaled Omar',
                'phone' => '01233334444',
                'gender' => 'male',
                'age' => 50,
                'national_id' => '27402021200333',
                'address' => '78 El-Tahrir Square, Alexandria',
            ],
            [
                'name' => 'Fatima Hassan',
                'phone' => '01544445555',
                'gender' => 'female',
                'age' => 42,
                'national_id' => '28208081200444',
                'address' => '12 Abbasia, Cairo',
            ],
            [
                'name' => 'Youssef Ibrahim',
                'phone' => '01055556666',
                'gender' => 'male',
                'age' => 19,
                'national_id' => '30510101200555',
                'address' => '99 University Road, Mansoura',
            ],
        ];

        foreach ($patients as $patient) {
            Patient::updateOrCreate(['phone' => $patient['phone']], $patient);
        }
    }
}
