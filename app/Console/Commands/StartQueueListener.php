<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StartQueueListener extends Command
{
    protected $signature = 'queue:start-listener';
    protected $description = 'Start the queue listener';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Starting queue listener...');
        $this->call('queue:listen', [
            '--timeout' => 3600, // Set the timeout to 1 hour
        ]);
    }
}
