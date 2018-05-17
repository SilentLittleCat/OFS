<?php

use Illuminate\Database\Seeder;
use App\Models\Area;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('areas')->truncate();

        $all_items = DB::table('test_area_data')->get();

        $tmp_items = clone $all_items;

        $items = $tmp_items->unique('province')->pluck('province');
        foreach($items as $item) {
            Area::create([
                'name' => $item,
                'level' => 1,
                'pid' => 0,
                'full_name' => $item
            ]);
        }
        $provinces = Area::all();
        foreach($provinces as $province) {
            $tmp_items = clone $all_items;
            $items = $tmp_items->where('province', $province->name)->unique('city')->pluck('city');
            foreach($items as $item) {
                Area::create([
                    'name' => $item,
                    'level' => 2,
                    'pid' => $province->id,
                    'full_name' => $province->name . ',' . $item
                ]);
            }
        }
        $cities = Area::where('level', 2)->get();
        foreach($cities as $city) {
            $tmp_items = clone $all_items;
            $items = $tmp_items->where('city', $city->name)->pluck('county');
            foreach($items as $item) {
                Area::create([
                    'name' => $item,
                    'level' => 3,
                    'pid' => $city->id,
                    'full_name' => $city->full_name . ',' . $item
                ]);
            }
        }
    }
}
