<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class EndOfDayTask extends Command
{
    protected $signature = 'endofday:task';
    protected $description = 'Command to run a task at the end of each day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}
