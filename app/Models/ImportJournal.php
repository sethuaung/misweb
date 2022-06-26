<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DateTime;

class ImportJournal implements ToModel, WithHeadingRow
{


    public $tran_type;

    function __construct($tran_type)
    {
        $this->tran_type = $tran_type;
    }


    public function model(array $row)
    {
        //dd($row);
        //$user_id = auth()->user()->id;
        //$branch_id = UserBranch::where('user_id',$user_id)->pluck('branch_id')->first();

        if ($row != null) {
            //$arr = [];

            $branch_code = trim($row['branch_code']);
            $reference_no = isset($row['reference_no']) ? $row['reference_no'] : 0;
            $coa_debit_code = isset($row['coa_debit']) ? trim($row['coa_debit']) : '';
            $coa_credit_code = isset($row['coa_credit']) ? trim($row['coa_credit']) : '';
            $amount_debit = isset($row['amount_debit']) ? $row['amount_debit'] : 0;
            $amount_credit = isset($row['amount_credit']) ? $row['amount_credit'] : 0;
            $date = isset($row['date']) ? $row['date'] : date('Y-m-d');
            $note = $row['descriptions'];
            $staff_id = $row['staff_id'];
            $staff_name = $row['staff_name'];
            $tran_type = $this->tran_type;
            //dd($tran_type);
            //dd($branch_code);
            //$user_id =0;

            //dd($amount_credit);


            $m = GeneralJournal::where('reference_no', trim($row['reference_no']))->where('tran_type', $tran_type)->first();
            $u = UserU::where('user_code', trim($row['staff_id']))->first();
            $b = Branch::where('code', trim($row['branch_code']))->first();

            ///dd($row['reference_no']);
            $coa_credit = AccountChart::where('code', $coa_credit_code)->first();
            $coa_debit = AccountChart::where('code', $coa_debit_code)->first();
            $credit_section_id = optional($coa_credit)->section_id;
            $debit_section_id = optional($coa_debit)->section_id;

            $credit_acc_id = optional($coa_credit)->id;
            $debit_acc_id = optional($coa_debit)->id;

            if ($u != null) {
                $user_id = $u->id;
            } else {
                $user = new  UserU();
                $user->name = $staff_name;
                $user->user_code = $staff_id;
                $user->password = bcrypt('123456789');
                if ($user->save()) {
                    $user_id = $user->id;
                };
            }
            //dd($m);
            if ($m != null) {

                $g_d = new GeneralJournalDetailImport();
                //$g_d->name='journal';
                $g_d->section_id = $amount_credit > 0 ? $credit_section_id : $debit_section_id;
                $g_d->journal_id = $m->id;
                $g_d->currency_id = 0;
                $g_d->acc_chart_id = $amount_credit > 0 ? $credit_acc_id : $debit_acc_id;
                $g_d->dr = $amount_debit;
                $g_d->cr = $amount_credit;
                $g_d->j_detail_date = $m->date_general;
                $g_d->description = $note;
                $g_d->tran_type = $tran_type;
                $g_d->created_by = $user_id;
                $g_d->updated_by = $user_id;
                $g_d->branch_id = $b->id ?? 0;
                $g_d->external_acc_id = $amount_credit > 0 ? $credit_acc_id : $debit_acc_id;
                $g_d->acc_chart_code = $amount_credit > 0 ? $coa_credit_code : $coa_debit_code;
                $g_d->external_acc_chart_id = $amount_credit > 0 ? $credit_acc_id : $debit_acc_id;
                $g_d->external_acc_chart_code = $amount_credit > 0 ? $coa_credit_code : $coa_debit_code;

                $g_d->save();
            } else {
                //dd($date);

                $UNIX_DATE = ($date - 25569) * 86400;
                $datetodb = gmdate("Y-m-d", $UNIX_DATE);

                $g = new GeneralJournalImport();
                //dd($datetodb);
                $g->date_general = $datetodb;
                $g->tran_type = $tran_type;
                $g->reference_no = $reference_no;
                $g->note = $note;
                $g->created_by = $user_id;
                $g->updated_by = $user_id;


                if ($g->save()) {
                    $g_d = new GeneralJournalDetail();
                    //$g_d->name='journal';
                    $g_d->section_id = $amount_credit > 0 ? $credit_section_id : $debit_section_id;
                    $g_d->journal_id = $g->id;
                    $g_d->currency_id = 0;
                    $g_d->acc_chart_id = $amount_credit > 0 ? $credit_acc_id : $debit_acc_id;
                    $g_d->dr = $amount_debit;
                    $g_d->cr = $amount_credit;
                    $g_d->j_detail_date = $g->date_general;
                    $g_d->description = $note;
                    $g_d->tran_type = $tran_type;
                    $g_d->created_by = $user_id;
                    $g_d->updated_by = $user_id;
                    $g_d->branch_id = $b->id ?? 0;
                    $g_d->external_acc_id = $amount_credit > 0 ? $credit_acc_id : $debit_acc_id;
                    $g_d->acc_chart_code = $amount_credit > 0 ? $coa_credit_code : $coa_debit_code;
                    $g_d->external_acc_chart_id = $amount_credit > 0 ? $credit_acc_id : $debit_acc_id;
                    $g_d->external_acc_chart_code = $amount_credit > 0 ? $coa_credit_code : $coa_debit_code;


                    $g_d->save();


                }
            }


        }
    }

//    public function headingRow(): int
//    {
//        return 1;
//    }


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
