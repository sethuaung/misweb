<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{CompulsorySavingTransaction, CompulsoryAccrueInterests};
use App\User;
use Carbon\Carbon;
use DB;
use Auth;
// use Backpack\CRUD\app\Http\Controllers\CrudController;

class CompulsoryAccrueInterestsCalculation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CompulsoryAccrueInterests:calculation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate Accrue Interests for Compulsory Saving Daily';

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
        // $currentDate = Carbon::now()->toDateString();
        // $now = Carbon::now();
        $now = Carbon::yesterday();

        // Turn off strict mode
        config()->set('database.connections.mysql.strict', false);
        \DB::reconnect(); //important as the existing connection if any would be in strict mode

        // Get minimum saving amount of each month for each client with loan
        $minSavings = CompulsorySavingTransaction::select('*', DB::raw('MIN(total_principle) AS min_principle'))
        ->where(function($query) use($now) {
            $query->whereMonth('tran_date', $now->month);
            $query->whereYear('tran_date', $now->year);
        })
        ->where(function($query){
            $query->where('train_type_ref', 'saving');
            $query->where('train_type', 'deposit');
        })
        ->orWhere(function($query){
            $query->where('train_type_ref', 'withdraw');
            $query->where('train_type', 'withdraw');
        })
        ->groupBy(['customer_id'])
        ->get()
        ->map(function ($value, $key) {
            $value->new_total_interest = $value->total_interest + (($value->min_principle * 1.25)/100);
            return $value;
        });

        // Turn on strict mode
        config()->set('database.connections.mysql.strict', true);
        \DB::reconnect();

        $user = User::find(1);
        Auth::login($user); // Super Admin account login for logs
        // $minSavings_chunk = $minSavings->chunk(1);

        try {

            // use chunk function to prevent allocated memory out error
            foreach ($minSavings->chunk(100) as $minSavings_chunk) {

                // $minSavings->chunk(1, function ($minSavings) {
                    foreach ($minSavings_chunk as $minSaving) {

                        $total_principle = CompulsorySavingTransaction::where(function($query) use($now) {
                            $query->whereMonth('tran_date', $now->month);
                            $query->whereYear('tran_date', $now->year);
                        })
                        ->where(function($query){
                            $query->where(function($q){
                                $q->where('train_type_ref', 'saving');
                                $q->where('train_type', 'deposit');
                            });
                            $query->orWhere(function($q){
                                $q->where('train_type_ref', 'withdraw');
                                $q->where('train_type', 'withdraw');
                            });
                        })
                        ->where('customer_id', $minSaving->customer_id)
                        ->orderBy('created_by', 'ASC')
                        ->first()
                        ->total_principle;

                        CompulsorySavingTransaction::create([
                            'customer_id' => $minSaving->customer_id,
                            'train_type' => 'accrue_interest',
                            'tran_id' => $minSaving->tran_id,
                            'train_type_ref' => 'saving',
                            'tran_id_ref' => $minSaving->tran_id_ref,
                            'tran_date' => $now->toDateString(),
                            'amount' => $minSaving->new_total_interest,
                            'total_principle' => $total_principle,
                            'total_interest' => $minSaving->new_total_interest,
                            'available_balance' => $total_principle + $minSaving->new_total_interest,
                            'loan_id' => $minSaving->loan_id,
                            'loan_compulsory_id' => $minSaving->loan_compulsory_id,
                        ])->save();

                        // after created, it will trigger run functions in its Model
                    }
                // });
            }

        } catch (\Exception $e) {

            printf($e->getMessage());
        }
    }
}
