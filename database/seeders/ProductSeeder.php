<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 
use App\Models\Product; 

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Product::query()->delete();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Product::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Product::create([
            'name'        => 'Keyboard Mechanical 60%',
            'description' => 'Keyboard mechanical compact untuk gaming dan kerja.',
            'price'       => 550000,
            'stock'       => 20,
            'sku'         => 'KB-60-MECH',
        ]);

        Product::create([
            'name'        => 'Mouse Wireless Silent',
            'description' => 'Mouse wireless dengan klik senyap dan baterai awet.',
            'price'       => 175000,
            'stock'       => 50,
            'sku'         => 'MS-WL-SILENT',
        ]);

        Product::create([
            'name'        => 'Monitor 24 inch IPS',
            'description' => 'Monitor 24 inch Full HD panel IPS.',
            'price'       => 1500000,
            'stock'       => 10,
            'sku'         => 'MN-24-IPS',
        ]);
    }
}
