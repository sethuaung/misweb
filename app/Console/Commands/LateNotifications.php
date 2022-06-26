<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Helpers\IDate;
use App\Helpers\UnitDay;
use App\Models\LoanCalculate;
use App\Models\Notification;
use App\Notifications\LatePaymentNotification;

class LateNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'LateNotifications:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add late notifications to be viewed by each user';

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
        // $data = User::all();
        // printf($data);

        $date = IDate::dateAdd(date('Y-m-d'), UnitDay::DAY,2);
        $loan_cal = LoanCalculate::whereNull('date_p')->whereDate('date_s','<=', $date)->get();
        $users = User::all();

        if($loan_cal != null){
            foreach ($loan_cal as $l){
                // foreach ($users as $user) {
                    $iidd = 0;
                    $n = Notification::where('type','App\Notifications\LatePaymentNotification')
                        // ->where('notifiable_id',$user->id)
                        ->where('data','LIKE','%"id":'.$l->id.'%')
                        ->where('data','LIKE','%"date_s":'.$date.'%')
                        // ->where('date_s','LIKE','%"id":'.$l->id.'%')
                        ->first();
                        // dd($n);

                    if($n != null){
                        if($n->data != null){
                            $idd = json_decode($n->data);
                            $iidd = optional($idd)->id;
                        }
                    }
                foreach ($users as $user) {
                    if($iidd == 0){
                        $user->notify(new LatePaymentNotification($l));
                    }
                }
            }
        }

        echo "done";
    }
}
