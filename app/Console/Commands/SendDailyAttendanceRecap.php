<?php

namespace App\Console\Commands;

use App\Models\RombonganBelajar;
use App\Models\HariLibur;
use App\Models\Presensi;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendDailyAttendanceRecap extends Command
{
    protected $signature = 'attendance:send-daily-recap';
    protected $description = 'Kirim rekap harian absensi ke WA Group Wali Kelas tiap jam 13.01';

    public function handle()
    {
        $today = Carbon::today();

        // 1. Lewati pengiriman jika hari libur (Hari Jumat atau libur nasional)
        if ($today->dayOfWeek === Carbon::FRIDAY || $this->isHoliday($today)) {
            $this->info("Hari ini hari libur. Pengiriman rekap WA dilewati.");
            return;
        }

        // 2. Ambil semua kelas yang memiliki WA Group ID
        $classes = RombonganBelajar::whereNotNull('wa_group_id')->get();

        foreach ($classes as $class) {
            $totalStudents = $class->anggota_rombel()->count();

            // Hitung siswa yang masuk (hadir atau terlambat)
            $scannedInCount = Presensi::whereIn('peserta_didik_id', $class->anggota_rombel()->pluck('peserta_didik_id'))
                ->where('date', $today->format('Y-m-d'))
                ->whereNotNull('check_in')
                ->count();

            $absentCount = $totalStudents - $scannedInCount;

            // Format Pesan Rekap Harian
            $message = "📊 *REKAP HARIAN ABSENSI SISWA*\n" .
                "Tanggal: " . $today->translatedFormat('d F Y') . "\n" .
                "Kelas: *{$class->nama}*\n\n" .
                "👥 Total Siswa: {$totalStudents}\n" .
                "✅ Siswa Masuk: {$scannedInCount}\n" .
                "❌ Siswa Tidak Masuk / Tanpa Keterangan: {$absentCount}\n\n" .
                "_Pesan otomatis dikirim oleh Sistem Presensi Sekolah._";

            // Kirim ke Group WA Kelas
            WhatsAppService::sendMessage($class->wa_group_id, $message);
        }

        $this->info("Rekap harian berhasil dikirim ke grup WhatsApp.");
    }

    private function isHoliday(Carbon $date): bool
    {
        return Holiday::where('start_date', '<=', $date->format('Y-m-d'))
            ->where('end_date', '>=', $date->format('Y-m-d'))
            ->exists();
    }
}