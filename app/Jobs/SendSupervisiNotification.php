<?php

namespace App\Jobs;

use App\Models\Supervisi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

/**
 * Job untuk mengirim notifikasi email saat supervisi disubmit
 * 
 * Contoh penggunaan:
 * SendSupervisiNotification::dispatch($supervisi);
 */
class SendSupervisiNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $supervisi;

    /**
     * Create a new job instance.
     */
    public function __construct(Supervisi $supervisi)
    {
        $this->supervisi = $supervisi;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Kirim email ke kepala sekolah
        // Mail::to($kepalaSekolah->email)
        //     ->send(new SupervisiSubmitted($this->supervisi));
        
        // Log untuk development
        \Log::info('Supervisi notification sent', [
            'supervisi_id' => $this->supervisi->id,
            'user' => $this->supervisi->user->name,
            'status' => $this->supervisi->status
        ]);
    }
}
