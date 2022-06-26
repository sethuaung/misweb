<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOldBranchToLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('loans_1')){
            $count = App\Models\Branch::count();
            $i = $count;
            for($j=1;$j<=$i;$j++){
                Schema::table('loans_'.$j, function (Blueprint $table) {
                    $table->integer('old_branch')->nullable();
                });
            }
        } else {
            Schema::table('loans', function (Blueprint $table) {
                $table->integer('old_branch')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn('old_branch');
        });
    }
}
