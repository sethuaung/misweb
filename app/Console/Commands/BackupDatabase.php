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
            'mysqldump -u%s -p%s %s | gzip -c > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            storage_path('backups/'.date("Y-m-d-H-i-s").'-backup.sql.gz')
//            storage_path('backups/'.time().'backup.sql')
        ));
    }

    public function handle()
    {
        if(date('H')-0==1 || date('H')-0==12) {
            try {
                $this->process->mustRun();

                $this->info('The backup has been proceed successfully.');
            } catch (ProcessFailedException $exception) {
                $this->error('The backup process has been failed.');
            }
        }
    }
}
