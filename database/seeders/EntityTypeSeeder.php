<?php

namespace Database\Seeders;

use App\Models\EntityType;
use App\Models\SeedType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class EntityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->getEntityTypes()->each(fn($entity_type) => EntityType::updateOrCreate([
            'model' => $entity_type['model']
        ], [
            'name' => $entity_type['name'],
        ]));
    }

    public function getEntityTypes(): Collection
    {
        return collect([
            [
                'name' => 'بذور',
                'model' => SeedType::class
            ]
        ]);
    }

}
