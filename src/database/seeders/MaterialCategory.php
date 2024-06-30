<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialCategory extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Atasan',
            'Bawahan',
            'Print',
            'Jahit',
            'Lobang Kancing',
            'Kerah',
            'Tambahan',
            'Polyflex/DTF',
            'Bordir',
        ];

        foreach ($categories as $category) {
            DB::table('material_categories')->insert([
                'name' => $category,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
