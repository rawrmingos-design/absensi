<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Human Resources',
                'description' => 'Departemen yang menangani sumber daya manusia, rekrutmen, dan pengembangan karyawan',
                'head_of_department' => 'Sarah Johnson',
                'is_active' => true
            ],
            [
                'name' => 'Information Technology',
                'description' => 'Departemen yang menangani teknologi informasi, sistem, dan infrastruktur IT',
                'head_of_department' => 'Michael Chen',
                'is_active' => true
            ],
            [
                'name' => 'Finance & Accounting',
                'description' => 'Departemen yang menangani keuangan, akuntansi, dan pelaporan keuangan perusahaan',
                'head_of_department' => 'Amanda Rodriguez',
                'is_active' => true
            ],
            [
                'name' => 'Marketing',
                'description' => 'Departemen yang menangani pemasaran, promosi, dan hubungan pelanggan',
                'head_of_department' => 'David Kim',
                'is_active' => true
            ],
            [
                'name' => 'Operations',
                'description' => 'Departemen yang menangani operasional harian dan proses bisnis utama',
                'head_of_department' => 'Lisa Thompson',
                'is_active' => true
            ]
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
