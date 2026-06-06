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
            [
                'icon' => 'circle-stack',
                'active' => true,
                'fields' => [
                    ['key' => 'brand',       'label' => 'Brand',       'type' => 'text'],
                    ['key' => 'model',       'label' => 'Model',       'type' => 'text'],
                    ['key' => 'capacity',    'label' => 'Capacity',    'type' => 'text'],
                    ['key' => 'interface',   'label' => 'Interface',   'type' => 'select', 'options' => ['NVMe', 'SATA', 'SAS', 'USB', 'IDE']],
                    ['key' => 'form_factor', 'label' => 'Form Factor', 'type' => 'select', 'options' => ['M.2', '2.5"', '3.5"', 'mSATA']],
                    ['key' => 'rpm',         'label' => 'RPM',         'type' => 'integer'],
                    ['key' => 'health',      'label' => 'Health (%)',  'type' => 'integer'],
                    ['key' => 'format',      'label' => 'Format',      'type' => 'text'],
                    ['key' => 'cycles',      'label' => 'Write Cycles','type' => 'integer'],
                ],
            ]
        );
    }
}
