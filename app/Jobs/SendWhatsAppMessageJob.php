<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Kstmostofa\LaravelWhatsApp\Facades\WhatsApp;

class SendWhatsAppMessageJob implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $backoff = 10;

    protected string $target;
    protected string $pesan;

    public function __construct(string $target, string $pesan)
    {
        $this->target = $target;
        $this->pesan = $pesan;
    }

    public function handle(): void
    {
        try {
            $target = trim($this->target);

            // Cek apakah target sudah berformat ID Grup/User bawaan WhatsApp
            if (str_contains($target, '@g.us') || str_contains($target, '@c.us')) {
                $recipient = $target;
            } elseif (str_contains($target, '-')) { 
                // Format lama WhatsApp Group ID (misal: 120363023948392849)
                $recipient = $target . '@g.us';
            } else {
                // Format Nomor Handphone Pribadi
                $formattedNumber = preg_replace('/[^0-9]/', '', $target);
                if (str_starts_with($formattedNumber, '0')) {
                    $formattedNumber = '62' . substr($formattedNumber, 1);
                }
                $recipient = $formattedNumber . '@c.us';
            }

            // Kirim pesan via Web Sidecar
            WhatsApp::web('main')->messages()->sendText($recipient, $this->pesan);

        } catch (\Exception $e) {
            Log::error("Gagal kirim WA ke {$this->target}: " . $e->getMessage());
            throw $e;
        }
    }
}