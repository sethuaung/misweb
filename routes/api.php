<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/db-restore', function (Request $request) {

    echo '<table style="border-collapse: collapse;width: 50%;" border="1">';
    foreach (glob(storage_path('backups/*.gz')) as $filename) {
       // return response()->download($filename);

        $info = pathinfo($filename);
        echo  '<tr><td><a href="'.url('api/db-restore-download?f='.$info['basename']).'">'.$info['basename'].'</a></td><td style="width: 100">'.\App\Helpers\IDate::formatSizeUnits(filesize($filename)).'</td><td width="30"><a href="'.url('api/delete-db-restore?f='.$info['basename']).'">Delete</a></td></tr>';
    }
    echo '</table>';

    /* php artisan db:restore file */
   // \Illuminate\Support\Facades\Artisan::call('db:restore',['file' => $request->file]);
   // return 'db:restore '.$request->file;
    return '';
});

Route::get('/db-restore-download', function (Request $request) {
    $f = storage_path('backups/'.$request->f);
   return response()->download($f);
});
Route::get('/delete-db-restore', function (Request $request) {
    $f = storage_path('backups/'.$request->f);
    unlink($f);
    return redirect()->back();
});

Route::get('/db-backup', function (Request $request) {
    /* php artisan db:backup */
    \Illuminate\Support\Facades\Artisan::call('db:backup');
    return 'db:backup';
});
Route::get('/xxxxxxxx', function (Request $request) {
    /* php artisan db:backup */

    return time().floor(rand(1000,9999));
});


Route::get('/product-category', 'Api\CategoryController@index');
Route::get('/product-category/{id}', 'Api\CategoryController@show');

Route::get('/account-section', 'Api\AccSectionController@index');
Route::get('/account-section/{id}', 'Api\AccSectionController@show');

Route::get('/account-chart', 'Api\AccChartController@index');
Route::get('/account-chart/{id}', 'Api\AccChartController@show');

Route::get('/acc-chart-profit', 'Api\AccChartProfitController@index');
Route::get('/acc-chart-profit/{id}', 'Api\AccChartProfitController@show');


Route::get('/account-cash', 'Api\AccCashController@index');
Route::get('/account-cash/{id}', 'Api\AccCashController@show');

Route::get('/acc-chart-expense', 'Api\AccChartExpenseController@index');
Route::get('/acc-chart-expense/{id}', 'Api\AccChartExpenseController@show');


Route::get('/account-sub-section', 'Api\AccSubSectionController@index');
Route::get('/account-sub-section/{id}', 'Api\AccSubSectionController@show');

Route::get('/unit', 'Api\UnitController@index');
Route::get('/unit/{id}', 'Api\UnitController@show');

//------------------------------------------------

Route::get('/get-loan-calculate', 'Admin\LoanCalculatorCrudController@getLoanCalculation');

Route::get('/accrue-interest', 'Api\AccrueInterestCompulsory@index');


//================================

Route::get('/myanmar-address-state', 'Api\AddressMyanmarController@state');
Route::get('/myanmar-address-district', 'Api\AddressMyanmarController@district');
Route::get('/myanmar-address-township', 'Api\AddressMyanmarController@township');
Route::get('/myanmar-address-village', 'Api\AddressMyanmarController@village');
Route::get('/myanmar-address-ward', 'Api\AddressMyanmarController@ward');

Route::get('repayment-order',function (){
    return roundNum(1254.999999);
    \App\Helpers\MFS::getRepaymentAccount(1,100,10,50,10,20,150);
});

Route::get('/update-activate-date', function (){
    $disburse = \App\Models\PaidDisbursement::all();
    if($disburse != null){
        foreach ($disburse as $dis){
            $loan = \App\Models\Loan2::find($dis->contract_id);
            if($loan != null){
                $loan->status_note_activated = $disburse->paid_disbursement_date;
                $loan->save();
            }
        }
    }
});
Route::get('/frd',function (){
   $amt = \App\Helpers\ACC::getFrdAccountAmount('pl-001');
   return $amt;
});
Route::get('insert-schedule-tem', function (){
    $loans = \App\Models\Loan::simplePaginate(600);
    //where('disbursement_status','Closed')
       // ->whereYear('loan_application_date', '<=', 2019)
       // ->simplePaginate(600);
    $n = ($loans->currentPage()+1);
    if($loans != null){
        foreach ($loans as $l){

            $mmm =  \App\Models\LoanCalculate::where('disbursement_id',$l->id)
                ->selectRaw('year(date_s) as yyy')
                ->orderBy('date_s','DESC')
                ->first();

            if($mmm != null) {
                if($mmm->yyy <= 2019) {
                    DB::unprepared("INSERT INTO loan_schedule_2019 SELECT * FROM loan_disbursement_calculate WHERE disbursement_id = {$l->id}");

                    \App\Models\LoanCalculate::where('disbursement_id', $l->id)->delete();
                }
            }
        }
    }
    if($loans->hasMorePages()) {
        return '<head>
            <meta http-equiv=\'refresh\' content=\'1; URL=' . url('api/insert-schedule-tem?&page=' . $n) . '\'>
    </head><h1>Wait ...('.$n.')</h1>';
    }else{
        return $n;
     //   return redirect('/');
    }
});


Route::get('/gen-saving-mkt', function (Request $request) {

    $date = $request->date;
    $branch_id = $request->branch_id;

    /* php artisan migrate */
    \Artisan::call("mkt:saving", ['date'=>$date, 'branch_id'=>$branch_id]);


    return 'Done';
});

Route::get('/save-client-approved','Api\ClientController@save_client');
