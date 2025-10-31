<?php

namespace App\Jobs;

use App\Models\Supervisi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessSupervisiUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $supervisi;
    protected $files;

    public function __construct(Supervisi $supervisi, array $files)
    {
        $this->supervisi = $supervisi;
        $this->files = $files;
    }

    public function handle(): void
    {
        foreach ($this->files as $file) {
            // Process file (optimize images, scan for viruses, etc.)
            // This runs in background
        }
    }
}