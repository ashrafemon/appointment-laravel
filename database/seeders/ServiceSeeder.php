<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Service::truncate();

        Service::create([
            'category_id' => 1,
            'name' => 'Monthly',
            'slug' => 'monthly',
            'cost' => 55,
            'time' => 60,
            'url' => 'service/monthly',
        ]);

        Service::create([
            'category_id' => 1,
            'name' => 'Evening refreshment',
            'slug' => 'evening_refreshment',
            'cost' => 25,
            'time' => 45,
            'url' => 'service/evening-refreshment',
        ]);
    }
}
