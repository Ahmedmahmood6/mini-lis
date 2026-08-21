<?php

namespace Database\Seeders;

use App\Models\Test;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tests = [
            [
                'code' => 'CBC',
                'name' => 'Complete Blood Count',
                'category' => 'Hematology',
                'unit' => '10^3/uL',
                'reference_range' => '4.5 - 11.0 10^3/uL',
                'price' => 150.00,
                'is_active' => true,
            ],
            [
                'code' => 'FBS',
                'name' => 'Fasting Blood Sugar (Glucose)',
                'category' => 'Biochemistry',
                'unit' => 'mg/dL',
                'reference_range' => '70 - 99 mg/dL',
                'price' => 50.00,
                'is_active' => true,
            ],
            [
                'code' => 'ALT',
                'name' => 'Liver Function (ALT)',
                'category' => 'Biochemistry',
                'unit' => 'U/L',
                'reference_range' => '7 - 56 U/L',
                'price' => 180.00,
                'is_active' => true,
            ],
            [
                'code' => 'CREAT',
                'name' => 'Serum Creatinine',
                'category' => 'Kidney Function',
                'unit' => 'mg/dL',
                'reference_range' => '0.7 - 1.3 mg/dL',
                'price' => 70.00,
                'is_active' => true,
            ],
            [
                'code' => 'LIPID',
                'name' => 'Lipid Profile',
                'category' => 'Biochemistry',
                'unit' => 'mg/dL',
                'reference_range' => '< 200 mg/dL',
                'price' => 200.00,
                'is_active' => true,
            ],
        ];

        foreach ($tests as $test) {
            Test::updateOrCreate(['code' => $test['code']], $test);
        }
    }
}
