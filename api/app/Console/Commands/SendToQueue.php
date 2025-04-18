<?php

namespace App\Console\Commands;

use App\Jobs\ProcessTask;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Queue;

class SendToQueue extends Command
{
    protected $signature = 'send:toqueue';
    protected $description = 'Send task to redis queue';

    public function handle(): void
    {
        Queue::push(new ProcessTask());
        $this->info('Task has been sent to the queue');
    }
}
