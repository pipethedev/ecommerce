<?php

use App\Category;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now()->toDateTimeString();
        Category::insert([
            ['name' => 'Laptops', 'slug' => 'laptop', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Desktops', 'slug' => 'desktop', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Clothes', 'slug' => 'clothe', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Wristwatch', 'slug' => 'watch', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Mobile Phones', 'slug' => 'smartphone', 'created_at' => $now, 'updated_at' => $now]
        ]);
    }
}
