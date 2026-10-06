<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@fund.local'],
            ['name' => 'Admin', 'password' => Hash::make('admin123')]
        );

        foreach (['Material', 'Labour', 'Transport', 'Food', 'Misc'] as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
