<?php
use App\Models\Semester;
use App\Models\TahunAjaran;
use App\Models\Setting;

if (! function_exists('active_tahun_ajaran_id')) {
    /**
     * Ambil ID Tahun Ajaran yang sedang aktif (periode_aktif = 1)
     *
     * @return int|string|null
     */
    function active_tahun_ajaran_id()
    {
        return TahunAjaran::where('periode_aktif', 1)->value('tahun_ajaran_id');
    }
}

if (! function_exists('active_tahun_ajaran')) {
    /**
     * Ambil Model Instance Tahun Ajaran yang sedang aktif
     *
     * @return \App\Models\TahunAjaran|null
     */
    function active_tahun_ajaran()
    {
        return TahunAjaran::where('periode_aktif', 1)->first();
    }
}
if (! function_exists('send_wa_message')) {
    /**
     * Helper Global Kirim Pesan WhatsApp
     *
     * @param string $targetNomor (Contoh: 628123456789)
     * @param string $pesan
     * @return bool
     */
    function send_wa_message($targetNomor, $pesan)
    {
        try {
            Whatsapp::send($targetNomor, $pesan);
            return true;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('WA Gateway Error: ' . $e->getMessage());
            return false;
        }
    }
}
function get_setting($key, $sekolah_id = NULL, $semester_id = NULL){
    $data = Setting::where(function($query) use ($key, $sekolah_id, $semester_id){
        $query->where('key', $key);
        if($sekolah_id){
            $query->where('sekolah_id', $sekolah_id);
        }
        if($semester_id){
            $query->where('semester_id', $semester_id);
        }
    })->first();
    return ($data) ? $data->value : NULL;
}
function semester_id(){
    return Semester::where('periode_aktif', 1)->first()?->semester_id;
}
