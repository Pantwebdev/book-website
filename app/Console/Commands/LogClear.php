<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LogClear extends Command
{
    protected $signature = 'log:clear';
    protected $description = 'Clear Laravel log files';

    public function handle()
    {
        foreach (glob(storage_path('logs/*.log')) as $file) {
            file_put_contents($file, '');
        }

        $this->info('Logs have been cleared!');
    }
}
