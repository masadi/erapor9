<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kstmostofa\LaravelWhatsApp\Facades\WhatsApp;
use Illuminate\Support\Facades\Http;

class Debugger extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:debugger';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $response = Http::get('http://127.0.0.1:3001/sessions/main/groups');
    
        $this->info("HTTP Status: " . $response->status());
        dd($response->json());
        $groups = WhatsApp::web('main')->groups()->all();
        dd($groups);
    }
}
