<?php

use Illuminate\Database\Seeder;
use App\Models\County;

class CountySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = collect(['成都市', '锦江区', '青羊区', '金牛区', '武侯区', '成华区', '高新区', '天府新区', '龙泉驿区', '新都区', '温江区', '双流区', '简阳市', '都江堰市', '邛崃市', '彭州市', '崇州市', '金堂县', '郫都区', '大邑县', '蒲江县', '新津县']);
        DB::table('counties')->truncate();
        foreach($names as $name) {
        	County::create([
        		'name' => $name
        	]);
        }
    }
}
