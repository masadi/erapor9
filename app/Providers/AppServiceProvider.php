<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Kstmostofa\LaravelWhatsApp\Events\Web\MessageReceived;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
        // Mendengarkan setiap pesan WhatsApp yang masuk dari Sidecar Stream
        Event::listen(MessageReceived::class, function (MessageReceived $event) {
            
            // Cek apakah pesan berasal dari Group WhatsApp
            if ($event->isGroup()) {
                $groupId = $event->from(); // Berisi ID Group (contoh: 120363123456789012@g.us)
                $body = $event->body();

                // Catat ke Log Laravel (storage/logs/laravel.log)
                Log::info("📩 PESAN GRUP DITERIMA", [
                    'group_id' => $groupId,
                    'pesan'    => $body,
                ]);

                // Tampilkan di terminal artisan (opsional untuk debugging)
                echo "\n----------------------------------------\n";
                echo "FOUND GROUP ID: " . $groupId . "\n";
                echo "Pesan: " . $body . "\n";
                echo "----------------------------------------\n";
            }
        });
    }
}
