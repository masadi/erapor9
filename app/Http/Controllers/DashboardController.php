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
            'ptk' => function ($query) {
                $query->where('is_dapodik', 1)->whereDoesntHave('ptk_keluar', function ($q) {
                    $q->where('semester_id', request()->semester_id);
                });
            },
            'pd_aktif' => function ($query) use ($request) {
                $query->where('semester_id', $this->semesterId())
                      ->whereHas('rombonganBelajar', function ($q) {
                          $q->where('jenis_rombel', 1);
                      });
            },
            'nilai_akhir' => function ($query) use ($request) {
                $query->whereHas('pembelajaran', function ($q) use ($request) {
                    $q->where('sekolah_id', $this->user()->sekolah_id)
                      ->where('semester_id', $this->semesterId());
                });
            },
            'cp' => function ($query) use ($request) {
                $query->whereHas('pembelajaran', function ($q) use ($request) {
                    $q->where('sekolah_id', $this->user()->sekolah_id)
                      ->where('semester_id', $this->semesterId());
                });
            },
            'nilai_projek' => function ($query) use ($request) {
                $query->whereHas('anggota_rombel', function ($q) use ($request) {
                    $q->where('sekolah_id', $this->user()->sekolah_id)
                      ->where('semester_id', $this->semesterId());
                })->whereNotNull('rencana_budaya_kerja_id');
            },
        ])->find($this->user()->sekolah_id);

        $status_penilaian = StatusPenilaian::firstOrCreate([
            'sekolah_id' => $this->user()->sekolah_id,
            'semester_id' => $this->semesterId(),
        ], ['status' => 1]);

        $tp = TujuanPembelajaran::where(function ($q) use ($request) {
            $q->whereHas('cp', function ($qq) use ($request) {
                $qq->whereHas('pembelajaran', function ($qqq) use ($request) {
                    $qqq->where('sekolah_id', $this->user()->sekolah_id)
                        ->where('semester_id', $this->semesterId());
                });
            })->orWhereHas('kd', function ($qq) use ($request) {
                $qq->whereHas('pembelajaran', function ($qqq) use ($request) {
                    $qqq->where('sekolah_id', $this->user()->sekolah_id)
                        ->where('semester_id', $this->semesterId());
                });
            });
        })->count();

        $data = [
            'stats' => [
                'jumlahSekolah'    => $sekolah->ptk_count,
                'jumlahGuruAktif'  => $sekolah->ptk_count,
                'jumlahKelas'      => $sekolah->rombongan_belajar_count ?? 0,
                'jumlahSiswaAktif' => $sekolah->pd_aktif_count,
            ],
            'dataLembaga' => [],
            'rekap' => [
                [
                    'data'    => 'PTK',
                    'jml'     => $sekolah->ptk_count,
                    'icon'    => 'user-graduate',
                    'variant' => 'info',
                    'html'    => '',
                ],
                [
                    'data'    => 'Peserta Didik',
                    'jml'     => $sekolah->pd_aktif_count,
                    'icon'    => 'children',
                    'variant' => 'warning',
                    'html'    => '',
                ],
                [
                    'data'    => 'CP',
                    'jml'     => $sekolah->cp_count,
                    'icon'    => 'list-check',
                    'variant' => 'success',
                    'html'    => 'Jumlah Mata Pelajaran yang telah di input Deskripsi Capaian Pembelajaran',
                ],
                [
                    'data'    => 'TP',
                    'jml'     => $tp,
                    'icon'    => 'spell-check',
                    'variant' => 'error',
                    'html'    => 'Jumlah Tujuan Pembelajaran yang telah di input oleh PTK',
                ],
                [
                    'data'    => 'Nilai Akhir',
                    'jml'     => $sekolah->nilai_akhir_count,
                    'icon'    => 'list-check',
                    'variant' => 'primary',
                    'html'    => 'Jumlah Nilai Akhir yang siap dicetak',
                ],
                [
                    'data'    => 'Nilai Projek',
                    'jml'     => $sekolah->nilai_projek_count,
                    'icon'    => 'list-check',
                    'variant' => 'error',
                    'html'    => 'Jumlah Peserta Didik yang telah dinilai P5',
                ],
            ],
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

