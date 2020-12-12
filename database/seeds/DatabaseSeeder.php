<?php

use Illuminate\Database\Seeder;
use Faker\Factory;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         //$this->call(AdminSeeder::class);
         $faker = Factory::create();

         foreach(range(1,10) as $i){
           $data = [
           "form_id" => 'SA'.rand(),
           "name" =>  $faker->name,
           "father_name" => $faker->name,
           "cnic" => rand(),
           "cell" => rand(),
           "occupation" => $faker->randomElement(['businessman','programmer','doctor']),
           "soi" => $faker->randomElement(['salary','commision','pension']),
           "address" => 'test',
           "payment_details" => 'null',
           "funds" => 'null',
           "status" => "pending",
           "land_from" => "saleapp",
           "user_id" => 63,
           "created_at" => now(),
           "updated_at" => now(),
   ];

          DB::table('forms')->insert($data);
         }

    }
}
