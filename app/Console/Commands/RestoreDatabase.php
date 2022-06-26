<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class RestoreDatabase extends Command
{
    protected $signature = 'db:restore {file}';

    protected $description = 'restore the database';

    protected $process;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $sql_path =  storage_path('backups/'.$this->argument('file'));
        $this->process = new Process(sprintf(
            'gunzip < %s | mysql -u%s -p%s %s',
            $sql_path,
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database')
//            storage_path('backups/'.time().'backup.sql')
        ));
        //$this->info(storage_path('backups/'.$this->argument('file')));
        try {
            $this->process->mustRun();
            $this->info('The restored has been proceed successfully.');
        } catch (ProcessFailedException $exception) {
            $this->error('The restored process has been failed.');
        }
    }
}
