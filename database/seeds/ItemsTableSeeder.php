<?php

use Illuminate\Database\Seeder;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('items')->insert([
            'name' => str_random(10),
            'description' => str_random(10),
            'price' => rand ( 100 , 9999 ),
            'image' => str_random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
