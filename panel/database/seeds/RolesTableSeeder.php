<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
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
                'role' => 'admin',
            ]
        ];

        DB::table('roles')->truncate();

        foreach ($data as $curData) {
            DB::table('roles')->insert($curData);
        }
    }
}
