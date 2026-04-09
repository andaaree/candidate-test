<?php

namespace Database\Seeders;

use App\Models\Layup;
use Illuminate\Database\Seeder;

class LayupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Layup::factory(20)->create();
    }
}
