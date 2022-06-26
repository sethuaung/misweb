<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\IDate;
use App\Helpers\MFS;
use App\Helpers\UnitDay;
use App\Models\Client;
use App\Models\Branch;
use App\Models\CenterLeader;
use App\Models\CompulsorySavingTransaction;
use App\Models\DepositSaving;
use App\Models\GroupLoan;
use App\Models\Guarantor;
use App\Models\LoanCharge;
use App\Models\LoanCompulsory;
use App\Models\Loan;
use App\Models\LoanCalculate;
use App\Models\LoanProduct;
use App\Models\Saving;
use App\Models\SavingProduct;
use App\Models\SavingSchedule;
use App\Models\SavingTransaction;
use App\Models\SavingWithdrawal;
use App\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SavingWithdrawalRequest as StoreRequest;
use App\Http\Requests\SavingWithdrawalRequest as UpdateRequest;
use App\Models\GeneralJournal;
use App\Models\GeneralJournalDetail;
use App\Models\SavingAccrueInterests;
use App\Models\SavingCompoundInterest;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;

/**
 * Class LoanCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */


class SavingWithdrawalCrudController extends CrudController
{

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\SavingWithdrawal');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/saving-withdrawal');
        $this->crud->setEntityNameStrings('Withdrawal', 'Withdrawals');



        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns


        $this->crud->addField([
            'name' => 'custom-ajax-saving',
            'type' => 'view',
            'view' => 'partials/saving/custom_saving_ajax',

        ]);


        $this->crud->addField(
            [
                'label' => _t('Saving ID'),
                'type' => "select2_from_ajax_saving",
                'name' => 'saving_id', // the column that contains the ID of that connected entity
                'entity' => 'savings', // the method that defines the relationship in your Model
                'attribute' => "saving_number", // foreign key attribute that is shown to user
                'model' => Saving::class, // foreign key model
                'data_source' => url("api/get-saving"), // url to controller search function (with /{id} should return model)
                'placeholder' => _t("Select a saving code"), // placeholder for the select
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4 saving_id'
                ],
            ]
        );

        $this->crud->addField([
            'label' => _t('Reference'),
            'name' => 'reference',
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);

        $this->crud->addField([
            'label' => _t('Saving Type'),
            'name' => 'saving_type',
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);

        $this->crud->addField([
            'label' => _t('Client Name'),
            'name' => 'client_name',
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);

        $this->crud->addField([
            'label' => _t('Available Balance'),
            'name' => 'available_balance',
            'type' => 'text_read',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],

        ]);

        $this->crud->addField([
            'label' => _t('date'),
            'name' => 'date',
            'type' => 'date_picker',
            'default' => date('Y-m-d'),
            'date_picker_options' => [
                'format' => 'yyyy-mm-dd',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
        ]);

        $this->crud->addField([
            'label' => _t('Withdrawal'),
            'name' => 'amount',
            'type' => 'number',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            //  'tab' => _t('Client'),
//            'location_group' => 'General',
        ]);



        $this->crud->addField([
            'label' => _t('Cash Out'), // Table column heading
            'type' => "select2_from_ajax_coa",
            'name' => 'cash_out_id', // the column that contains the ID of that connected entity
            'data_source' => url("api/account-chart"), // url to controller search function (with /{id} should return model)
            'placeholder' => "Select an account", // placeholder for the select
            'minimum_input_length' => 0,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->addField([
            'label' => _t('Note'), // Table column heading
            'type' => "text",
            'name' => 'note', // the column that contains the ID of that connected entity
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ]
        ]);

        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->addButtonFromModelFunction('line', 'addButtonCustom', 'addButtonCustom', 'beginning'); // add a button whose HTML is returned by a method in the CRUD model

        $this->setPermissions();

    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'delete', 'clone']);

        $fname = 'saving-withdrawal';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }

        // Allow create access
        if (_can2($this,'create-'.$fname)) {
            $this->crud->allowAccess('create');
        }

        // Allow update access
       /* if (_can2($this,'update-'.$fname)) {
            $this->crud->allowAccess('update');
        }

        // Allow delete access
        if (_can2($this,'delete-'.$fname)) {
            $this->crud->allowAccess('delete');
        }*/


//        if (_can2($this,'clone-'.$fname)) {
//            $this->crud->allowAccess('clone');
//        }

    }

    public function store(StoreRequest $request)
    {

        $total_avai_balance = $request->available_balance;
        $total_amount = $request->amount;

        if ($total_amount > $total_avai_balance){

            return redirect()->back()->withErrors('Can\'t withdraw this amount!');
        }
        else{
            $saving = Saving::find($request->saving_id);
            if($saving->interest_compound == "6 Months Fixed" || $saving->interest_compound == "9 Months Fixed" || $saving->interest_compound == "12 Months Fixed"){
                $check_compound_interest = SavingCompoundInterest::where('saving_id',$saving->id)->get();
                if ($total_amount != $total_avai_balance){

                    return redirect()->back()->withErrors('Can\'t withdraw this amount!');
                }
                else if(companyReportPart() == "company.moeyan" && count($check_compound_interest) < 1){

                    $redirect_location = parent::storeCrud($request);
                    $row = $this->crud->entry;

                    SavingWithdrawal::accSavingWithdrawal($row);
                    SavingWithdrawal::saveTransaction($row);
                    SavingWithdrawal::accSavingInterest($row);
                    return redirect('admin/saving-withdrawal/create');
                }
                else{
                    $redirect_location = parent::storeCrud($request);
                    $row = $this->crud->entry;

                    SavingWithdrawal::accSavingWithdrawal($row);
                    SavingWithdrawal::saveTransaction($row);
                    return redirect('admin/saving-withdrawal/create');
                }
            }else{
                $redirect_location = parent::storeCrud($request);
                $row = $this->crud->entry;

                SavingWithdrawal::accSavingWithdrawal($row);
                SavingWithdrawal::saveTransaction($row);
                return redirect('admin/saving-withdrawal/create');
            }
        }

    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

}
