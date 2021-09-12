<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hrd = User::create([
            'name' => 'HRD Staff',
            'username' => 'hrd',
            'email' => 'hrd@gmail.com',
            'password' => bcrypt('admin123')
        ]);
        $hrd->assignRole('hrd');

        $karyawan = User::create([
            'name' => 'Fikri Haidar',
            'username' => 'fikri',
            'email' => 'fikri@gmail.com',
            'password' => bcrypt('admin123')
        ]);
        $karyawan->assignRole('karyawan');

        $karyawan1 = User::create([
            'name' => 'Jhon Due',
            'username' => 'jhon',
            'email' => 'jhon@gmail.com',
            'password' => bcrypt('admin123')
        ]);
        $karyawan1->assignRole('karyawan');
    }
}
