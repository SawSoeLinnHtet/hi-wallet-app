<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = AdminUser::create([
            'name' => 'Saw Soe Linn Htet',
            'email' => 'admin@gmail.com',
            'phone' => '09962569030',
            'password' => bcrypt('123123123'),
        ]);
    }
}
