<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CreateCrud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:create {tag*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'backpack generate crud';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tag = $this->argument('tag');
        if ($this->confirm('Do you wish to continue?')) {

           // print ( json_encode($tag));
           // Artisan::call('email:send', ['user' => 1, '--queue' => 'default']);
            if(count($tag)>0) {
                foreach ($tag as $t){
                    $t_table = strtolower(str_plural($t));
                    $w = str_replace('_',' ',$t);
                    $_class = ucwords($w);
                    $class = preg_replace('/\s+/', '', $_class);
                    print ('wait create '.$class.'...');
                    Artisan::call('make:migration:schema',['name' => 'create_'.$t_table.'_table' ,'--model'=>0]);
                    Artisan::call('backpack:crud',['name' => $class]);
                    Artisan::call('backpack:base:add-custom-route',['code' => 'CRUD::resource(\''.strtolower($class).'\', \''.$class.'CrudController\');']);
                    Artisan::call('backpack:base:add-sidebar-content',['code' => '<li><a href="{{ backpack_url(\''.strtolower($class).'\') }}"><i class=\'fa fa-tag\'></i> <span>'.$_class.'</span></a></li>']);
                    print('Success full crate CRUD '.$class);
                    print (' ');
                }
            }
        }
    }
}
