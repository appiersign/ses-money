<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Merchant::class, 2)->create()->each(function ($merchant){
            $merchant->terminals()->save(factory(\App\Terminal::class)->make());
        });
    }
}
