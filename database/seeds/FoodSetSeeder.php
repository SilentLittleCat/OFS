<?php

use Illuminate\Database\Seeder;
use App\Models\FoodSet;
use App\Models\BaseAttachmentModel;

class FoodSetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('food_sets')->truncate();
    	$man_food_img = BaseAttachmentModel::where('class', '男士餐图')->first();
    	$woman_food_img = BaseAttachmentModel::where('class', '女士餐图')->first();
    	$work_food_img = BaseAttachmentModel::where('class', '工作餐图')->first();

    	//男士周餐
        FoodSet::create([
        	'type' => 1,
        	'kind' => 0,
        	'money' => 250,
        	'status' => 1,
        	'poster' => $man_food_img->url
        ]);

        //男士月餐
        FoodSet::create([
        	'type' => 1,
        	'kind' => 1,
        	'money' => 1000,
        	'status' => 1,
        	'poster' => $man_food_img->url
        ]);

        //女士周餐
        FoodSet::create([
        	'type' => 2,
        	'kind' => 0,
        	'money' => 240,
        	'status' => 1,
        	'poster' => $woman_food_img->url
        ]);

        //女士月餐
        FoodSet::create([
        	'type' => 2,
        	'kind' => 1,
        	'money' => 900,
        	'status' => 1,
        	'poster' => $woman_food_img->url
        ]);

        //工作餐周餐
        FoodSet::create([
        	'type' => 3,
        	'kind' => 0,
        	'money' => 250,
        	'status' => 1,
        	'poster' => $work_food_img->url
        ]);

        //工作餐月餐
        FoodSet::create([
        	'type' => 3,
        	'kind' => 1,
        	'money' => 1000,
        	'status' => 1,
        	'poster' => $work_food_img->url
        ]);
    }
}
