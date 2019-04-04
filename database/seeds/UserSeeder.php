<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = \Carbon\Carbon::now();
        if(DB::table('users')->get()->count() == 0){
            DB::table('users')->insert([
                'name' => 'Bhavin Nakrani',
                'email' => 'bhavin.it8488@gmail.com',
                'password' => bcrypt('admin@123'),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

    }
}
