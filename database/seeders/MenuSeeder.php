<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Menu::truncate();
        // 1. Inisialisasi Master Permissions Laratrust Native
        $permissions = [
            'sinkronisasi-read'       => 'Akses Sinkronisasi',
            'setting-read' => 'Pengaturan Aplikasi',
            'ptk-read'         => 'Lihat Data Guru',
            'rombongan-belajar-read'      => 'Lihat Data Rombongan Belajar',
            'peserta-didik-read'         => 'Lihat Data Peserta Didik',
            'kartu-siswa-read' => 'Pengaturan Kartu Siswa',
            'hari-libur-read'          => 'Kelola Hari Libur',
            'whatsapp-read'      => 'Kelola Whastapp',
            'rekap-presensi-read'    => 'Lihat Rekap Presensi',
            'profile-read'       => 'Lihat Profil',
            'riwayat-presensi-read'       => 'Lihat Riwayat Presensi',
        ];

        foreach ($permissions as $name => $displayName) {
            Permission::firstOrCreate(
                ['name' => $name],
                ['display_name' => $displayName, 'description' => $displayName]
            );
        }
        $rawMenus = [
            // Admin & Operator
            ['title' => 'Dashboard', 'icon' => 'LayoutDashboard', 'route' => 'dashboard', 'resource' => NULL],
            ['title' => 'Sinkronisasi', 'icon' => 'refresh', 'route' => NULL, 'resource' => NULL, 'children' => [
                ['title' => 'Tarik Dapodik', 'icon' => 'download', 'route' => 'sinkronisasi.index', 'resource' => 'sinkronisasi-read'],
                ['title' => 'Kirim Nilai ke Dapodik', 'icon' => 'database', 'route' => 'sinkronisasi.nilai', 'resource' => 'sinkronisasi-read'],
                ['title' => 'Kirim Data e-Rapor', 'icon' => 'upload', 'route' => 'sinkronisasi.erapor', 'resource' => 'sinkronisasi-read'],
            ]],
            ['title' => 'Pengaturan', 'icon' => 'Settings', 'route' => NULL, 'resource' => NULL, 'children' => [
                ['title' => 'Aplikasi', 'icon' => 'Flame', 'route' => 'settings.index', 'resource' => 'setting-read'],
                ['title' => 'Akses Pengguna', 'icon' => 'users', 'route' => 'settings.users', 'resource' => 'setting-read'],
                ['title' => 'Backup/Restore Database', 'icon' => 'database', 'route' => 'settings.database', 'resource' => 'setting-read'],
            ]],
            /*['title' => 'Data Sekolah', 'icon' => 'Building', 'route' => 'sekolah.index', 'resource' => 'sekolah-read'],
            ['title' => 'Data Guru', 'icon' => 'Users', 'route' => 'ptk.index', 'resource' => 'ptk-read'],
            ['title' => 'Data Kelas', 'icon' => 'DoorEnter', 'route' => 'rombongan-belajar.index', 'resource' => 'rombongan-belajar-read'],
            ['title' => 'Data Siswa', 'icon' => 'School', 'route' => 'peserta-didik.index', 'resource' => 'peserta-didik-read'],
            
            // Wali Kelas
            ['title' => 'Rekap Absensi', 'icon' => 'FileText', 'route' => 'wali-kelas.rekap-presensi', 'resource' => 'rekap-presensi'],
            //['id' => 9, 'title' => 'Data Siswa', 'icon' => 'UserCheck', 'route' => 'wali-kelas.index', 'resource' => 'peserta-didik-read', 'order' => 9],
            
            // Siswa / Wali Murid
            ['title' => 'Profil Saya', 'icon' => 'User', 'route' => 'profile.edit', 'resource' => 'profile-read'],
            ['title' => 'Riwayat Absensi', 'icon' => 'Clock', 'route' => 'peserta-didik.presensi.index', 'resource' => 'riwayat-presensi-read'],
            
            // Fitur Scanner
            ['title' => 'Scan QR Absensi', 'icon' => 'Scan', 'route' => 'scan.index', 'resource' => NULL],*/
            ['title' => 'Profil Saya', 'icon' => 'User', 'route' => 'profile.edit', 'resource' => NULL],
            ['title' => 'Pusat Unduhan', 'icon' => 'cloud-download', 'route' => 'pusat-unduhan', 'resource' => NULL],
            ['title' => 'Daftar Perubahan', 'icon' => 'git-branch', 'route' => 'settings.changelog', 'resource' => 'setting-read'],
            ['title' => 'Cek Pembaharuan', 'icon' => 'terminal-2', 'route' => 'settings.check-update', 'resource' => 'setting-read'],
        ];

        // Jalankan fungsi rekursif pembuat menu
        $this->createMenuTree($rawMenus);
    }

    /**
     * Helper rekursif untuk menyimpan menu & child ke database.
     */
    private function createMenuTree(array $items, ?int $parentId = null): void
    {
        foreach ($items as $index => $item) {
            // Konversi resource NULL ke null (bebas akses untuk semua user login)
            $permission = isset($item['resource']) && $item['resource'] !== NULL 
                ? $item['resource'] 
                : null;

            $menu = Menu::create([
                'parent_id'    => $parentId,
                'name'         => trim($item['title']),
                'route_or_url' => $item['route'] ?? null,
                'icon'         => $item['icon'] ?? 'Folder',
                'permission'   => $permission,
                'order'        => $index + 1,
            ]);

            // Jika item mempunya sub-menu (children), panggil kembali fungsi ini secara rekursif
            if (!empty($item['children'])) {
                $this->createMenuTree($item['children'], $menu->id);
            }
        }
    }
}