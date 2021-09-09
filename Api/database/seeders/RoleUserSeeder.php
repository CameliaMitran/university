<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_id = DB::table('users')->first()->id;
        $role_id = DB::table('roles')->where('name', 'admin')->first()->id; 
        DB::table('role_user')->insert([
            'user_id' => $user_id,
            'role_id' => $role_id,
        ]);

    }
}
