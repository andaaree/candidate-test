<?php

namespace Database\Seeders;

use App\Models\Layer;
use Illuminate\Database\Seeder;

class LayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
     {
        Layer::factory(60)->create();
    }
}
