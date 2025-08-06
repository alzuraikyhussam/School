<?php

namespace Database\Seeders;

use App\Models\Parent;
use App\Models\User;
use Illuminate\Database\Seeder;

class ParentSeeder extends Seeder
{
    public function run(): void
    {
        $parents = User::where('type', 'Parent')->get();

        foreach ($parents as $index => $parent) {
            Parent::create([
                'user_id' => $parent->id,
                'national_id' => 'NID' . str_pad($index + 1, 6, '0', STR_PAD_LEFT),
                'address' => 'Address ' . ($index + 1) . ', City, Country',
            ]);
        }
    }
}