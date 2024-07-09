<?php

namespace App\Console\Commands;

use App\Models\Commit;
use Illuminate\Console\Command;

class ClearCommitsOlderThan90Days extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-commits-older-than90-days';

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
        Commit::where("commited_at", "<", now()->subDays(90))->delete();
    }
}
