<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin_details = new App\User;
		$admin_details->name = 'super admin';
		$admin_details->email = 'super@admin.com';
		$admin_details->password = bcrypt('superadmin');
		$admin_details->role_id = 0;
		$admin_details->save();
    }
}
