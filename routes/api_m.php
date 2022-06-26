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


Route::post('/register','M\RegisterController@register');
Route::post('/login','M\LoginController@login');

Route::get('/get-client-near-by','M\ClientController@getClientNearBy');
Route::post('/get-client-near-by','M\ClientController@getClientNearBy');

Route::get('/get-client','M\ClientController@getClient');
Route::post('/get-client','M\ClientController@getClient');
Route::post('/store-client','M\ClientController@storeClient');
Route::post('/store-loan','M\LoanController@storeLoan');
Route::get('/get-branch','M\ClientController@getBranch');
Route::post('/get-branch','M\ClientController@getBranch');

Route::get('/get-center-leader','M\ClientController@getCenterLeader');
Route::post('/get-center-leader','M\ClientController@getCenterLeader');

Route::get('/get-guarantor','M\LoanController@getGuarantor');
Route::post('/get-guarantor','M\LoanController@getGuarantor');

Route::get('/get-product','M\LoanController@getProduct');
Route::post('/get-product','M\LoanController@getProduct');

Route::get('/get-loan-officer','M\ClientController@getLoanOfficer');
Route::post('/get-loan-officer','M\ClientController@getLoanOfficer');

Route::get('/get-service-charge','M\ClientController@getLoanOfficer');
Route::post('/get-service-charge','M\ClientController@getLoanOfficer');


Route::get('/get-lat-lng','M\ClientController@getLatLng');
Route::post('/get-lat-lng','M\ClientController@getLatLng');

Route::get('/get-compulsory','M\LoanController@getCompulsory');
Route::post('/get-compulsory','M\LoanController@getCompulsory');

Route::post('/get-group','M\LoanController@getGroup');
Route::get('/get-group','M\LoanController@getGroup');

Route::get('/myanmar-address-state', 'Api\AddressMyanmarController@state');
Route::post('/myanmar-address-state', 'Api\AddressMyanmarController@state');

Route::get('/myanmar-address-district', 'Api\AddressMyanmarController@district');
Route::post('/myanmar-address-district', 'Api\AddressMyanmarController@district');

Route::get('/myanmar-address-township', 'Api\AddressMyanmarController@township');
Route::post('/myanmar-address-township', 'Api\AddressMyanmarController@township');

Route::get('/myanmar-address-village', 'Api\AddressMyanmarController@village');
Route::post('/myanmar-address-village', 'Api\AddressMyanmarController@village');

Route::get('/myanmar-address-ward', 'Api\AddressMyanmarController@ward');
Route::post('/myanmar-address-ward', 'Api\AddressMyanmarController@ward');


Route::post('/store-client', 'M\ClientController@storeClient');
Route::get('/store-client', 'M\ClientController@storeClient');

Route::post('/store-guarantor', 'M\ClientController@storeGuarantor');
Route::get('/store-guarantor', 'M\ClientController@storeGuarantor');


Route::post('/loan-disbursement-pending', 'M\LoanController@loanDisbursePending');
Route::get('/loan-disbursement-pending', 'M\LoanController@loanDisbursePending');



Route::get('/get-survey', 'M\ClientController@getSurvey');
Route::post('/get-survey', 'M\ClientController@getSurvey');


Route::get('/get-owner-ship-farmland', 'M\ClientController@getOwnerShipFarmland');
Route::post('/get-owner-ship-farmland', 'M\ClientController@getOwnerShipFarmland');

Route::get('/get-owner-ship', 'M\ClientController@getOwnerShip');
Route::post('/get-owner-ship', 'M\ClientController@getOwnerShip');


Route::get('/get-transaction-type', 'M\LoanController@getTransactionType');
Route::post('/get-transaction-type', 'M\LoanController@getTransactionType');

Route::get('/get-group-loan', 'M\ClientController@getGroupLoan');
Route::post('/get-group-loan', 'M\ClientController@getGroupLoan');


Route::post('/get-disbursement-number', 'M\LoanController@getDisbursementNumber');
Route::post('/get-loan', 'M\LoanController@getLoan');
Route::get('/get-loan', 'M\LoanController@getLoan');

Route::get('/get-repayment', 'M\LoanController@getRepayment');
Route::post('/get-repayment', 'M\LoanController@getRepayment');


Route::post('/get-cash', 'M\PaymentController@getCash');
Route::get('/get-cash', 'M\PaymentController@getCash');
Route::get('/get-loan-calculate', 'M\PaymentController@getLoanCalculate');


Route::post('/get-payment-number', 'M\LoanController@getPaymentNumber');

Route::post('/get-payment-show', 'M\LoanController@payment');
Route::get('/get-payment-show', 'M\LoanController@payment');


Route::post('/summary-reports', 'M\LoanController@summaryReport');
Route::get('/summary-reports', 'M\LoanController@summaryReport');



Route::post('/disbursement-list', 'M\LoanController@disbursementList');
Route::get('/disbursement-list', 'M\LoanController@disbursementList');

Route::post('/disbursement-client-confirm-list', 'M\LoanController@disbursementClientConfirmList');
Route::get('/disbursement-client-confirm-list', 'M\LoanController@disbursementClientConfirmList');

Route::post('/generate-loan-calculate', 'M\LoanController@getLoanCalculation');
Route::get('/generate-loan-calculate', 'M\LoanController@getLoanCalculation');

Route::post('/store-due-payment', 'M\LoanController@storeDuePayment');
Route::get('/store-due-payment', 'M\LoanController@storeDuePayment');

Route::post('/cancel-due-payment', 'M\LoanController@cancelDuePayment');
Route::get('/cancel-due-payment', 'M\LoanController@cancelDuePayment');

Route::post('/client-confirm', 'M\LoanController@clientConfirm');
Route::get('/client-confirm', 'M\LoanController@clientConfirm');


Route::post('/get-loan-paid-repayment', 'M\PaymentController@getLoanPaidRepayment');
Route::get('/get-loan-paid-repayment', 'M\PaymentController@getLoanPaidRepayment');

Route::post('/confirm-payment', 'M\PaymentController@confirmPayment');
Route::get('/confirm-payment', 'M\PaymentController@confirmPayment');


Route::post('/get-loan-calculator', 'M\LoanController@getLoanCalculation');
Route::get('/get-loan-calculator', 'M\LoanController@getLoanCalculation');


Route::post('/store-client-pending', 'M\LoanController@apply_loan');
Route::get('/store-client-pending', 'M\LoanController@apply_loan');



Route::post('/get-branch-code', 'M\ClientController@getBranchCode');
Route::get('/get-branch-code', 'M\ClientController@getBranchCode');

Route::post('upload-client-image-and-nrc',function (Request $request) {
    $id = $request->id;
    $new_name = rand() . '.png';
    // $f = "uploads/images/clients/{$new_name}";

    $f = "uploads/images/clients";
    //$fnn = 'kh'.'/'.date('Y/m/d');
    if ($request->photo_client != null && $id>0) {
        $p = $request->photo_client;
        $filename = md5($p . time()) . '.jpg';
        $image = \Image::make($p)->encode('jpg', 90);
        // dd($filename);
        if(\Illuminate\Support\Facades\Storage::disk('local_public')->put($f."/".$filename, $image->stream())){
            $m =  \App\Models\ClientPending::find($id);
            if($m != null){
                $m->photo_client = $f."/".$filename;
                $m->save();

            }
        };
    }


    $new_name = rand() . '.png';
    // $f = "uploads/images/clients/{$new_name}";

    $f = "uploads/images/clients";
    if ($request->nrc_photo != null && $id>0) {
        $p1 = $request->nrc_photo;
        $filename1 = md5($p1 . time()) . '.jpg';
        $image1 = \Image::make($p1)->encode('jpg', 90);
        if(\Illuminate\Support\Facades\Storage::disk('local_public')->put($f."/".$filename1, $image1->stream())){
            $m1 =  \App\Models\ClientPending::find($id);
            if($m1 != null){
                $m1->nrc_photo = $f."/".$filename1;
                if($m1->save()){
                    return [
                        'id'=>$m1->id
                    ];
                }
            }
        };
    }


});

Route::post('upload-client-image',function (Request $request) {

    $id = $request->id;
    $new_name = rand() . '.png';
    // $f = "uploads/images/clients/{$new_name}";

    $f = "uploads/images/clients";
    //$fnn = 'kh'.'/'.date('Y/m/d');
    if ($request->photo_client != null && $id>0) {
        $p = $request->photo_client;
        $filename = md5($p . time()) . '.jpg';
        $image = \Image::make($p)->encode('jpg', 90);
        // dd($filename);
        if(\Illuminate\Support\Facades\Storage::disk('local_public')->put($f."/".$filename, $image->stream())){
            $m =  \App\Models\ClientPending::find($id);
            if($m != null){
                $m->photo_client = $f."/".$filename;
                if($m->save()){
                    return [
                        'id'=>$m->id
                    ];
                }
            }
        };
    }

});

Route::post('upload-nrc-client-image',function (Request $request) {

    $id = $request->id;
    $new_name = rand() . '.png';
    // $f = "uploads/images/clients/{$new_name}";

    $f = "uploads/images/clients";
    if ($request->nrc_photo != null && $id>0) {
        $p1 = $request->nrc_photo;
        $filename1 = md5($p1 . time()) . '.jpg';
        $image1 = \Image::make($p1)->encode('jpg', 90);
        if(\Illuminate\Support\Facades\Storage::disk('local_public')->put($f."/".$filename1, $image1->stream())){
            $m1 =  \App\Models\ClientPending::find($id);
            if($m1 != null){
                $m1->nrc_photo = $f."/".$filename1;
                if($m1->save()){
                    return [
                        'id'=>$m1->id
                    ];
                }
            }
        };
    }
});



//Route::post('upload-page-image',function (Request $request) {
//    //return $request->all();
//    $id = $request->id;
//    $fnn = 'kh'.'/'.date('Y/m/d');
//    if ($request->photo != null && $id>0) {
//        $p = $request->photo;
//        $new_name = rand() . '.png';
//        $f = "uploads/pages/{$fnn}/{$new_name}";
//        if(Storage::disk('dropbox')->put($f, $p)){
//            $m =  \App\Models\TripPlaceApi::find($id);
//            if($m != null){
//                $arr = [];
//                $photo_album = $m->photo_album;
//                if($photo_album != null && is_string($photo_album)){
//                    if($photo_album != '') {
//                        $arr = json_decode($photo_album);
//                    }
//                }else if($photo_album != null && is_array($photo_album)){
//                    $arr = $photo_album;
//                }
//                $arr[] = $f;
//                $m->photo_album = $arr;
//                if($m->save()){
//                    return ['id'=>$m->id];
//                }
//            }
//        };
//    }
//    return ['id'=>0];
//});


Route::get('/create-loan-view', 'M\LoanController@createLoanView');
Route::post('/store-loan-view', 'M\LoanController@store_loan_view');

Route::post('/get-loan-detail', 'M\LoanController@loan_detail');
Route::get('/get-loan-detail', 'M\LoanController@loan_detail');

Route::get('/get-customer-group', 'M\ClientController@getCustomerGroup');
Route::post('/get-customer-group', 'M\ClientController@getCustomerGroup');


Route::get('/get-address','M\ClientController@getAddress');
Route::post('/get-address','M\ClientController@getAddress');


Route::post('/get-loan-pending', 'M\LoanDepositController@getLoanPending');
Route::get('/get-loan-pending', 'M\LoanDepositController@getLoanPending');

Route::post('/store-deposit', 'M\LoanDepositController@storeDeposit');
Route::get('/store-deposit', 'M\LoanDepositController@storeDeposit');

Route::post('/store-disbursement', 'M\LoanDisbursementController@storeDisbursement');
Route::get('/store-disbursement', 'M\LoanDisbursementController@storeDisbursement');

Route::post('/get-loan-approved', 'M\LoanDisbursementController@getLoanApproved');
Route::get('/get-loan-approved', 'M\LoanDisbursementController@getLoanApproved');

Route::get('/get-loan-deposit', 'M\LoanDepositController@getLoanDeposit');
Route::post('/get-loan-deposit', 'M\LoanDepositController@getLoanDeposit');

Route::get('/get-loan-disbursement', 'M\LoanDisbursementController@getLoanDisbursement');
Route::post('/get-loan-disbursement', 'M\LoanDisbursementController@getLoanDisbursement');