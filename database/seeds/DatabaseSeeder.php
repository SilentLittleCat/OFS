<?php

use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;
use Carbon\Carbon;
use App\Models\Food;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_users')->truncate();
        DB::table('classes')->truncate();
        DB::table('admin_menus')->truncate();
        DB::table('base_area')->truncate();
        DB::table('base_dictionary_option')->truncate();
        DB::table('base_settings')->truncate();
        
        $fileSystem = new Filesystem();
        $database = $fileSystem->get(base_path('database/seeds') . '/' . 'init.sql');
        DB::connection()->getPdo()->exec($database);
        DB::table('admin_users')->insert([
            'name' => 'user1',
            'real_name' => 'user1',
            'password' => bcrypt('123456'),
            'email' => 'user1@163.com',
            'mobile' => '123456',
            'avatar' => 'http://webimg-handle.liweijia.com/upload/avatar/avatar_0.jpg',
            'type' => 0,
            'is_root' => 1
        ]);

        DB::table('classes')->insert([
            'class' => '未分类',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        // $this->call(NeedSeeder::class);
        $this->call(TestSeeder::class);
        $this->call(UserWeightSeeder::class);
        $this->call(SendRangeSeeder::class);
        // $this->call(CountySeeder::class);
        $this->call(FoodSetSeeder::class);
    }
}
