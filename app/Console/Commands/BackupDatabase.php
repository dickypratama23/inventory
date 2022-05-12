<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup';
    protected $description = 'Backup the database';
    protected $process;

    public function __construct()
    {
        parent::__construct();

        $this->process = new Process(sprintf(
            'mysqldump -u%s -p%s %s --host=%s --port=2323 > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            config('database.connections.mysql.host'),
            storage_path('backup.sql')
        ));
    }

    public function handle()
    {
        try {
            $this->process->mustRun();

            $this->info('The backup has been proceed successfully.');
        } catch (ProcessFailedException $exception) {
            $this->error(config('database.connections.mysql.host'));
        }
    }
}
