<?php

use Illuminate\Database\Seeder;
use App\Models\SendRange;

class SendRangeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('send_ranges')->truncate();
        SendRange::create([
        	'city' => '成都市',
        	'county' => '成华区'
        ]);
        SendRange::create([
        	'city' => '成都市',
        	'county' => '武侯区'
        ]);
        SendRange::create([
        	'city' => '北京市',
        	'county' => '东城区'
        ]);
    }
}
