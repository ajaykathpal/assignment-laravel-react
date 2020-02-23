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
        $data = [
            [
                'id' => 1,
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'auth_token' => '',
                'dob' => now(),
                'password' => \Hash::make('admin@123'),
                'role_id' => 1,
            ]
        ];

        DB::table('users')->truncate();

        foreach ($data as $curData) {
            DB::table('users')->insert($curData);
        }
    }
}
