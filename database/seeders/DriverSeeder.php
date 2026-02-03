<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DriverSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('drivers')->insert([
            [
                'name' => 'Budi Santoso',
                'phone_number' => '081234567890',
                'email' => 'budi.santoso@example.com',
                'status' => 'active',
                'notes' => 'Sopir utama kendaraan dinas',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Agus Pratama',
                'phone_number' => '081298765432',
                'email' => 'agus.pratama@example.com',
                'status' => 'active',
                'notes' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Dedi Kurniawan',
                'phone_number' => '082112223333',
                'email' => null,
                'status' => 'active',
                'notes' => 'Berpengalaman perjalanan luar kota',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Hendra Saputra',
                'phone_number' => '085678901234',
                'email' => null,
                'status' => 'inactive',
                'notes' => 'Sedang cuti',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Rizki Maulana',
                'phone_number' => '081355566677',
                'email' => 'rizki.maulana@example.com',
                'status' => 'active',
                'notes' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
