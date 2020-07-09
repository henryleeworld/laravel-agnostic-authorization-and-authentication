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
        DB::table('users')->insert([
			[		
                'id'          => '1',
			    'email'       => 'admin@admin.com',
			    'password'    => bcrypt('password'),
			    'permissions' => '{"admin.dashboard":true}',
			    'first_name'  => 'Henry',
			    'last_name'   => 'Lee'	
			]
        ]);
        DB::table('roles')->insert([
			[		
			    'id'          => '1',
			    'slug' 		  => 'admin',
			    'name' 		  => 'Admin',
			    'permissions' => '{"password.request":true,"password.email":true,"password.reset":true,"admin.dashboard":true}',
			],
			[		
			    'id'          => '2',
			    'slug'        => 'client',
			    'name'        => 'client',
			    'permissions' => '{"admin.dashboard":true}',
			]
		]);
		DB::table('role_users')->insert([
			[		
			    'user_id' => '1',
			    'role_id' => '1',
			]
		]);
		DB::table('activations')->insert([
			[		
			    'user_id' 	=> '1',
			    'code' 		=> '1S4u7lJzehk62xDm3DgYgXXYWtbHE6gSP',
			    'completed' => '1',
			]
		]);
    }
}
