<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Laratrust\Models\Team;
use App\Models\Menu;
use App\Models\Semester;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();
        $semester = Semester::where('periode_aktif', 1)->first();
        // 1. Ambil tahun_ajaran_id aktif (misal: 20261)
        $activeSemesterid = session('active_semester_id', fn () => $semester->semester_id);
        // 2. Cari Objek Team Laratrust berdasarkan kolom 'name'
        $activeTeam = $activeSemesterid 
            ? Team::where('name', $semester->nama)->first() 
            : null;
        // 3. Simpan team_id ke Session jika $activeTeam ditemukan
        if ($activeTeam) {
            session(['active_team_id' => $activeTeam->id]);
        } else {
            session()->forget('active_team_id');
        }
        // 4. Ambil Role berdasarkan Objek Team
        $currentRole = ['User'];
        if ($user && $activeTeam) {
            // Query langsung relasi Eloquent roles khusus untuk team_id aktif
            $currentRole = $user->roles()->wherePivot('team_id', $activeTeam->id)->get();
        }
        $rawNavigations = Menu::whereNull('parent_id')->with('childrenRecursive')->orderBy('order', 'asc')->get();
        return array_merge(parent::share($request), [
            'csrf_token' => csrf_token(),
            'activeSemesterid' => $activeSemesterid,
            'periodeAktif' => $semester?->nama, 
            'activeTeam' => $activeTeam?->id,
            'auth' => [
                'user' => $user ? [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'photo' => $user->photo,
                    'role'  => $currentRole, // Role dinamis berdasarkan semester aktif
                ] : null,
            ],
            'sekolah' => $user?->sekolah,
            // Filter Navigasi Berdasarkan Laratrust Permission/Role & Semester
            'navigations' => fn () => $user ? $this->formatMenuTree($rawNavigations, $user, $activeTeam) : [],

            // DAFTARKAN FLASH MESSAGE
            'flash' => [
                'plainTextToken' => fn () => $request->session()->get('plainTextToken'),
                'success'        => fn () => $request->session()->get('success'),
                'error'          => fn () => $request->session()->get('error'),
            ],

            // Bagikan APP_NAME dari .env
            'appName' => config('app.name', 'Siberes'),
            'appLogo' => '/images/logo/logo.png',
            'loginBanner' => [
                'title'       => config('app.login_title', env('APP_LOGIN_TITLE', 'Kelola Anggaran & Laporan Lebih Efisien.')),
                'description' => config('app.login_description', env('APP_LOGIN_DESCRIPTION', 'Akses platform untuk pemantauan data dan pelaporan otomatis secara real-time.')),
            ],
        ]);
    }

    /**
     * Format menu secara rekursif hingga ke tingkat terkecil (Multi-level)
     */
    private function formatMenuTree($menus, $user, $team)
    {
        return $menus->map(function ($menu) use ($user, $team){
            // Cek permission user (misal pakai Laratrust / Spatie / Gate)
            $hasPermission = empty($menu->permission) || $this->userHasAccessInSemester($menu, $user, $team);

            // Ambil children rekursif
            $children = $this->formatMenuTree($menu->childrenRecursive, $user, $team);

            // Jika menu ini tidak punya permission DAN tidak punya children yang lolos filter, lewatkan
            if (!$hasPermission && $children->isEmpty()) {
                return null;
            }

            // Tentukan URL / Route
            $href = '#';
            if ($menu->route_or_url) {
                $href = Route::has($menu->route_or_url) 
                    ? route($menu->route_or_url) 
                    : $menu->route_or_url;
            }

            return [
                'id'       => $menu->id,
                'name'     => $menu->name,
                'icon'     => $menu->icon,
                'href'     => $href,
                'children' => $children->values()->toArray(),
            ];
        })->filter()->values();
    }

    /**
     * Helper untuk mengecek izin akses Laratrust
     */
    private function userHasAccessInSemester($user, $menu, $team): bool
    {
        // Jika menu tidak butuh permission khusus, izinkan
        if (empty($menu->permission)) {
            return true;
        }

        // Jika user adalah superadmin (cek superadmin di team spesifik ATAU superadmin global)
        if ($user->hasRole('superadmin', $team) || $user->hasRole('superadmin')) {
            return true;
        }

        // Cek Permission Laratrust dengan melempar OBJEK TEAM (bukan ID angka)
        return $user->isAbleTo($menu->permission, $team);
    }
}