<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserByToAllTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $DB_DATABASE = env('DB_DATABASE',null);

//        dd($DB_DATABASE);


        $tables = \DB::select('SHOW TABLES');
        foreach($tables as $table)
        {
            $f = "Tables_in_{$DB_DATABASE}";
            $tbl = $table->{$f};
            if($tbl != null){
                if( $tbl == 'migrations' || $tbl == 'addresses') {

                }else{
                    if(!(Schema::hasColumn($tbl, 'created_by')))  //check whether users table has email column
                    {
                        \DB::unprepared("alter table `{$tbl}`
	                                            add created_by int default 0 null;");
                    }

                    if(!(Schema::hasColumn($tbl, 'updated_by')))  //check whether users table has email column
                    {
                        \DB::unprepared("alter table `{$tbl}`
	                                            add updated_by int default 0 null;");
                    }

                }
            }
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
