<?php

namespace Database\Seeders;

use App\Models\ItemType;
use Illuminate\Database\Seeder;

class ItemTypeSeeder extends Seeder
{
    public function run(): void
    {
        ItemType::firstOrCreate(
            ['name' => 'Disk'],
            ['icon' => 'circle-stack', 'active' => true, 'fields' => []]
        );

        ItemType::firstOrCreate(
            ['name' => 'Computer'],
            ['icon' => 'computer-desktop', 'active' => true, 'fields' => []]
        );

        ItemType::firstOrCreate(
            ['name' => 'RAM'],
            ['icon' => 'cpu-chip', 'active' => true, 'fields' => []]
        );

        ItemType::firstOrCreate(
            ['name' => 'GPU'],
            ['icon' => 'rectangle-group', 'active' => true, 'fields' => []]
        );

        ItemType::firstOrCreate(
            ['name' => 'CPU'],
            ['icon' => 'cpu-chip', 'active' => true, 'fields' => []]
        );
    }
}
