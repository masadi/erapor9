<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sekolah;
use App\Models\Ptk;
use App\Models\PesertaDidik;
use App\Models\RombonganBelajar;
use App\Models\StatusPenilaian;
use App\Models\TujuanPembelajaran;
use App\Models\Pembelajaran;
use App\Models\AnggotaRombel;
use App\Models\DeskripsiMataPelajaran;
use App\Models\Absensi;
use App\Models\NilaiAkhir;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman ringkasan Dashboard.
     */
    public function index(Request $request)
    {
        // Menggunakan logika yang sama dengan DashboardController di proyek erapor8
        $data = $this->dashboard_admin($request);
        return Inertia::render('Dashboard', $data);
    }

    /**
     * Logika yang disalin dari erapor8\app\Http\Controllers\DashboardController::dashboard_admin
     */
    private function dashboard_admin(Request $request)
    {
        $sekolah = Sekolah::with([
            'kepala_sekolah' => function ($query) use ($request) {
                $query->where('semester_id', $this->semesterId());
            },
        ])->withCount([
            'ptk as guru' => function ($query) {
                $query->where('is_dapodik', 1);
                $query->whereDoesntHave('ptk_keluar', function ($q) {
                    $q->where('semester_id', $this->semesterId());
                });
                $query->whereIn('jenis_ptk_id', jenis_gtk('guru'));
            },
            'ptk as tendik' => function ($query) {
                $query->where('is_dapodik', 1);
                $query->whereDoesntHave('ptk_keluar', function ($q) {
                    $q->where('semester_id', $this->semesterId());
                });
                $query->whereIn('jenis_ptk_id', jenis_gtk('tendik'));
            },
            'pd_aktif' => function ($query) use ($request) {
                $query->where('semester_id', $this->semesterId())
                      ->whereHas('rombonganBelajar', function ($q) {
                          $q->where('jenis_rombel', 1);
                      });
            },
            'rombonganBelajar' => function ($query) use ($request) {
                $query->where('semester_id', $this->semesterId());
            },
        ])->find($this->user()->sekolah_id);

        $status_penilaian = StatusPenilaian::firstOrCreate([
            'sekolah_id' => $this->user()->sekolah_id,
            'semester_id' => $this->semesterId(),
        ], ['status' => 1]);

        $data = [
            'stats' => [
                'guru' => $sekolah->guru,
                'tendik' => $sekolah->tendik,
                'pd'      => $sekolah->pd_aktif_count,
                'rombel' => $sekolah->rombongan_belajar_count,
            ],
            'dataLembaga' => [],
            'app' => [
                'app_name'        => config('app.name'),
                'app_version'     => get_setting('app_version'),
                'db_version'      => get_setting('db_version'),
                'status_penilaian'=> $status_penilaian->status ? true : false,
            ],
        ];

        return $data;
    }
}

