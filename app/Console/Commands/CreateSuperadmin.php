<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateSuperadmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-superadmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membuat akun Superadmin baru secara interaktif';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== PEMBUATAN AKUN SUPERADMIN ===');

        // 1. Input Data
        $name = $this->ask('Masukkan Nama Lengkap');
        $username = $this->ask('Masukkan Username');
        $email = $this->ask('Masukkan Alamat Email');
        $password = $this->secret('Masukkan Password');
        $passwordConfirm = $this->secret('Konfirmasi Password');

        // 2. Validasi Input
        $validator = Validator::make([
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $passwordConfirm,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:100', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            $this->error('Gagal membuat Superadmin! Periksa kesalahan berikut:');
            foreach ($validator->errors()->all() as $error) {
                $this->line("- {$error}");
            }
            return self::FAILURE;
        }

        // 3. Buat User Superadmin
        $user = User::create([
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password),
            'is_active' => true,
        ]);

        // 4. Assign Role Laratrust (Global / Non-Team)
        $roleSuperadmin = Role::firstOrCreate(
            ['name' => 'superadmin'],
            ['display_name' => 'Superadmin', 'description' => 'Akses Penuh Seluruh Sistem']
        );

        $user->addRole($roleSuperadmin);

        $this->info('');
        $this->info("✓ Superadmin '{$username}' ({$email}) berhasil dibuat!");
        
        return self::SUCCESS;
    }
}
