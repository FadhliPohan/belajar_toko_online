<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $inputan['name'] = 'fadli';
        $inputan['email'] = 'fadly@mail.com';//ganti pake emailmu
        $inputan['password'] = Hash::make('123258');//passwordnya 123258
        $inputan['phone'] = '085852527575';
        $inputan['alamat'] = 'Bulung Kulon Rt 03 Rw 05';
        $inputan['role'] = 'admin';//kita akan membuat akun atau users in dengan role admin
        User::create($inputan);
    }
}
