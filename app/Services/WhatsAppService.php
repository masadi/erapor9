<?php

namespace App\Services;

use App\Models\Whatsapp;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Kirim pesan WhatsApp ke nomor individual atau ID Group WA
     */
    public static function sendMessage(string $target, string $message): bool
    {
        $setting = Whatsapp::first();

        if (!$setting || $setting->status !== 'connected' || !$setting->gateway_url) {
            Log::warning("WhatsApp Gateway belum dikonfigurasi atau tidak aktif.");
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $setting->api_key,
            ])->post($setting->gateway_url, [
                'target'  => $target, // Bisa nomor HP atau Group ID WA
                'message' => $message,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error("Gagal mengirim WA ke {$target}: " . $e->getMessage());
            return false;
        }
    }
}