<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\IDate;
use App\Helpers\UnitDay;
use App\Models\LoanCalculate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationPaymentController extends Controller
{
    public function notificationPayment(Request $request)
    {
        $date = IDate::dateAdd(date('Y-m-d'),UnitDay::DAY,2);

        $late = LoanCalculate::selectRaw('distinct disbursement_id')->whereNull('date_p')->whereDate('date_s','<=',$date)->get();


        return view('partials.payment.notification_payment',['rows'=>$late]);
    }
}
