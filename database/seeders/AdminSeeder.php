<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Isi ADMIN_EMAIL dan ADMIN_PASSWORD di file .env sebelum menjalankan seeder.
        $email = env('ADMIN_EMAIL', 'admin@disporabudpar.test');
        $password = env('ADMIN_PASSWORD');

        if (! $password) {
            $this->command?->warn('ADMIN_PASSWORD belum diisi di .env, akun admin tidak dibuat.');

            return;
        }

        DB::table('users')->updateOrInsert(
            ['email' => $email],
            [
                'name' => env('ADMIN_NAME', 'Administrator'),
                'password' => Hash::make($password),
                'role' => 'admin',
                'aktif' => true,
                'email_verified_at' => now(),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        $this->command?->info("Akun admin siap: {$email}");
    }
}
