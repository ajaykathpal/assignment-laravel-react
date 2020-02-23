<?php

use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
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
                'page' => 'profile',
                'action' => 'show',
            ],
            [
                'id' => 2,
                'page' => 'user',
                'action' => 'show',
            ],
            [
                'id' => 3,
                'page' => 'user',
                'action' => 'add',
            ],
            [   'id' => 4,
                'page' => 'user',
                'action' => 'edit',
            ],
            [   'id' => 5,
                'page' => 'user',
                'action' => 'delete',
            ],
            [   'id' => 6,
                'page' => 'role',
                'action' => 'show',
            ],
            [   'id' => 7,
                'page' => 'role',
                'action' => 'add',
            ],
            [   'id' => 8,
                'page' => 'role',
                'action' => 'edit',
            ],
            [   'id' => 9,
                'page' => 'role',
                'action' => 'delete',
            ]
        ];

        DB::table('permissions')->truncate();

        foreach ($data as $curData) {
            DB::table('permissions')->insert($curData);
        }
    }
}
