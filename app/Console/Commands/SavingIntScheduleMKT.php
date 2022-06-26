<?php

namespace App\Console\Commands;

use App\Models\CompulsoryAccrueInterests;
use App\Models\CompulsorySavingTransaction;
use App\Models\CompulsorySavingTransactionBranch;
use App\Models\Loan;
use App\Models\LoanBranch;
use App\Models\LoanCompulsory;
use App\Models\LoanCompulsoryByBranch;
use App\Models\LoanCompulsoryByBranch10;
use App\Models\LoanCompulsoryByBranch11;
use App\Models\LoanCompulsoryByBranch12;
use App\Models\LoanCompulsoryByBranch13;
use App\Models\LoanCompulsoryByBranch14;
use App\Models\LoanCompulsoryByBranch15;
use App\Models\LoanCompulsoryByBranch16;
use App\Models\LoanCompulsoryByBranch17;
use App\Models\LoanCompulsoryByBranch18;
use App\Models\LoanCompulsoryByBranch19;
use App\Models\LoanCompulsoryByBranch2;
use App\Models\LoanCompulsoryByBranch20;
use App\Models\LoanCompulsoryByBranch21;
use App\Models\LoanCompulsoryByBranch22;
use App\Models\LoanCompulsoryByBranch23;
use App\Models\LoanCompulsoryByBranch24;
use App\Models\LoanCompulsoryByBranch25;
use App\Models\LoanCompulsoryByBranch26;
use App\Models\LoanCompulsoryByBranch27;
use App\Models\LoanCompulsoryByBranch3;
use App\Models\LoanCompulsoryByBranch4;
use App\Models\LoanCompulsoryByBranch5;
use App\Models\LoanCompulsoryByBranch6;
use App\Models\LoanCompulsoryByBranch7;
use App\Models\LoanCompulsoryByBranch8;
use App\Models\LoanCompulsoryByBranch9;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SavingIntScheduleMKT extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mkt:saving {date?} {branch_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        if ($date_now = $this->argument('date')) {

            $_date_m = Carbon::parse($date_now)->format('Y-m');
            $date_m = $_date_m.'-01';

            $branch_id =  $this->argument('branch_id');

            $last_month = Carbon::parse($date_m)->subMonth()->endOfMonth()->toDateString();

            //$this->info("Syncing event $last_month");

            LoanCompulsoryByBranch::where('compulsory_status','Active')
                ->selectRaw('id, principles, interest_rate, compulsory_id,loan_id , client_id, compulsory_product_type_id, branch_id, interests ')
                ->chunk(500, function($loan_compulsory) use ($date_now, $date_m, $last_month) {
                    foreach ($loan_compulsory as $saving){
                        $accrue_no = time().floor(rand(1000,9999));

                        DB::unprepared("call generateSaving1('{$saving->id}','{$saving->loan_id}', '{$saving->compulsory_product_type_id}','{$date_m}' ,'{$date_now}', '{$saving->interest_rate}', '{$saving->compulsory_id}', '{$saving->client_id}', '{$accrue_no}', '{$saving->branch_id}', '{$saving->interests}', '{$saving->principles}', '{$last_month}' ) ;");

                    }

            });

            LoanCompulsoryByBranch2::where('compulsory_status','Active')
                ->selectRaw('id, principles, interest_rate, compulsory_id,loan_id , client_id, compulsory_product_type_id, branch_id, interests ')
                ->chunk(500, function($loan_compulsory) use ($date_now, $date_m, $last_month) {
                    foreach ($loan_compulsory as $saving){
                        $accrue_no = time().floor(rand(1000,9999));

                        DB::unprepared("call generateSaving1_2('{$saving->id}','{$saving->loan_id}', '{$saving->compulsory_product_type_id}','{$date_m}' ,'{$date_now}', '{$saving->interest_rate}', '{$saving->compulsory_id}', '{$saving->client_id}', '{$accrue_no}', '{$saving->branch_id}', '{$saving->interests}', '{$saving->principles}', '{$last_month}' ) ;");

                    }

            });

            LoanCompulsoryByBranch3::where('compulsory_status','Active')
                ->selectRaw('id, principles, interest_rate, compulsory_id,loan_id , client_id, compulsory_product_type_id, branch_id, interests ')
                ->chunk(500, function($loan_compulsory) use ($date_now, $date_m, $last_month) {
                    foreach ($loan_compulsory as $saving){
                        $accrue_no = time().floor(rand(1000,9999));

                        DB::unprepared("call generateSaving1_3('{$saving->id}','{$saving->loan_id}', '{$saving->compulsory_product_type_id}','{$date_m}' ,'{$date_now}', '{$saving->interest_rate}', '{$saving->compulsory_id}', '{$saving->client_id}', '{$accrue_no}', '{$saving->branch_id}', '{$saving->interests}', '{$saving->principles}', '{$last_month}' ) ;");

                    }

            });

            LoanCompulsoryByBranch4::where('compulsory_status','Active')
                ->selectRaw('id, principles, interest_rate, compulsory_id,loan_id , client_id, compulsory_product_type_id, branch_id, interests ')
                ->chunk(500, function($loan_compulsory) use ($date_now, $date_m, $last_month) {
                    foreach ($loan_compulsory as $saving){
                        $accrue_no = time().floor(rand(1000,9999));

                        DB::unprepared("call generateSaving1_4('{$saving->id}','{$saving->loan_id}', '{$saving->compulsory_product_type_id}','{$date_m}' ,'{$date_now}', '{$saving->interest_rate}', '{$saving->compulsory_id}', '{$saving->client_id}', '{$accrue_no}', '{$saving->branch_id}', '{$saving->interests}', '{$saving->principles}', '{$last_month}' ) ;");

                    }

            });

            LoanCompulsoryByBranch5::where('compulsory_status','Active')
                ->selectRaw('id, principles, interest_rate, compulsory_id,loan_id , client_id, compulsory_product_type_id, branch_id, interests ')
                ->chunk(500, function($loan_compulsory) use ($date_now, $date_m, $last_month) {
                    foreach ($loan_compulsory as $saving){
                        $accrue_no = time().floor(rand(1000,9999));

                        DB::unprepared("call generateSaving1_5('{$saving->id}','{$saving->loan_id}', '{$saving->compulsory_product_type_id}','{$date_m}' ,'{$date_now}', '{$saving->interest_rate}', '{$saving->compulsory_id}', '{$saving->client_id}', '{$accrue_no}', '{$saving->branch_id}', '{$saving->interests}', '{$saving->principles}', '{$last_month}' ) ;");

                    }

            });

            LoanCompulsoryByBranch6::where('compulsory_status','Active')
                ->selectRaw('id, principles, interest_rate, compulsory_id,loan_id , client_id, compulsory_product_type_id, branch_id, interests ')
                ->chunk(500, function($loan_compulsory) use ($date_now, $date_m, $last_month) {
                    foreach ($loan_compulsory as $saving){
                        $accrue_no = time().floor(rand(1000,9999));

                        DB::unprepared("call generateSaving1_6('{$saving->id}','{$saving->loan_id}', '{$saving->compulsory_product_type_id}','{$date_m}' ,'{$date_now}', '{$saving->interest_rate}', '{$saving->compulsory_id}', '{$saving->client_id}', '{$accrue_no}', '{$saving->branch_id}', '{$saving->interests}', '{$saving->principles}', '{$last_month}' ) ;");

                    }

            });

            LoanCompulsoryByBranch7::where('compulsory_status','Active')
                ->selectRaw('id, principles, interest_rate, compulsory_id,loan_id , client_id, compulsory_product_type_id, branch_id, interests ')
                ->chunk(500, function($loan_compulsory) use ($date_now, $date_m, $last_month) {
                    foreach ($loan_compulsory as $saving){
                        $accrue_no = time().floor(rand(1000,9999));

                        DB::unprepared("call generateSaving1_7('{$saving->id}','{$saving->loan_id}', '{$saving->compulsory_product_type_id}','{$date_m}' ,'{$date_now}', '{$saving->interest_rate}', '{$saving->compulsory_id}', '{$saving->client_id}', '{$accrue_no}', '{$saving->branch_id}', '{$saving->interests}', '{$saving->principles}', '{$last_month}' ) ;");

                    }

            });

            LoanCompulsoryByBranch8::where('compulsory_status','Active')
                ->selectRaw('id, principles, interest_rate, compulsory_id,loan_id , client_id, compulsory_product_type_id, branch_id, interests ')
                ->chunk(500, function($loan_compulsory) use ($date_now, $date_m, $last_month) {
                    foreach ($loan_compulsory as $saving){
                        $accrue_no = time().floor(rand(1000,9999));

                        DB::unprepared("call generateSaving1_8('{$saving->id}','{$saving->loan_id}', '{$saving->compulsory_product_type_id}','{$date_m}' ,'{$date_now}', '{$saving->interest_rate}', '{$saving->compulsory_id}', '{$saving->client_id}', '{$accrue_no}', '{$saving->branch_id}', '{$saving->interests}', '{$saving->principles}', '{$last_month}' ) ;");

                    }

            });

            LoanCompulsoryByBranch9::where('compulsory_status','Active')
                ->selectRaw('id, principles, interest_rate, compulsory_id,loan_id , client_id, compulsory_product_type_id, branch_id, interests ')
                ->chunk(500, function($loan_compulsory) use ($date_now, $date_m, $last_month) {
                    foreach ($loan_compulsory as $saving){
                        $accrue_no = time().floor(rand(1000,9999));

                        DB::unprepared("call generateSaving1_9('{$saving->id}','{$saving->loan_id}', '{$saving->compulsory_product_type_id}','{$date_m}' ,'{$date_now}', '{$saving->interest_rate}', '{$saving->compulsory_id}', '{$saving->client_id}', '{$accrue_no}', '{$saving->branch_id}', '{$saving->interests}', '{$saving->principles}', '{$last_month}' ) ;");

                    }

            });

            LoanCompulsoryByBranch10::where('compulsory_status','Active')
                ->selectRaw('id, principles, interest_rate, compulsory_id,loan_id , client_id, compulsory_product_type_id, branch_id, interests ')
                ->chunk(500, function($loan_compulsory) use ($date_now, $date_m, $last_month) {
                    foreach ($loan_compulsory as $saving){
                        $accrue_no = time().floor(rand(1000,9999));

                        DB::unprepared("call generateSaving1_10('{$saving->id}','{$saving->loan_id}', '{$saving->compulsory_product_type_id}','{$date_m}' ,'{$date_now}', '{$saving->interest_rate}', '{$saving->compulsory_id}', '{$saving->client_id}', '{$accrue_no}', '{$saving->branch_id}', '{$saving->interests}', '{$saving->principles}', '{$last_month}' ) ;");

                    }

            });

            LoanCompulsoryByBranch11::where('compulsory_status','Active')
                ->selectRaw('id, principles, interest_rate, compulsory_id,loan_id , client_id, compulsory_product_type_id, branch_id, interests ')
                ->chunk(500, function($loan_compulsory) use ($date_now, $date_m, $last_month) {
                    foreach ($loan_compulsory as $saving){
                        $accrue_no = time().floor(rand(1000,9999));

                        DB::unprepared("call generateSaving1_11('{$saving->id}','{$saving->loan_id}', '{$saving->compulsory_product_type_id}','{$date_m}' ,'{$date_now}', '{$saving->interest_rate}', '{$saving->compulsory_id}', '{$saving->client_id}', '{$accrue_no}', '{$saving->branch_id}', '{$saving->interests}', '{$saving->principles}', '{$last_month}' ) ;");

                    }

            });

            LoanCompulsoryByBranch12::where('compulsory_status','Active')
                ->selectRaw('id, principles, interest_rate, compulsory_id,loan_id , client_id, compulsory_product_type_id, branch_id, interests ')
                ->chunk(500, function($loan_compulsory) use ($date_now, $date_m, $last_month) {
                    foreach ($loan_compulsory as $saving){
                        $accrue_no = time().floor(rand(1000,9999));

                        DB::unprepared("call generateSaving1_12('{$saving->id}','{$saving->loan_id}', '{$saving->compulsory_product_type_id}','{$date_m}' ,'{$date_now}', '{$saving->interest_rate}', '{$saving->compulsory_id}', '{$saving->client_id}', '{$accrue_no}', '{$saving->branch_id}', '{$saving->interests}', '{$saving->principles}', '{$last_month}' ) ;");

                    }

            });

            LoanCompulsoryByBranch13::where('compulsory_status','Active')
                ->selectRaw('id, principles, interest_rate, compulsory_id,loan_id , client_id, compulsory_product_type_id, branch_id, interests ')
                ->chunk(500, function($loan_compulsory) use ($date_now, $date_m, $last_month) {
                    foreach ($loan_compulsory as $saving){
                        $accrue_no = time().floor(rand(1000,9999));

                        DB::unprepared("call generateSaving1_13('{$saving->id}','{$saving->loan_id}', '{$saving->compulsory_product_type_id}','{$date_m}' ,'{$date_now}', '{$saving->interest_rate}', '{$saving->compulsory_id}', '{$saving->client_id}', '{$accrue_no}', '{$saving->branch_id}', '{$saving->interests}', '{$saving->principles}', '{$last_month}' ) ;");

                    }

            });

            LoanCompulsoryByBranch14::where('compulsory_status','Active')
                ->selectRaw('id, principles, interest_rate, compulsory_id,loan_id , client_id, compulsory_product_type_id, branch_id, interests ')
                ->chunk(500, function($loan_compulsory) use ($date_now, $date_m, $last_month) {
                    foreach ($loan_compulsory as $saving){
                        $accrue_no = time().floor(rand(1000,9999));

                        DB::unprepared("call generateSaving1_14('{$saving->id}','{$saving->loan_id}', '{$saving->compulsory_product_type_id}','{$date_m}' ,'{$date_now}', '{$saving->interest_rate}', '{$saving->compulsory_id}', '{$saving->client_id}', '{$accrue_no}', '{$saving->branch_id}', '{$saving->interests}', '{$saving->principles}', '{$last_month}' ) ;");

                    }

            });

            LoanCompulsoryByBranch15::where('compulsory_status','Active')
                ->selectRaw('id, principles, interest_rate, compulsory_id,loan_id , client_id, compulsory_product_type_id, branch_id, interests ')
                ->chunk(500, function($loan_compulsory) use ($date_now, $date_m, $last_month) {
                    foreach ($loan_compulsory as $saving){
                        $accrue_no = time().floor(rand(1000,9999));

                        DB::unprepared("call generateSaving1_15('{$saving->id}','{$saving->loan_id}', '{$saving->compulsory_product_type_id}','{$date_m}' ,'{$date_now}', '{$saving->interest_rate}', '{$saving->compulsory_id}', '{$saving->client_id}', '{$accrue_no}', '{$saving->branch_id}', '{$saving->interests}', '{$saving->principles}', '{$last_month}' ) ;");

                    }

            });

            LoanCompulsoryByBranch16::where('compulsory_status','Active')
                ->selectRaw('id, principles, interest_rate, compulsory_id,loan_id , client_id, compulsory_product_type_id, branch_id, interests ')
                ->chunk(500, function($loan_compulsory) use ($date_now, $date_m, $last_month) {
                    foreach ($loan_compulsory as $saving){
                        $accrue_no = time().floor(rand(1000,9999));

                        DB::unprepared("call generateSaving1_16('{$saving->id}','{$saving->loan_id}', '{$saving->compulsory_product_type_id}','{$date_m}' ,'{$date_now}', '{$saving->interest_rate}', '{$saving->compulsory_id}', '{$saving->client_id}', '{$accrue_no}', '{$saving->branch_id}', '{$saving->interests}', '{$saving->principles}', '{$last_month}' ) ;");

                    }

            });

            LoanCompulsoryByBranch17::where('compulsory_status','Active')
                ->selectRaw('id, principles, interest_rate, compulsory_id,loan_id , client_id, compulsory_product_type_id, branch_id, interests ')
                ->chunk(500, function($loan_compulsory) use ($date_now, $date_m, $last_month) {
                    foreach ($loan_compulsory as $saving){
                        $accrue_no = time().floor(rand(1000,9999));

                        DB::unprepared("call generateSaving1_17('{$saving->id}','{$saving->loan_id}', '{$saving->compulsory_product_type_id}','{$date_m}' ,'{$date_now}', '{$saving->interest_rate}', '{$saving->compulsory_id}', '{$saving->client_id}', '{$accrue_no}', '{$saving->branch_id}', '{$saving->interests}', '{$saving->principles}', '{$last_month}' ) ;");

                    }

            });

            LoanCompulsoryByBranch18::where('compulsory_status','Active')
                ->selectRaw('id, principles, interest_rate, compulsory_id,loan_id , client_id, compulsory_product_type_id, branch_id, interests ')
                ->chunk(500, function($loan_compulsory) use ($date_now, $date_m, $last_month) {
                    foreach ($loan_compulsory as $saving){
                        $accrue_no = time().floor(rand(1000,9999));

                        DB::unprepared("call generateSaving1_18('{$saving->id}','{$saving->loan_id}', '{$saving->compulsory_product_type_id}','{$date_m}' ,'{$date_now}', '{$saving->interest_rate}', '{$saving->compulsory_id}', '{$saving->client_id}', '{$accrue_no}', '{$saving->branch_id}', '{$saving->interests}', '{$saving->principles}', '{$last_month}' ) ;");

                    }

            });

            LoanCompulsoryByBranch19::where('compulsory_status','Active')
                ->selectRaw('id, principles, interest_rate, compulsory_id,loan_id , client_id, compulsory_product_type_id, branch_id, interests ')
                ->chunk(500, function($loan_compulsory) use ($date_now, $date_m, $last_month) {
                    foreach ($loan_compulsory as $saving){
                        $accrue_no = time().floor(rand(1000,9999));

                        DB::unprepared("call generateSaving1_19('{$saving->id}','{$saving->loan_id}', '{$saving->compulsory_product_type_id}','{$date_m}' ,'{$date_now}', '{$saving->interest_rate}', '{$saving->compulsory_id}', '{$saving->client_id}', '{$accrue_no}', '{$saving->branch_id}', '{$saving->interests}', '{$saving->principles}', '{$last_month}' ) ;");

                    }

            });

            LoanCompulsoryByBranch20::where('compulsory_status','Active')
                ->selectRaw('id, principles, interest_rate, compulsory_id,loan_id , client_id, compulsory_product_type_id, branch_id, interests ')
                ->chunk(500, function($loan_compulsory) use ($date_now, $date_m, $last_month) {
                    foreach ($loan_compulsory as $saving){
                        $accrue_no = time().floor(rand(1000,9999));

                        DB::unprepared("call generateSaving1_20('{$saving->id}','{$saving->loan_id}', '{$saving->compulsory_product_type_id}','{$date_m}' ,'{$date_now}', '{$saving->interest_rate}', '{$saving->compulsory_id}', '{$saving->client_id}', '{$accrue_no}', '{$saving->branch_id}', '{$saving->interests}', '{$saving->principles}', '{$last_month}' ) ;");

                    }

            });

            LoanCompulsoryByBranch21::where('compulsory_status','Active')
                ->selectRaw('id, principles, interest_rate, compulsory_id,loan_id , client_id, compulsory_product_type_id, branch_id, interests ')
                ->chunk(500, function($loan_compulsory) use ($date_now, $date_m, $last_month) {
                    foreach ($loan_compulsory as $saving){
                        $accrue_no = time().floor(rand(1000,9999));

                        DB::unprepared("call generateSaving1_21('{$saving->id}','{$saving->loan_id}', '{$saving->compulsory_product_type_id}','{$date_m}' ,'{$date_now}', '{$saving->interest_rate}', '{$saving->compulsory_id}', '{$saving->client_id}', '{$accrue_no}', '{$saving->branch_id}', '{$saving->interests}', '{$saving->principles}', '{$last_month}' ) ;");

                    }

            });

            LoanCompulsoryByBranch22::where('compulsory_status','Active')
                ->selectRaw('id, principles, interest_rate, compulsory_id,loan_id , client_id, compulsory_product_type_id, branch_id, interests ')
                ->chunk(500, function($loan_compulsory) use ($date_now, $date_m, $last_month) {
                    foreach ($loan_compulsory as $saving){
                        $accrue_no = time().floor(rand(1000,9999));

                        DB::unprepared("call generateSaving1_22('{$saving->id}','{$saving->loan_id}', '{$saving->compulsory_product_type_id}','{$date_m}' ,'{$date_now}', '{$saving->interest_rate}', '{$saving->compulsory_id}', '{$saving->client_id}', '{$accrue_no}', '{$saving->branch_id}', '{$saving->interests}', '{$saving->principles}', '{$last_month}' ) ;");

                    }

            });

            LoanCompulsoryByBranch23::where('compulsory_status','Active')
                ->selectRaw('id, principles, interest_rate, compulsory_id,loan_id , client_id, compulsory_product_type_id, branch_id, interests ')
                ->chunk(500, function($loan_compulsory) use ($date_now, $date_m, $last_month) {
                    foreach ($loan_compulsory as $saving){
                        $accrue_no = time().floor(rand(1000,9999));

                        DB::unprepared("call generateSaving1_23('{$saving->id}','{$saving->loan_id}', '{$saving->compulsory_product_type_id}','{$date_m}' ,'{$date_now}', '{$saving->interest_rate}', '{$saving->compulsory_id}', '{$saving->client_id}', '{$accrue_no}', '{$saving->branch_id}', '{$saving->interests}', '{$saving->principles}', '{$last_month}' ) ;");

                    }

            });

            LoanCompulsoryByBranch24::where('compulsory_status','Active')
                ->selectRaw('id, principles, interest_rate, compulsory_id,loan_id , client_id, compulsory_product_type_id, branch_id, interests ')
                ->chunk(500, function($loan_compulsory) use ($date_now, $date_m, $last_month) {
                    foreach ($loan_compulsory as $saving){
                        $accrue_no = time().floor(rand(1000,9999));

                        DB::unprepared("call generateSaving1_24('{$saving->id}','{$saving->loan_id}', '{$saving->compulsory_product_type_id}','{$date_m}' ,'{$date_now}', '{$saving->interest_rate}', '{$saving->compulsory_id}', '{$saving->client_id}', '{$accrue_no}', '{$saving->branch_id}', '{$saving->interests}', '{$saving->principles}', '{$last_month}' ) ;");

                    }

            });

            LoanCompulsoryByBranch25::where('compulsory_status','Active')
                ->selectRaw('id, principles, interest_rate, compulsory_id,loan_id , client_id, compulsory_product_type_id, branch_id, interests ')
                ->chunk(500, function($loan_compulsory) use ($date_now, $date_m, $last_month) {
                    foreach ($loan_compulsory as $saving){
                        $accrue_no = time().floor(rand(1000,9999));

                        DB::unprepared("call generateSaving1_25('{$saving->id}','{$saving->loan_id}', '{$saving->compulsory_product_type_id}','{$date_m}' ,'{$date_now}', '{$saving->interest_rate}', '{$saving->compulsory_id}', '{$saving->client_id}', '{$accrue_no}', '{$saving->branch_id}', '{$saving->interests}', '{$saving->principles}', '{$last_month}' ) ;");

                    }

            });

            LoanCompulsoryByBranch26::where('compulsory_status','Active')
                ->selectRaw('id, principles, interest_rate, compulsory_id,loan_id , client_id, compulsory_product_type_id, branch_id, interests ')
                ->chunk(500, function($loan_compulsory) use ($date_now, $date_m, $last_month) {
                    foreach ($loan_compulsory as $saving){
                        $accrue_no = time().floor(rand(1000,9999));

                        DB::unprepared("call generateSaving1_26('{$saving->id}','{$saving->loan_id}', '{$saving->compulsory_product_type_id}','{$date_m}' ,'{$date_now}', '{$saving->interest_rate}', '{$saving->compulsory_id}', '{$saving->client_id}', '{$accrue_no}', '{$saving->branch_id}', '{$saving->interests}', '{$saving->principles}', '{$last_month}' ) ;");

                    }

            });

            LoanCompulsoryByBranch27::where('compulsory_status','Active')
                ->selectRaw('id, principles, interest_rate, compulsory_id,loan_id , client_id, compulsory_product_type_id, branch_id, interests ')
                ->chunk(500, function($loan_compulsory) use ($date_now, $date_m, $last_month) {
                    foreach ($loan_compulsory as $saving){
                        $accrue_no = time().floor(rand(1000,9999));

                        DB::unprepared("call generateSaving1_27('{$saving->id}','{$saving->loan_id}', '{$saving->compulsory_product_type_id}','{$date_m}' ,'{$date_now}', '{$saving->interest_rate}', '{$saving->compulsory_id}', '{$saving->client_id}', '{$accrue_no}', '{$saving->branch_id}', '{$saving->interests}', '{$saving->principles}', '{$last_month}' ) ;");

                    }

            });



        }


    }
}
