<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // ['name' => 'Fashion', 'created_at' => NOW(), 'updated_at' => NOW()],
            // ['name' => 'Instrument', 'created_at' => NOW(), 'updated_at' => NOW()],
            // ['name' => 'Sport', 'created_at' => NOW(), 'updated_at' => NOW()]
            ['name' => 'Car', 'created_at' => NOW(), 'updated_at' => NOW()],
            ['name' => 'Phone', 'created_at' => NOW(), 'updated_at' => NOW()],
            ['name' => 'Tablet', 'created_at' => NOW(), 'updated_at' => NOW()]
        ];

        $this->category->insert($categories);
    }
}