<?php

namespace App\Http\Controllers\Admin;
use App\Helpers\MFS;
use App\Models\Client;
use App\Models\Branch;
use App\Models\CompulsorySavingTransaction;
use App\Models\GeneralJournal;
use App\Models\GeneralJournalDetail;
use App\Models\Loan;
use App\Models\Loan2;
use App\Models\LoanCalculate;
use App\Models\LoanDeposit;
use App\Models\LoanPayment;
use App\Models\PaidDisbursement;
use App\Models\PaymentHistory;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Carbon\Carbon;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\LoanOutstandingRequest as StoreRequest;
use App\Http\Requests\LoanOutstandingRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

/**
 * Class LoanOutstandingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ActivatedLoanItemCrudController extends CrudController
{

    public function paymentPop(Request $request){

        //dd($request->all());
        $loan_id = $request->loan_id;
        $row = Loan::find($loan_id);
        //dd($row);
        return view ('partials.loan-payment.loan-payment-pop',['row'=>$row]);
    }

    public function showDetailsRow($id)
    {
        //$m_ = ProductSerial::where('product_id', $id)->get();
//        dd($m_);
        $row = Loan::find($id);
        // dd($row);
        return view('partials.loan_disbursement.loan_outstanding_payment', ['row' => $row]);
    }


    public function updateLoanStatus(Request $request)
    {
        $id = $request->id;
        $m = Loan::find($id);
        $m->status_note_approve = $request->status_note_approve;
        $m->status_note_date_approve = $request->status_note_date_approve;
        $m->disbursement_status = $request->disbursement_status;

        $m->save();
    }

    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\ActivatedLoanItem');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/loan-item-activated');
        $this->crud->setEntityNameStrings('loan activated', 'loan activated');
        $this->crud->disableResponsiveTable();
        $this->crud->setListView('partials.loan_disbursement.payment-loan');
        $this->crud->enableExportButtons();
        $this->crud->orderBy(getLoanTable().'.id','DESC');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        if(companyReportPart() == 'company.mkt'){
            $this->crud->addClause('where', getLoanTable().'.branch_id', session('s_branch_id'));
        }



        include('loan_item_inc.php');
        $this->crud->addClause('selectRaw', getLoanTable().'.*');
        $this->crud->enableDetailsRow();
        $this->crud->allowAccess('disburse_details_row');
        $this->crud->denyAccess(['create', 'update', 'delete', 'clone']);
        //$this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning');
        // add asterisk for fields that are required in LoanOutstandingRequest
        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning'); // add a button whose HTML is returned by a method in the CRUD model

        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();

    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'loan-outstanding';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }

        // Allow create access


        /*
        if (_can2($this,'create-'.$fname)) {
            $this->crud->allowAccess('create');
        }

        // Allow update access
        if (_can2($this,'update-'.$fname)) {
            $this->crud->allowAccess('update');
        }

        // Allow delete access
        if (_can2($this,'delete-'.$fname)) {
            $this->crud->allowAccess('delete');
        }


        if (_can2($this,'clone-'.$fname)) {
            $this->crud->allowAccess('clone');
        }

        */

    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry

        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
    public function firstdate(){
        $loan_id = ($_GET['id']);
        $loan = \App\Models\Loan::where('id',$loan_id)->first();
        $disbursement = \App\Models\PaidDisbursement::where('contract_id',$loan_id)->first();
        $general_journal = \App\Models\GeneralJournal::where('reference_no',optional($disbursement)->reference)->first();
        //dd($disbursement->reference);
        $journal_details = \App\Models\GeneralJournalDetail::where('journal_id',optional($general_journal)->id)->get();
        // dd($journal_details);
        $first_date = $_GET['firstDate'];
        $repayment_date = $_GET['repaymentDate'];
        $deposit_date = '';
        if(isset($_GET['DepositDate'])){
            $deposit_date = $_GET['DepositDate'];
        }

        if($repayment_date != ""){
            $re_date = Carbon::parse($repayment_date)->format('Y-m-d');
            $loan->status_note_date_activated = $re_date;
            $loan->save();
            $disbursement->paid_disbursement_date = $re_date;
            $disbursement->save();
            $general_journal->date_general = $re_date;
            $general_journal->save();
            foreach($journal_details as $journal_detail){
                // dd($journal_detail->j_detail_date);
                $journal_detail->j_detail_date = $re_date;
                $journal_detail->save();
            }
            // $journal_details->save();
        }
        if($first_date != ""){
            $fr_date = Carbon::parse($first_date)->format('Y-m-d');
            $loan->first_installment_date = $fr_date;
            $loan->save();
            $loanId = \App\Models\LoanCalculate::where('disbursement_id',$loan_id)->first();

            if(optional($loanId)->total_p){
                return response('Paid Loan', 200);
            }
            else{
                \App\Models\LoanCalculate::where('disbursement_id',$loan_id)->delete();

                    //dd($loan_id->id);
                  $obj=Loan::find($loan_id);
                  $date = optional($obj)->loan_application_date;
                  $first_date_payment = optional($obj)->first_installment_date;
                  $loan_product = \App\Models\LoanProduct::find(optional($obj)->loan_production_id);
                  $interest_method = optional($loan_product)->interest_method;
                  $principal_amount = optional($obj)->loan_amount;
                  $loan_duration = optional($obj)->loan_term_value;
                  $loan_duration_unit = optional($obj)->loan_term;
                  $repayment_cycle = optional($obj)->repayment_term;
                  $loan_interest = optional($obj)->interest_rate;
                  $loan_interest_unit = optional($obj)->interest_rate_period;
                  $i = 1;

                 /* $repayment = MFS::getRepaymentSchedule($date, $first_date_payment, $interest_method,
                      $principal_amount, $loan_duration, $loan_duration_unit, $repayment_cycle, $loan_interest, $loan_interest_unit);*/
                  //dd($repayment);


                  $monthly_base = optional($loan_product)->monthly_base??'No';
                  $repayment = $monthly_base== 'No' ?MFS::getRepaymentSchedule($date,$first_date_payment,$interest_method,
                      $principal_amount,$loan_duration,$loan_duration_unit,$repayment_cycle,$loan_interest,$loan_interest_unit):
                      MFS::getRepaymentSchedule2($monthly_base,$date,$first_date_payment,$interest_method,
                          $principal_amount,$loan_duration,$loan_duration_unit,$repayment_cycle,$loan_interest,$loan_interest_unit);

                    //dd($repayment);
                  if ($repayment != null) {
                      if (is_array($repayment)) {
                          foreach ($repayment as $r) {
                              $d_cal = new LoanCalculate();

                              $d_cal->no = $i++;
                              $d_cal->day_num = $r['day_num'];
                              $d_cal->disbursement_id = optional($obj)->id;
                              $d_cal->date_s = $r['date'];
                              $d_cal->principal_s = $r['principal'];
                              $d_cal->interest_s = $r['interest'];
                              $d_cal->penalty_s = 0;
                              $d_cal->service_charge_s = 0;
                              $d_cal->total_s = $r['payment'];
                              $d_cal->balance_s = $r['balance'];
                              $d_cal->branch_id = optional($obj)->branch_id;
                              $d_cal->group_id = optional($obj)->group_loan_id;
                              $d_cal->center_id = optional($obj)->center_leader_id;
                              $d_cal->loan_product_id = optional($obj)->loan_production_id;
                              $d_cal->save();
                          }
                      }
                  }

            }
        };
        if($deposit_date != ''){
            $de_date = Carbon::parse($deposit_date)->format('Y-m-d');
            $deposit = \App\Models\LoanDeposit::where('applicant_number_id',$loan_id)->first();
            if(!isset($deposit)){
                return response('No Deposit Date', 200);
            }
            else{
            $general_journal = \App\Models\GeneralJournal::where('reference_no',optional($deposit)->referent_no)->where('tran_type','loan-deposit')->first();
            $journal_details = \App\Models\GeneralJournalDetail::where('journal_id',optional($general_journal)->id)->get();
            //dd($journal_details);
            $deposit->loan_deposit_date = $de_date;
            $deposit->save();
            $general_journal->date_general = $de_date;
            $general_journal->save();
            foreach($journal_details as $journal_detail){
                // dd($journal_detail->j_detail_date);
                $journal_detail->j_detail_date = $de_date;
                $journal_detail->save();
            }
        }
        }
    }
    public function changedate(){
        $id = $_GET['id'];
        $date = $_GET['changeDate'];
        $date_s = Carbon::parse($date)->format('Y-m-d 00:00:00.0');
        //dd($date_s);
        $loan = \App\Models\LoanCalculate::where('id',$id)->first();
        if($date != ""){
            $loan->date_s = $date_s;
            $loan->save();
        }else{
            return response('Error', 200);
        }
    }
    public function clientOptions(Request $request) {

        $term = $request->input('term');
        $options = Client::where('name', 'like', '%'.$term.'%')->get()->pluck('name', 'id');
        return $options;
    }
    public function view_payment(Request $request){
        $schedule_id = $request->schedule_id;

        $payments = PaymentHistory::where('schedule_id',$schedule_id)->get();
        $all_payments = PaymentHistory::where('loan_id',$request->loan_id)->get();
        //dd($payment);
        return view('partials.loan-payment.view-repayment',['payments'=>$payments,'all_payments'=> $all_payments]);
    }

    public function cancel_activated(Request $request){


        $id = $request->id;
        $m = Loan2::find($id);
        if($m != null){
            $m->disbursement_status = 'Canceled';
            $m->cancel_date = date('Y-m-d');
            $m->remark = $request->remark;
            if ($m->save()){
                $paid_disbursement=PaidDisbursement::where('contract_id',$id)->first();
                if ($paid_disbursement != null){
                    GeneralJournal::where('tran_id',$paid_disbursement->id)->where('tran_type','loan-disbursement')->delete();
                    GeneralJournalDetail::where('tran_id',$paid_disbursement->id)->where('tran_type','loan-disbursement')->delete();
                    CompulsorySavingTransaction::where('tran_id',$paid_disbursement->id)->where('train_type_ref','disbursement')->delete();
                }
                PaidDisbursement::where('contract_id',$id)->delete();
                $paid_dis_deposit=LoanDeposit::where('applicant_number_id',$id)->first();
                if ($paid_dis_deposit != null){
                    $m->deposit_paid = 'No';
                    $m->save();
                    GeneralJournal::where('tran_id',$paid_dis_deposit->id)->where('tran_type','loan-deposit')->delete();
                    GeneralJournalDetail::where('tran_id',$paid_dis_deposit->id)->where('tran_type','loan-deposit')->delete();
                    CompulsorySavingTransaction::where('tran_id',$paid_dis_deposit->id)->where('train_type','deposit')->delete();
                }
                LoanDeposit::where('applicant_number_id',$id)->delete();
                $loan_payment=LoanPayment::where('disbursement_id',$id)->get();
                foreach ($loan_payment as $p){
                    GeneralJournal::where('tran_id',$p->id)->where('tran_type','payment')->delete();
                    GeneralJournalDetail::where('tran_id',$p->id)->where('tran_type','payment')->delete();
                    CompulsorySavingTransaction::where('tran_id',$p->id)->where('train_type_ref','saving')->delete();
                }
                LoanPayment::where('disbursement_id',$id)->delete();

                $payment_histories = PaymentHistory::where('loan_id', $id)->get();
                foreach($payment_histories as $payment_history){
                    $payment_history->delete();
                }


                LoanCalculate::where('disbursement_id',$id)
                    ->update([
                       'principal_p' => 0,
                        'interest_p' => 0,
                        'penalty_p' => 0,
                        'service_charge_p' => 0,
                        'total_p' => 0,
                        'balance_p' => 0,
                        'owed_balance_p' => 0,
                        'payment_status' => 'pending',
                        'over_days_p' => 0,
                        'principle_pd' => 0,
                        'interest_pd' => 0,
                        'total_pd' => 0,
                        'penalty_pd' => 0,
                        'payment_pd' => 0,
                        'service_pd' => 0,
                        'compulsory_pd' => 0,
                        'compulsory_p' => 0,
                        'count_payment' => 0,
                        'exact_interest' => 0,
                        'charge_schedule' => 0,
                        'compulsory_schedule' => 0,
                        'total_schedule' => 0,
                        'balance_schedule' => 0,
                        'penalty_schedule' => 0,
                    ]);
            }
        }


         return redirect()->back();

    }

    public function rollbackCompleted(Request $request){
        $id = $request->id;
        $m = Loan2::find($id);

        if($m != null){
            $m->disbursement_status = "Activated";
            // $m->remark = $request->remark;
            if ($m->save()){
                    $last_schedule = LoanCalculate::where('disbursement_id', $id)->orderBy('no', 'desc')->first();

                    LoanCalculate::where('id', $last_schedule->id)->update([
                        'principal_p' => 0,
                        'interest_p' => 0,
                        'penalty_p' => 0,
                        'service_charge_p' => 0,
                        'total_p' => 0,
                        'balance_p' => 0,
                        'owed_balance_p' => 0,
                        'payment_status' => 'pending',
                        'over_days_p' => 0,
                        'principle_pd' => 0,
                        'interest_pd' => 0,
                        'total_pd' => 0,
                        'penalty_pd' => 0,
                        'payment_pd' => 0,
                        'service_pd' => 0,
                        'compulsory_pd' => 0,
                        'compulsory_p' => 0,
                        'count_payment' => 0,
                        'exact_interest' => 0,
                        'charge_schedule' => 0,
                        'compulsory_schedule' => 0,
                        'total_schedule' => 0,
                        'balance_schedule' => 0,
                        'penalty_schedule' => 0,
                    ]);

                $histories = PaymentHistory::where('schedule_id', $last_schedule->id)->get();

                foreach($histories as $history){
                   $payment = LoanPayment::where('id',$history->payment_id)->where('disbursement_id', $id)->first();

                   $gen_jour_ds = GeneralJournalDetail::where('tran_id', $history->payment_id)->where('tran_type','payment')->get();

                   $gen_jour = GeneralJournal::where('id', $gen_jour_ds[0]->journal_id)->where('reference_no', $payment->payment_number)->first();
                   CompulsorySavingTransaction::where('tran_id', $history->payment_id)->where('train_type_ref','saving')->delete();

                   foreach($gen_jour_ds as $gen_jour_d){
                    $gen_jour_d->delete();
                   }
                   $payment->delete();
                   $gen_jour->delete();
                   $history->delete();
                }
            }
        }
        return redirect()->back();
    }

}
