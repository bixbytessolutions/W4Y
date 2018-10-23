<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		
        for($i=1;$i<10;$i++){
            $name = "testme".$i;
            DB::table('users_w4y')->insert([
                'first_name' => $name,
                'email' => $name.'@me.com',
                'photo' => 'user.jpg',
                'password' => bcrypt('123456'),
            ]);
        }
    }
}
