<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class VisitaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $ip = "0.0.0.0")
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        sleep(random_int(1, 20));
        $this->ip;
    }

    public function tags(): array
    {
        return ['VisitaJob'];
    }

}
