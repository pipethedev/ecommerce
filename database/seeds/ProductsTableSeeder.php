<?php

use Illuminate\Database\Seeder;
use App\Product;
use App\Category;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for($i = 1; $i <= 8; $i++ ){
        Product::create([
            'name' => 'Laptops' .$i,
            'slug' => 'laptop-'.$i,
            'details' => [13, 14, 15][array_rand([13, 14, 15])] .'inch,'.[1, 2,3][array_rand([1, 2, 3])] .' TB SSD , 32GB RAM',
            'price' => rand(1000, 6000),
            'description'=> 'Laptop'.$i.' is an innovative oil filled radiator with the most modern technology. If you are looking for something that can make your interior look awesome, and at the same time give you the pleasant warm feeling during the winter.',
        ])->categories()->attach(1);
        }

        $product = Product::find(1);
        $product->categories()->attach(2);
        


        for($i = 1; $i <= 8; $i++){
        Product::create([
            'name' => "Desktops" .$i,
            'slug' => 'desktop-'.$i,
            'details' => [13, 14, 15][array_rand([13, 14, 15])] .'inch,'.[1, 2,3][array_rand([1, 2, 3])] .' TB SSD , 32GB RAM',
            'price' => rand(1000, 6000),
            'description'=> 'Desktop'.$i.' is an innovative oil filled radiator with the most modern technology. If you are looking for something that can make your interior look awesome, and at the same time give you the pleasant warm feeling during the winter.',
        ])->categories()->attach(2);
        }

        for($i = 1; $i <= 8; $i++){
        Product::create([
            'name' => "Clothes" .$i,
            'slug' => 'clothe-'.$i,
            'details' => [13, 14, 15][array_rand([13, 14, 15])] .'large,'.[1, 2,3][array_rand([1, 2, 3])] .' red color , made with wool',
            'price' => rand(1000, 6000),
            'description'=> 'Clothes'.$i.' bring a comfy and statisfied wear whih makes you look comfortable',
        ])->categories()->attach(3);
        }

        for($i = 1; $i <= 8; $i++){
        Product::create([
            'name' => "Wristwatch" .$i,
            'slug' => 'watch-'.$i,
            'details' => [13, 14, 15][array_rand([13, 14, 15])] .'black,'.[1, 2,3][array_rand([1, 2, 3])] .' ultimate g-shock',
            'price' => rand(1000, 6000),
            'description'=> 'watch'.$i.' is an innovative oil filled radiator with the most modern technology. If you are looking for something that can make your interior look awblackesome, and at the same time give you the pleasant warm feeling during the winter.',
        ])->categories()->attach(4);
    }
        for($i = 1; $i <= 8; $i++){
        Product::create([
            'name' => 'Mobile Phones' .$i,
            'slug' => 'smartphone-'.$i,
            'details' => [13, 14, 15][array_rand([13, 14, 15])] .'black,'.[1, 2,3][array_rand([1, 2, 3])] .' iphone ',
            'price' => rand(1000, 6000),
            'description'=> 'This phone '.$i.' is an innovative oil filled radiator with the most modern technology. If you are looking for something that can make your interior look awblackesome, and at the same time give you the pleasant warm feeling during the winter.',
        ])->categories()->attach(5);
    }

    }
}
