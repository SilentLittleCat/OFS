<?php

use Illuminate\Database\Seeder;
use App\Models\UserWeight;

class UserWeightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_weight')->truncate();
        $faker = Faker\Factory::create('zh_CN');

        for($i = 0; $i < 20; ++$i) {
        	UserWeight::create([
        		'user_id' => 11,
        		'weight' => $faker->numberBetween(60, 90),
        		'created_at' => $faker->dateTime(),
        		'updated_at' => $faker->dateTime()
        	]);
        }
    }
}
