<?php

namespace App\Http\Controllers\Admin;

use App\CustomerGroup;
use App\Models\ClientPending;
use App\Models\EmployeeStatus;
use App\Helpers\MyEnum;
use App\Models\Client;
use App\Models\Guarantor;
use App\Models\Ownership;
use App\Models\OwnershipFarmland;
use App\Models\Survey;
use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ClientRequest as StoreRequest;
use App\Http\Requests\ClientRequest as UpdateRequest;
use DateTime;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Shared\Date;


/**
 * Class ClientCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ActiveMemberClientCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Client');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/active-member-client');
        $this->crud->setEntityNameStrings('Active Member', 'Active Members');

        $this->crud->enableExportButtons();
        //$this->crud->allowAccess('show');
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addClause('where','customer_group_id','=',1);
        $this->crud->addClause('where','clients.branch_id',session('s_branch_id'));

        $client_pending_id = request()->client_pending_id??0;
        $create_client = request()->create_client??'';
        $m= ClientPending::find($client_pending_id);




        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'client_number',
            'label'=> _t("Client ID")
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'client_number', 'LIKE', '%'.$value.'%');
            }
        );

        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'name',
            'label'=> _t("Full Name")
        ],
        false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'name', 'LIKE', '%'.$value.'%');
                $this->crud->addClause('orWhere', 'name_other', 'LIKE', '%'.$value.'%');
            }
        );

        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'nrc_number',
            'label'=> _t("nrc_number")
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'nrc_number', 'LIKE', '%'.$value.'%');
            }
        );

        if (companyReportPart() == 'company.quicken') {
            $this->crud->addFilter([ // simple filter
                'type' => 'dropdown',
                'name' => 'condition',
                'label'=> _t("condition"),
                ], [
                    'Waiting Client' => 'Waiting Client',
                    'Have Loan' => 'Have Loan',
                ],
                function($value) { // if the filter is active
                    $this->crud->addClause('where', 'condition', 'LIKE', '%'.$value.'%');
                }
            );
        }
        if (companyReportPart() != 'company.mkt'){
        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'branch_id',
            'type' => 'select2_ajax',
            'label'=> 'Branch',
            'placeholder' => 'Pick a branch'
        ],
            url('api/branch-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'branch_id', $value);
            });
        }
        if (companyReportPart() != 'company.bolika'){
            $this->crud->addFilter([ // select2_ajax filter
                'name' => 'center_leader_id',
                'type' => 'select2_ajax',
                'label'=> 'Center',
                'placeholder' => 'Pick a center'
            ],
                url('api/center-option'), // the ajax route
                function($value) { // if the filter is active
                    $this->crud->addClause('where', 'center_leader_id', $value);
                });
        }

        $this->crud->addFilter([ // select2_ajax filter
            'name' => 'customer_group_id',
            'type' => 'select2_ajax',
            'label'=> 'Client Type',
            'placeholder' => 'Pick a type'
        ],
            url('api/customer-option'), // the ajax route
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'customer_group_id', $value);
            });

        $this->crud->addFilter([ // daterange filter
            'type' => 'date_range',
            'name' => 'register_date',
            'label'=> 'Date'
        ],
            false,
            function($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'register_date', '>=', $dates->from);
                $this->crud->addClause('where', 'register_date', '<=', $dates->to . ' 23:59:59');
            });


        $this->crud->addColumn([
            'name' => 'client_number',
            'label' => _t('Client Number'),
        ]);

        if (companyReportPart() == 'company.bolika'){
            $this->crud->addColumn([
                'name' => 'name',
                'label' => _t('First Name'),
            ]);
        }else{
            $this->crud->addColumn([
                'name' => 'name',
                'label' => _t('Full Name (English)'),
            ]);
        }

        if (companyReportPart() == 'company.bolika'){
            $this->crud->addColumn([
                'name' => 'name_other',
                'label' => _t('Last Name'),
            ]);
        }else{
            $this->crud->addColumn([
                'name' => 'name_other',
                'label' => _t('Full Name (Myanmar)'),
            ]);
        }



        $this->crud->addColumn([
            'label' => _t('Branch Name/ID'),
            'type' => "select",
            'name' => 'branch_id', // the column that contains the ID of that connected entity;
            'entity' => 'branch_name', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Branch", // foreign key model
        ]);

        if (companyReportPart() != 'company.bolika'){
            $this->crud->addColumn([
                'label' => _t('Center Name/ID'),
                'type' => "select",
                'name' => 'center_leader_id', // the column that contains the ID of that connected entity;
                'entity' => 'center_leader', // the method that defines the relationship in your Model
                'attribute' => "title", // foreign key attribute that is shown to user
                'model' => "App\\Models\\CenterLeader", // foreign key model
            ]);
        }

        $this->crud->addColumn([
            'label' => _t('Customer Type'),
            'type' => "select",
            'name' => 'customer_group_id', // the column that contains the ID of that connected entity
            'entity' => 'customer_group_name', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\\Models\\CustomerGroup", // foreign key model

        ]);

        $this->crud->addColumn([
            'label' => _t("Entry"), // Table column heading
            'type' => "select",
            'name' => 'updated_by', // the column that contains the ID of that connected entity;
            'entity' => 'updated_by_user', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Client", // foreign key model
        ]);


        $this->crud->addColumn([
            'name' => 'officer_name.name',
            'label' => _t('Loan Officer'),
        ]);

        $this->crud->addColumn([
            'name' => 'primary_phone_number',
            'label' => _t('phone'),
        ]);


        $this->crud->addColumn([
            'name' => 'nrc_number',
            'label' => _t('nrc number'),
        ]);
        $this->crud->addColumn([
            'name' => 'register_date',
            'label' => _t('register_date'),
            'type' => 'date'
        ]);

        $this->crud->addColumn([
            'name' => 'created_at',
            'label' => _t('register_time'),
            'type' => 'datetime'
        ]);

        $this->crud->addColumn([
            'name' => 'updated_at',
            'label' => _t('last_update'),
            'type' => 'datetime'
        ]);


        /**************************
         * add fields
         */

        /**
         * personal details
         */


        // dd(session('s_branch_id'));


        $this->crud->addField([
            'type' => "hidden",
            'name' => 'client_pending_id',
            'default' =>optional($m)->id,
        ]);


        $this->crud->addField([
            'label' => _t('Branch'),
            'type' => "select2_from_ajax",
            'default' => session('s_branch_id'),
            'name' => 'branch_id', // the column that contains the ID of that connected entity
            'entity' => 'branch_name', // the method that defines the relationship in your Model
            'attribute' => "title", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Branch", // foreign key model
            'data_source' => url("api/get-branch"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select a branch"), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'tab' => _t('personal_detail'),
//            'location_group' => 'General',
        ]);

        if (companyReportPart() != 'company.bolika'){
            $this->crud->addField([
                'label' => _t('Center'),
                'type' => "select2_from_ajax_center",
                'name' => 'center_leader_id', // the column that contains the ID of that connected entity
                'entity' => 'center_leader', // the method that defines the relationship in your Model
                'attribute' => "title", // foreign key attribute that is shown to user
                'model' => "App\\Models\\CenterLeader", // foreign key model
                'data_source' => url("/api/get-center-leader-name"), // url to controller search function (with /{id} should return model)
                'placeholder' => _t("Select a center leader name"), // placeholder for the select
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'tab' => _t('personal_detail'),
//            'location_group' => 'General',
            ]);

        }

        if (companyReportPart() == 'company.bolika'){
            $this->crud->addField([
                'name' => 'nrc_number',
                'label' => _t('nrc_number *'),
                'type' => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'tab' => _t('personal_detail'),
            ]);
        }else{
            $this->crud->addField([
                'name' => 'nrc_type',
                'label' => _t('nrc_type'),
                'type' => 'enum',
                'attributes' => [
                    'id' => 'nrc_type',
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3',
                ],
                'tab' => _t('personal_detail'),
            ]);


            $this->crud->addField([
                'name' => 'nrc_number_new',
                'label' => _t('nrc_number *'),
                'type' => 'text',
                'attributes' => [
                    'id' => 'nrc_new',
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3 nrc_old'
                ],
                'tab' => _t('personal_detail'),
            ]);
            $this->crud->addField([
                'name' => 'nrc_number_old',
                'label' => _t('nrc_number *'),
                'type' => 'nrc',
                'attributes' => [
                    'id' => 'nrc_old',
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3 nrc_new'
                ],
                'tab' => _t('personal_detail'),
            ]);
        }



        $this->crud->addField([
            'name' => 'company_part',
            'label' => _t('Company Part'),
            'type' => 'hidden',
            'default' =>  companyReportPart(),
            'attributes' => [
                'id' => 'company_part',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3 nrc_new'
            ],
        ]);

        if (companyReportPart() == 'company.moeyan') {
            $this->crud->addField([
                'name' => 'client_number',
                'label' => _t('client ID'),
                'attributes' => [
                    'id' => 'client_number_moeyan',
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'tab' => _t('personal_detail'),
            ]);
        }
        else{
            $this->crud->addField([
                'label' => _t('ID Format'),
                'name' => 'id_format',
                'type' => 'enum',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'tab' => _t('personal_detail'),
            ]);

            $this->crud->addField([
                'name' => 'client_number',
                'label' => _t('client ID'),
                'default' => Client::getSeqRef('client'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'tab' => _t('personal_detail'),
            ]);
        }
        /*      $this->crud->addField([
                  'name' => 'account_number',
                  'label' => _t('account_number'),
                  'wrapperAttributes' => [
                      'class' => 'form-group col-md-3'
                  ],
                  'tab' => _t('personal_detail'),
              ]);

        */

        if (companyReportPart() == 'company.bolika'){
            $this->crud->addField([
                'name' => 'name',
                'default' => optional($m)->name,
                'label' => _t('First Name'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'tab' => _t('personal_detail'),
            ]);
        }else{
            $this->crud->addField([
                'name' => 'name',
                'default' => optional($m)->name,
                'label' => _t('Full Name (English)'),
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'tab' => _t('personal_detail'),
            ]);
        }

        if (companyReportPart() == 'company.bolika'){
            $this->crud->addField([
                'name' => 'name_other',
                'label' => _t('Last Name'),
                'default' => optional($m)->name,
                'type' => "text_mm_unicode",
                'attributes' => [
                    'id' => 'name_other',
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'tab' => _t('personal_detail'),
            ]);

        }else{
            $this->crud->addField([
                'name' => 'name_other',
                'label' => _t('Full Name (Myanmar)'),
                'default' => optional($m)->name,
                'type' => "text_mm_unicode",
                'attributes' => [
                    'id' => 'name_other',
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'tab' => _t('personal_detail'),
            ]);

        }






        /*$this->crud->addField([
            'label' => _t('Group ID'),
            'type' => "select2_from_ajax_multiple",
            'name' => 'group_loans', // the column that contains the ID of that connected entity
            'entity' => 'group_loans', // the method that defines the relationship in your Model
            'attribute' => "group_code", // foreign key attribute that is shown to user
            'model' => "App\\Models\\GroupLoan", // foreign key model
            'data_source' => url("/api/get-group-loan"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select Group Loan"), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'tab' => _t('personal_detail'),
            'pivot' => true,
//            'location_group' => 'General',
        ]);*/

        $this->crud->addField([
            'name' => 'dob',
            'label' => _t('dob'),
            'type' => 'date2',
            'default' => '1999-01-01',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'tab' => _t('personal_detail'),
        ]);

         $this->crud->addField([
             'name' => 'gender',
             'label' => _t('gender'),
             'type' => 'select_from_array',
             'options' => MyEnum::gender(),
             'allows_null' => false,
             'default' => 1,
             'wrapperAttributes' => [
                 'class' => 'form-group col-md-3'
             ],
             'tab' => _t('personal_detail'),
        ]);

        if (companyReportPart() != 'company.bolika') {
            $this->crud->addField([
                'name' => 'education',
                'label' => _t('education'),
                'type' => 'select_from_array',
                'options' => MyEnum::educationLevel(),
                'allows_null' => false,
                'default' => 1,
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'tab' => _t('personal_detail'),
            ]);
        }


        // $this->crud->addField([
        //     'name' => 'nrc_number',
        //     'label' => _t('nrc_number'),
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-md-3'
        //     ],
        //     'tab' => _t('personal_detail'),
        // ]);



        $this->crud->addField([
            'name' => 'primary_phone_number',
            'label' => _t('primary_phone_number'),
            'default' => optional($m)->phone_1,
            'type' => 'phone_11_digit',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'tab' => _t('personal_detail'),
        ]);
        $this->crud->addField([
            'name' => 'alternate_phone_number',
            'label' => _t('alternate_phone_number'),
            'default' => optional($m)->phone_2,
            'type' => 'phone_11_digit',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'tab' => _t('personal_detail'),
        ]);

        if (companyReportPart() == 'company.moeyan') {
            $this->crud->addField([
                'name' => 'optional_phone_number',
                'label' => _t('optional_phone_number'),
                'default' => optional($m)->phone_2,
                'type' => 'phone_11_digit',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'tab' => _t('personal_detail'),
            ]);
        }

        if (companyReportPart() != 'company.bolika') {
            $this->crud->addField([
                'name' => 'reason_for_no_finger_print',
                'label' => _t('reason_for_no_finger_print'),
                'type' => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'tab' => _t('personal_detail'),
            ]);
        }
        // $this->crud->addField([
        //     'name' => 'more_information',
        //     'label' => _t('more_information'),
        //     'type' => 'enum',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-md-3'
        //     ],
        //     'tab' => _t('personal_detail'),
        // ]);


        /**
         * family information
         */


        $this->crud->addField([
            'name' => 'marital_status',
            'label' => _t('marital_status'),
            'type' => 'select_from_array',
            'options' => MyEnum::maritalStatus(),
            'allows_null' => false,
            'default' => 1,
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'tab' => _t('family_information'),
        ]);

        $this->crud->addField([
            'name' => 'father_name',
            'label' => _t('father_name'),
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'tab' => _t('family_information'),
        ]);
        $this->crud->addField([
            'name' => 'husband_name',
            'label' => _t('spouse name'),
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'tab' => _t('family_information'),
        ]);
        $this->crud->addField([
            'name' => 'occupation_of_husband',
            'label' => _t('occupation_of_husband'),
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'tab' => _t('family_information'),
        ]);


        /**
         * about your family
         */
        $this->crud->addField([
            'name' => 'no_children_in_family',
            'label' => _t('no_children_in_family'),
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'tab' => _t('family_information'),
        ]);
        $this->crud->addField([
            'name' => 'no_of_working_people',
            'label' => _t('no_of_working_people'),
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'tab' => _t('family_information'),
        ]);
        if (companyReportPart() != 'company.bolika') {
            $this->crud->addField([
                'name' => 'no_of_dependent',
                'label' => _t('no_of_dependent'),
                'type' => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'tab' => _t('family_information'),
            ]);
        }
        $this->crud->addField([
            'name' => 'no_of_person_in_family',
            'label' => _t('no_of_person_in_family'),
            'type' => 'text',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'tab' => _t('family_information'),
        ]);

        if (companyReportPart() == 'company.moeyan') {
            $this->crud->addField([
                'name' => 'family_phone_number',
                'label' => _t('family_phone_number'),
                'type' => 'phone_11_digit',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'tab' => _t('family_information'),
            ]);
        }
        /**
         * more information
         */


        /**
         * leader detail
         */
        if (companyReportPart() != 'company.bolika') {
            $this->crud->addField([
                'name' => 'you_are_a_group_leader',
                'label' => _t('you_are_a_group_leader'),
                'type' => 'enum',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'tab' => _t('personal_detail'),
            ]);


            $this->crud->addField([
                'name' => 'you_are_a_center_leader',
                'label' => _t('you_are_a_center_leader'),
                'type' => 'enum',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'tab' => _t('personal_detail'),
            ]);
        }

        $this->crud->addField([
            'label' => _t('register_date'),
            'name' => 'register_date',
            'type' => 'date_picker',
            'default' => date('Y-m-d'),
            'date_picker_options' => [
                'format' => 'yyyy-mm-dd',
            ],
            'script' => 'change',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'tab' => _t('personal_detail'),
//            'location_group' => 'General',
        ]);

        $this->crud->addField([
            'label' => _t('Customer Type'),
            'type' => "select2_from_ajax",
            'name' => 'customer_group_id', // the column that contains the ID of that connected entity
            'entity' => 'customer_group_name', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\\Models\\CustomerGroup", // foreign key model
            'data_source' => url("api/get-customer-group"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select a Group"), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'tab' => _t('personal_detail'),
           'location_group' => 'General',
        ]);



        $this->crud->addField([
            'label' => _t('Loan Officer Name'),
            'type' => "select2_from_ajax_loan_officer",
            'name' => 'loan_officer_id', // the column that contains the ID of that connected entity
            'entity' => 'officer_name', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\\User", // foreign key model
            'data_source' => url("api/get-user"), // url to controller search function (with /{id} should return model)
            'placeholder' => _t("Select a officer"), // placeholder for the select
            'minimum_input_length' => 0, // minimum characters to type before querying results
            'wrapperAttributes' => [
                'class' => 'form-group col-md-3'
            ],
            'tab' => _t('personal_detail'),
//            'location_group' => 'General',
        ]);


        /*$table->string('province_id')->nullable();
        $table->string('district_id')->nullable();
        $table->string('commune_id')->nullable();
        $table->string('village_id')->nullable();
        $table->string('ward_id')->nullable();
        $table->string('street_number')->nullable();
        $table->string('house_number')->nullable();*/









        if(getEnvCountry() == 'KH') {
            $this->crud->addField([
                // 1-n relationship
                'label' => _t("City / Province", 'location'), // Table column heading
                'type' => "select2_province",
                'name' => 'province_id', // the column that contains the ID of that connected entity
                'entity' => 'province', // the method that defines the relationship in your Model
                'attribute' => "name", // foreign key attribute that is shown to user
                'model' => "App\Address", // foreign key model
                'data_source' => url("api/province"), // url to controller search function (with /{id} should return model)
                'placeholder' => _t("Select a province"), // placeholder for the select
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
                'attributes' => [
                    'class' => 'form-control'
                ],
                'tab' => _t(' Address'),
                'location_group' => '',
            ]);

            $this->crud->addField([

                'label' => _t("District", 'location'), // Table column heading
                'type' => "select2_district",
                'name' => 'district_id', // the column that contains the ID of that connected entity
                'entity' => 'district', // the method that defines the relationship in your Model
                'attribute' => "name", // foreign key attribute that is shown to user
                'model' => "App\Address", // foreign key model
                'data_source' => url("api/district"), // url to controller search function (with /{id} should return model)
                'placeholder' => _t("Select a district"), // placeholder for the select
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
                'attributes' => [
                    'class' => 'form-control'
                ],
                'tab' => _t(' Address'),
                'location_group' => '',
            ]);

            $this->crud->addField([

                'label' => _t("Commune", 'location'), // Table column heading
                'type' => "select2_commune",
                'name' => 'commune_id', // the column that contains the ID of that connected entity
                'entity' => 'commune', // the method that defines the relationship in your Model
                'attribute' => "name", // foreign key attribute that is shown to user
                'model' => "App\Address", // foreign key model
                'data_source' => url("api/commune"), // url to controller search function (with /{id} should return model)
                'placeholder' => _t("Select a commune"), // placeholder for the select
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
                'attributes' => [
                    'class' => 'form-control'
                ],
                'tab' => _t(' Address'),
                'location_group' => '',
            ]);

            $this->crud->addField([
                'label' => _t("Village", 'location'), // Table column heading
                'type' => "select2_village",
                'name' => 'village_id', // the column that contains the ID of that connected entity
                'entity' => 'village', // the method that defines the relationship in your Model
                'attribute' => "name", // foreign key attribute that is shown to user
                'model' => "App\Address", // foreign key model
                'data_source' => url("api/village"), // url to controller search function (with /{id} should return model)
                'placeholder' => _t("Select a village"), // placeholder for the select
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
                'attributes' => [
                    'class' => 'form-control'
                ],
                'tab' => _t(' Address'),
                'location_group' => '',
            ]);

            $this->crud->addField([
                'name' => 'street_number',
                'label' => _t('Street Number', 'location'),
                'type' => 'text_street',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
                'attributes' => [
                    'class' => 'form-control'
                ],
                'tab' => _t(' Address'),
                'location_group' => '',
            ]);

            $this->crud->addField([
                'name' => 'house_number',
                'label' => _t('House Number', 'location'),
                'type' => 'text_house',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
                'attributes' => [
                    'class' => 'form-control'
                ],
                'tab' => _t(' Address'),
                'location_group' => '',
            ]);

            $this->crud->addField([
                'name' => 'address',
                'label' => _t('Address'),
                'type' => 'textarea2',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-12'
                ],
                'attributes' => [
                    'class' => 'form-control'
                ],
                'tab' => _t(' Address'),
                'location_group' => '',
            ]);
        }else if(getEnvCountry() == 'MM'){
            $this->crud->addField([
                // 1-n relationship
                //'label' => _t("Province", 'location'), // Table column heading
                'type' => "select2_from_ajax_address_myanmar",
                'name' => "province_id",
                'state_name' => "province_id",
                'district_name' => "district_id",
                'township_name' => "commune_id",
                'village_name' => "village_id",
                'ward_name' => "ward_id",
                'house_number_name' => "house_number",
                'address_name' => "address1",
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
                'tab' => _t(' Address')
            ]);


           // dd($m->address);
            $this->crud->addField([
                'name' => 'address2',
                'label' => _t('address 2'),
                'default' => optional($m)->address,
                'type' => 'textarea',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-12'
                ],
                'tab' => _t(' Address'),
            ]);

        }



        $this->crud->addField([
            'name' => 'lat',
            'label' => _t('Lat'),
            'type' => "text",
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'tab' => _t(' Address'),
        ]);

        $this->crud->addField([
            'name' => 'lng',
            'label' => _t('Lng'),
            'type' => "text",
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'tab' => _t(' Address'),
        ]);


        /*
        $this->crud->addField([
            // 1-n relationship
            //'label' => _t("Province", 'location'), // Table column heading
            'type' => "select2_from_ajax_address_myanmar",
            'name' => "province_id",
            'state_name' => "province_id",
            'district_name' => "district_id",
            'township_name' => "commune_id",
            'village_name' => "village_id",
            'ward_name' => "ward_id",
            'house_number_name' => "house_number",
            'address_name' => "address1",
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'tab' => _t(' Address')
        ]);


        /**
         * personal_detail
         */
        // $this->crud->addField([
        //     'name' => 'address1',
        //     'label' => _t('address1'),
        //     'type' => 'textarea',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-md-6'
        //     ],
        //     'tab' => _t('personal_detail'),
        // ]);






        /**
         * upload KYC document
         */

///////////////// Employee Status
        /* no ready ********/
        /**
         * employee status
         */

        if (companyReportPart() != 'company.bolika') {
            $this->crud->addField([
                'name' => 'position',
                'label' => _t('Position'),
                'entity' => 'employee', // the method that defines the relationship in your Model
                'attribute' => "position", // foreign key attribute that is shown to user
                'model' => "App\\Models\\EmployeeStatus",
                'type' => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'tab' => _t('Employee Status'),
            ]);


            if (companyReportPart() != 'company.moeyan') {
                $this->crud->addField([
                    'name' => 'employment_status',
                    'label' => _t('Employee Status'),
                    'entity' => 'employee', // the method that defines the relationship in your Model
                    'attribute' => "employment_status", // foreign key attribute that is shown to user
                    'model' => "App\\Models\\EmployeeStatus",
                    'type' => "select_from_array",
                    'options' => ['' => '', 'Active' => 'Work', 'Inactive' => 'Not Work'],
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3'
                    ],
                    'tab' => _t('Employee Status'),
                    //            'location_group' => 'General',
                ]);

                $this->crud->addField([
                    'name' => 'employment_industry',
                    'label' => _t('Employee Industry'),
                    'entity' => 'employee',
                    'attribute' => 'employment_industry',
                    'model' => 'App\\Models\\EmployeeStatus',
                    'type' => "select_from_array",
                    'options' => ['' => '', 'manufacturing' => 'Manufacturing', 'industry' =>
                        'Industry', 'factory' => 'Factory', 'trading' => 'Trading',
                        'servicing' => 'Servicing', 'agricultural' => 'Agricultural',
                        'live_stock_and_fishery' => 'Live Stock & Fishery', 'company_office_staff' => 'Company office staff',
                        'government_office_staff' => 'Government office staff', 'arts' => 'Arts (eg: actor/actress)',
                        'general_worker' => 'General worker', 'media' => 'Media - (eg: writer /editor)', 'dependant' => 'Dependant'],
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3'
                    ],
                    'tab' => _t('Employee Status'),
                    //            'location_group' => 'General',
                ]);

                $this->crud->addField([
                    'name' => 'senior_level',
                    'label' => _t('Seniority Level'),
                    'entity' => 'employee',
                    'attribute' => 'senior_level',
                    'model' => 'App\\Models\\EmployeeStatus',
                    'type' => "select_from_array",
                    'options' => ['' => '', 'staff' => 'Staff', 'senior' => 'Senior', 'manager' => 'Manager', 'director' => 'Director', 'ceo' => 'CEO'],
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3'
                    ],
                    'tab' => _t('Employee Status'),
                    //            'location_group' => 'General',
                ]);
            }

            $this->crud->addField([
                'name' => 'company_name',
                'label' => _t('Company Name'),
                'entity' => 'employee',
                'attribute' => 'company_name',
                'model' => 'App\\Models\\EmployeeStatus',
                'type' => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'tab' => _t('Employee Status'),
            ]);

            if (companyReportPart() != 'company.mkt' && companyReportPart() != 'company.moeyan') {
                $this->crud->addField([
                    'name' => 'branch',
                    'label' => _t('Branch'),
                    'entity' => 'employee',
                    'attribute' => 'branch',
                    'model' => 'App\\Models\\EmployeeStatus',
                    'type' => 'text',
                    'wrapperAttributes' => [
                        'class' => 'form-group col-md-3'
                    ],
                    'tab' => _t('Employee Status'),
                ]);
            }

            $this->crud->addField([
                'name' => 'department',
                'label' => _t('Department'),
                'entity' => 'employee',
                'attribute' => 'department',
                'model' => 'App\\Models\\EmployeeStatus',
                'type' => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'tab' => _t('Employee Status'),
            ]);

            $this->crud->addField([
                'name' => 'work_phone',
                'label' => _t('Work Phone'),
                'entity' => 'employee',
                'attribute' => 'work_phone',
                'model' => 'App\\Models\\EmployeeStatus',
                'type' => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'tab' => _t('Employee Status'),
            ]);


            $this->crud->addField([
                'name' => 'work_phone2',
                'label' => _t('Work Phone 2'),
                'entity' => 'employee',
                'attribute' => 'work_phone2',
                'model' => 'App\\Models\\EmployeeStatus',
                'type' => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'tab' => _t('Employee Status'),
            ]);

        }
        
        if(companyReportPart() == 'company.moeyan' || companyReportPart() == 'company.mkt' || companyReportPart()  == 'company.quicken'){
            $this->crud->addField([
                'name' => 'you_are_a_guarantor',
                'label' => _t('you_are_a_guarantor'),
                'type' => 'select_from_array',
                'options' => [0 => 'No', 1 => 'Yes'],
                'attributes' => [
                    'id' => 'guarantor'
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'fake' => true,
                'tab' => _t('personal_detail'),
            ]);
        }

        
        if(companyReportPart() != 'company.moeyan' && companyReportPart() != 'company.bolika'){
            $this->crud->addField([
                'name' => 'working_experience',
                'label' => _t('Working Experience'),
                'entity' => 'employee',
                'attribute'=> 'working_experience',
                'model' => 'App\\Models\\EmployeeStatus',
                'type' => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'tab' => _t('Employee Status'),
            ]);
        }

        if(companyReportPart() != 'company.mkt' && companyReportPart() != 'company.moeyan' && companyReportPart() != 'company.bolika'){
            $this->crud->addField([
                'name' => 'work_day',
                'label' => _t('Work Day'),
                'entity' => 'employee',
                'attribute'=> 'work_day',
                'model' => 'App\\Models\\EmployeeStatus',
                'type' => "select_from_array",
                'options' => ['' => '', 'Friday' => 'Friday', 'Saturday' => 'Saturday', 'Sunday' => 'Sunday'],
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'tab' => _t('Employee Status'),
    //            'location_group' => 'General',
            ]);
        }

        if(companyReportPart() != 'company.bolika') {
            $this->crud->addField([
                'name' => 'basic_salary',
                'label' => _t('Basic Salary'),
                'entity' => 'employee',
                'attribute' => 'basic_salary',
                'model' => 'App\\Models\\EmployeeStatus',
                'type' => 'text',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'tab' => _t('Employee Status'),
            ]);
        }

        if(companyReportPart() != 'company.moeyan' && companyReportPart() != 'company.bolika'){
            $this->crud->addField([
                'name' => 'company_address',
                'label' => _t('Company Address'),
                'entity' => 'employee',
                'attribute'=> 'company_address',
                'model' => 'App\\Models\\EmployeeStatus',
                'type' => 'textarea',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-3'
                ],
                'tab' => _t('Employee Status'),
            ]);
        }
         
        if(companyReportPart() == 'company.moeyan'){
            $this->crud->addField([
                // 1-n relationship
                //'label' => _t("Province", 'location'), // Table column heading
                'type' => "select2_from_ajax_address_myanmar",
                'name' => "company_province_id",
                'state_name' => "company_province_id",
                'district_name' => "company_district_id",
                'township_name' => "company_commune_id",
                'village_name' => "company_village_id",
                'ward_name' => "company_ward_id",
                'house_number_name' => "company_house_number",
                'address_name' => "company_address1",
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
                'tab' => _t('Employee Status')
            ]);
        }
        ///////////////// Employee Status



        /**
         * upload KYC document
         */


        /**
         * survey
         */

        $survey = Survey::all();
        $arr_survey = [];
        if ($survey != null) {
            foreach ($survey as $su) {
                $arr_survey[$su->id] = $su->name;
            }
        }

        if(companyReportPart() != 'company.bolika') {

            $this->crud->addField([// n-n relationship
                'label' => _t('survey'), // Table column heading
                'type' => "checklist_from_array",
                'name' => 'survey_id', // the column that contains the ID of that connected entity
                'option' => $arr_survey,
                // 'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-12'
                ],
                'tab' => _t('survey and ownership'),
            ]);
        }


        /* no ready *******/


        $ownership_of_farmland = OwnershipFarmland::all();
        $arr_ownership_of_farmland = [];
        if ($ownership_of_farmland != null) {
            foreach ($ownership_of_farmland as $osf) {
                $arr_ownership_of_farmland[$osf->id] = $osf->name;
            }
        }


        /**
         * ownerhsip of farmland
         */
        if(companyReportPart() != 'company.bolika') {
            $this->crud->addField([
                'name' => 'ownership_of_farmland',
                'label' => _t('ownership_of_farmland'),
                'type' => "checklist_from_array",
                'option' => $arr_ownership_of_farmland,
                'tab' => _t('survey and ownership'),
            ]);
        }


        /**
         * ownerhsip
         */

        $owner_ship = Ownership::all();
        $arr_owner_ship = [];
        if ($owner_ship != null) {
            foreach ($owner_ship as $ows) {
                $arr_owner_ship[$ows->id] = $ows->name;
            }
        }


        if(companyReportPart() != 'company.bolika') {

            $this->crud->addField([
                'name' => 'ownership',
                'label' => _t('ownership'),
                'option' => $arr_owner_ship,
                'type' => "checklist_from_array",
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-12'
                ],
                'tab' => _t('survey and ownership'),
            ]);

        }


//        $this->crud->addField([
//            'label' => _t('attach_file'),
//            'name' => 'attach_file',
//            'type' => 'upload_multiple',
//            'upload' => true,
//            //'disk' => 'uploads', // if you store files in the /public folder, please ommit this; if you store them in /storage or S3, please specify it;
//            'tab' => _t('KYC Document'),
//        ]);


        $this->crud->addField([
            'label' => _t('family_registration_copy'),
            'name' => 'family_registration_copy',
            'type' => 'upload_multiple',
            'upload' => true,
            //'disk' => 'uploads', // if you store files in the /public folder, please ommit this; if you store them in /storage or S3, please specify it;
            'tab' => _t('KYC Document'),
        ]);


        $this->crud->addField([
            'label' => _t('photo_of_client'),
            'name' => 'photo_of_client',
            'type' => 'image2',
            'upload' => true,
            'default' => 'No_Image_Available.jpg',
            'crop' => true, // set to true to allow cropping, false to disable
            'aspect_ratio' => 1, // ommit or set to 0 to allow any aspect ratio
            //'disk' => 'uploads', // if you store files in the /public folder, please ommit this; if you store them in /storage or S3, please specify it;
            'tab' => _t('Image'),
        ]);


        $this->crud->addField([
            'label' => _t('nrc_photo'),
            'name' => 'nrc_photo',
            'type' => 'upload_multiple',
            'upload' => true,
            //'disk' => 'uploads', // if you store files in the /public folder, please ommit this; if you store them in /storage or S3, please specify it;
            'tab' => _t('KYC Document'),
        ]);


        $this->crud->addField([
            'label' => _t('scan_finger_print'),
            'name' => 'scan_finger_print',
            'type' => 'upload_multiple',
            'upload' => true,
            //'disk' => 'uploads', // if you store files in the /public folder, please ommit this; if you store them in /storage or S3, please specify it;
            'tab' => _t('KYC Document'),
        ]);

        if(companyReportPart() == 'company.moeyan'){
            $this->crud->addField([
                'label' => _t('form_photo_front'),
                'name' => 'form_photo_front',
                'type' => 'upload_multiple',
                'upload' => true,
                //'disk' => 'uploads', // if you store files in the /public folder, please ommit this; if you store them in /storage or S3, please specify it;
                'tab' => _t('KYC Document'),
            ]);
    
    
            $this->crud->addField([
                'label' => _t('form_photo_back'),
                'name' => 'form_photo_back',
                'type' => 'upload_multiple',
                'upload' => true,
                //'disk' => 'uploads', // if you store files in the /public folder, please ommit this; if you store them in /storage or S3, please specify it;
                'tab' => _t('KYC Document'),
            ]);

            $this->crud->addField([
                'label' => _t('company_letter_head'),
                'name' => 'company_letter_head',
                'type' => 'upload_multiple',
                'upload' => true,
                //'disk' => 'uploads', // if you store files in the /public folder, please ommit this; if you store them in /storage or S3, please specify it;
                'tab' => _t('KYC Document'),
            ]);
    
    
            $this->crud->addField([
                'label' => _t('community_recommendation'),
                'name' => 'community_recommendation',
                'type' => 'upload_multiple',
                'upload' => true,
                //'disk' => 'uploads', // if you store files in the /public folder, please ommit this; if you store them in /storage or S3, please specify it;
                'tab' => _t('KYC Document'),
            ]);

            $this->crud->addField([
                'label' => _t('employment_certificate'),
                'name' => 'employment_certificate',
                'type' => 'upload_multiple',
                'upload' => true,
                //'disk' => 'uploads', // if you store files in the /public folder, please ommit this; if you store them in /storage or S3, please specify it;
                'tab' => _t('KYC Document'),
            ]);
    
    
            $this->crud->addField([
                'label' => _t('other_document'),
                'name' => 'other_document',
                'type' => 'upload_multiple',
                'upload' => true,
                //'disk' => 'uploads', // if you store files in the /public folder, please ommit this; if you store them in /storage or S3, please specify it;
                'tab' => _t('KYC Document'),
            ]);
        }

        $this->crud->addField([
            'name' => 'custom-ajax-button12',
            'type' => 'view',
            'view' => 'partials/client/custom_ajax_read_only_single',
            'tab' => _t('family_information'),
        ]);


//        $this->crud->addField([
//            'label' => _t('nrc_number'),
//            'name' => 'nrc_number',
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-3'
//            ],
//            'tab' => _t('General'),
//            'location_group' => 'General',
//        ]);
//
//        $this->crud->addField([
//            'label' => _t('title'),
//            'name' => 'title',
//            'type' => 'enum',
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-3'
//            ],
//            'tab' => _t('General'),
//            'location_group' => 'General',
//        ]);
//
//
//
//        $this->crud->addField([
//            'label' => _t('father_name'),
//            'name' => 'father_name',
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-3'
//            ],
//            'tab' => _t('General'),
//            'location_group' => 'General',
//        ]);
//
//        $this->crud->addField([
//            'label' => _t('mobile'),
//            'name' => 'mobile',
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-3'
//            ],
//            'tab' => _t('General'),
//            'location_group' => 'General',
//        ]);
//
//        $this->crud->addField([
//            'label' => _t('phone'),
//            'name' => 'phone',
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-3'
//            ],
//            'tab' => _t('General'),
//            'location_group' => 'General',
//        ]);
//
//        $this->crud->addField([
//            'label' => _t('email'),
//            'name' => 'email',
//            'type' => 'email',
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-3'
//            ],
//            'tab' => _t('General'),
//            'location_group' => 'General',
//        ]);
//
//
//
//        $this->crud->addField([
//            'label' => _t('place_of_birth'),
//            'name' => 'place_of_birth',
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-3'
//            ],
//            'tab' => _t('General'),
//        ]);
//
//        $this->crud->addField([ // image
//            'default' => asset('No_Image_Available.jpg'),
//            'label' => _t('photo'),
//            'name' => "photo",
//            'type' => 'image',
//            'upload' => true,
//            'crop' => true, // set to true to allow cropping, false to disable
//            'aspect_ratio' => 0, // ommit or set to 0 to allow any aspect ratio
//            //disk' => 'local_public', // in case you need to show images from a different disk
//            //'prefix' => 'uploads/images/Clients/', // in case your db value is only the file name (no path), you can use this to prepend your path to the image src (in HTML), before it's shown to the user;
//           /* 'wrapperAttributes' => [
//                'class' => 'form-group col-md-3'
//            ],*/
//            'tab' => _t('Photo'),
//        ]);
//
//        $this->crud->addField([
//            'label' => _t('attach_file'),
//            'name' => 'attach_file',
//            'type' => 'upload_multiple',
//            'upload' => true,
//            //'disk' => 'uploads', // if you store files in the /public folder, please ommit this; if you store them in /storage or S3, please specify it;
//            'tab' => _t('Photo'),
//        ]);
//
//        $this->crud->addField([
//            'label' => _t('description'),
//            'name' => 'description',
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-3'
//            ],
//            'tab' => _t('General'),
//        ]);
//
//        $this->crud->addField([
//            'label' => _t('marital_status'),
//            'name' => 'marital_status',
//            'type' => 'enum',
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-3'
//            ],
//            'tab' => _t('General'),
//        ]);
//
//        $this->crud->addField([
//            'label' => _t('spouse_gender'),
//            'name' => 'spouse_gender',
//            'type' => 'enum',
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-3'
//            ],
//            'tab' => _t('General'),
//        ]);
//
//        $this->crud->addField([
//            'label' => _t('spouse_name'),
//            'name' => 'spouse_name',
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-3'
//            ],
//            'tab' => _t('General'),
//        ]);
//
//        $this->crud->addField([
//            'label' => _t('spouse_date_of_birth'),
//            'name' => 'spouse_date_of_birth',
//            'type' => 'date_picker',
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-3'
//            ],
//            'tab' => _t('General'),
//            'location_group' => 'General', // normal add with personal_detail
//        ]);
//
//        $this->crud->addField([
//            'label' => _t('number_of_child'),
//            'name' => 'number_of_child',
//            'type' => 'number2',
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-3'
//            ],
//            'tab' => _t('General'),
//        ]);
//
//        $this->crud->addField([
//            'label' => _t('spouse_mobile_phone'),
//            'name' => 'spouse_mobile_phone',
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-3'
//            ],
//            'tab' => _t('General'),
//        ]);
//
//        $this->crud->addField([
//            'label' => _t('user_name'),
//            'name' => 'user_name',
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-3'
//            ],
//            'tab' => _t('Account'),
//        ]);
//
//        $this->crud->addField([
//            'label' => _t('password'),
//            'name' => 'password',
//            'type' => 'password',
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-3'
//            ],
//            'tab' => _t('Account'),
//        ]);
//
//        $this->crud->addField([
//            'label' => _t('Loan Officer Access'),
//            'type' => "select2_from_ajax",
//            'name' => 'loan_officer_access_id', // the column that contains the ID of that connected entity
//            'entity' => 'user', // the method that defines the relationship in your Model
//            'attribute' => "name", // foreign key attribute that is shown to user
//            'model' => "App\\Models\\Client", // foreign key model
//            'data_source' => url("api/get-user"), // url to controller search function (with /{id} should return model)
//            'placeholder' => _t("Select a loan officer access"), // placeholder for the select
//            'minimum_input_length' => 0, // minimum characters to type before querying results
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-3'
//            ],
//            'tab' => _t('Account'),
////            'location_group' => 'General',
//        ]);
//
//
//
//
//
//
//        // =======  personal_detail ========
//        $this->crud->addField([
//            // 1-n relationship
//            'label' => _t("Province", 'location'), // Table column heading
//            'type' => "select2_province",
//            'name' => 'province_id', // the column that contains the ID of that connected entity
//            'entity' => 'province', // the method that defines the relationship in your Model
//            'attribute' => "name", // foreign key attribute that is shown to user
//            'model' => "App\personal_detail", // foreign key model
//            'data_source' => url("api/province"), // url to controller search function (with /{id} should return model)
//            'placeholder' => _t("Select a province"), // placeholder for the select
//            'minimum_input_length' => 0, // minimum characters to type before querying results
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-6'
//            ],
//            'attributes' => [
//                'class' => 'form-control'
//            ],
//            'tab' => _t(' personal_detail'),
//            'location_group' => '',
//        ]);
//
//
//        $this->crud->addField([
//
//            'label' => _t("District", 'location'), // Table column heading
//            'type' => "select2_district",
//            'name' => 'district_id', // the column that contains the ID of that connected entity
//            'entity' => 'district', // the method that defines the relationship in your Model
//            'attribute' => "name", // foreign key attribute that is shown to user
//            'model' => "App\personal_detail", // foreign key model
//            'data_source' => url("api/district"), // url to controller search function (with /{id} should return model)
//            'placeholder' => _t("Select a district"), // placeholder for the select
//            'minimum_input_length' => 0, // minimum characters to type before querying results
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-6'
//            ],
//            'attributes' => [
//                'class' => 'form-control'
//            ],
//            'tab' => _t(' personal_detail'),
//            'location_group' => '',
//        ]);
//
//        $this->crud->addField([
//
//            'label' => _t("Commune", 'location'), // Table column heading
//            'type' => "select2_commune",
//            'name' => 'commune_id', // the column that contains the ID of that connected entity
//            'entity' => 'commune', // the method that defines the relationship in your Model
//            'attribute' => "name", // foreign key attribute that is shown to user
//            'model' => "App\personal_detail", // foreign key model
//            'data_source' => url("api/commune"), // url to controller search function (with /{id} should return model)
//            'placeholder' => _t("Select a commune"), // placeholder for the select
//            'minimum_input_length' => 0, // minimum characters to type before querying results
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-6'
//            ],
//            'attributes' => [
//                'class' => 'form-control'
//            ],
//            'tab' => _t(' personal_detail'),
//            'location_group' => '',
//        ]);
//
//        $this->crud->addField([
//
//            'label' => _t("Village", 'location'), // Table column heading
//            'type' => "select2_village",
//            'name' => 'village_id', // the column that contains the ID of that connected entity
//            'entity' => 'village', // the method that defines the relationship in your Model
//            'attribute' => "name", // foreign key attribute that is shown to user
//            'model' => "App\personal_detail", // foreign key model
//            'data_source' => url("api/village"), // url to controller search function (with /{id} should return model)
//            'placeholder' => _t("Select a village"), // placeholder for the select
//            'minimum_input_length' => 0, // minimum characters to type before querying results
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-6'
//            ],
//            'attributes' => [
//                'class' => 'form-control'
//            ],
//            'tab' => _t(' personal_detail'),
//            'location_group' => '',
//        ]);
//
//
//        $this->crud->addField([
//            'name' => 'street_number',
//            'label' => _t('Street Number', 'location'),
//            'type' => 'text_street',
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-6'
//            ],
//            'attributes' => [
//                'class' => 'form-control'
//            ],
//            'tab' => _t(' personal_detail'),
//            'location_group' => '',
//        ]);
//
//        $this->crud->addField([
//            'name' => 'house_number',
//            'label' => _t('House Number', 'location'),
//            'type' => 'text_house',
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-6'
//            ],
//            'attributes' => [
//                'class' => 'form-control'
//            ],
//            'tab' => _t(' personal_detail'),
//            'location_group' => '',
//        ]);
//
//
//        $this->crud->addField([
//            'name' => 'personal_detail',
//            'label' => _t('personal_detail'),
//            'type' => 'textarea2',
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-12'
//            ],
//            'attributes' => [
//                'class' => 'form-control'
//            ],
//            'tab' => _t(' personal_detail'),
//            'location_group' => '',
//        ]);

        if (companyReportPart() == 'company.moeyan') {

            $this->crud->child_resource_included = ['select' => false, 'text' => false];
            $this->crud->addField([ // Table
                'name' => 'contact_person',
                'label' => _t('Contact Person'),
                'type' => 'child',
                'entity_singular' => 'person', // used on the "Add X" button
                'columns' => [
                    [
                        'name' => 'name',
                        'label' => _t('Name'),
                        'type' => 'child_text'
                    ],
                    [
                        'name' => 'phone',
                        'label' => _t('Phone'),
                        'type' => 'child_text'
                    ],
                    [
                        'name' => 'relationship',
                        'label' => _t('Relationship'),
                        'type' => 'child_text'
                    ]
                ],
                'max' => 3, // maximum rows allowed in the table
                'min' => 3, // minimum rows allowed in the table,
                'tab' => 'Contact Persons'
            ]);

        }

        if (companyReportPart() == 'company.quicken') {
            $this->crud->addColumn([
                'name' => 'condition',
                'label' => _t('Status'),
                'type' => 'closure',
                'function' => function($entry) {
            
                    if ($entry->condition=="Waiting Client"){
                        return '<button class="btn btn-warning btn-xs " >'.$entry->condition.'</button>';
                    }
                    elseif ($entry->condition=="Have Loan"){
                        return '<button class="btn btn-success btn-xs" >'.$entry->condition.'</button>';
                    }
            
                }
            
            ]);
        }

        // add asterisk for fields that are required in ClientRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->crud->orderBy('id', 'desc');
        $this->setPermissions();


    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'client';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }

        // Allow create access
        // if (_can2($this,'create-'.$fname)) {
        //     $this->crud->allowAccess('create');
        // }

        // Allow update access
        if (_can2($this,'update-'.$fname)) {
            $this->crud->allowAccess('update');
        }

        // Allow delete access
        if (_can2($this,'delete-'.$fname)) {
            $this->crud->allowAccess('delete');
        }


//        if (_can2($this,'clone-'.$fname)) {
//            $this->crud->allowAccess('clone');
//        }

    }

    public function store(StoreRequest $request)
    {
        companyReportPart() == "company.moeyan" ? $request['nrc_type'] = "New Format" : "";
//        dd($request->all());
        if (companyReportPart() != 'company.bolika'){
            $this->NrcFormat($request);
        }

        $nrc_number = $request->nrc_number;
        $nrc_number_new = $request->nrc_number_new;

        $date = $request->dob;

        if ($date != null) {
            $_age = floor((time() - strtotime($date)) / 31556926);

            if ($_age < 18.0) {
                return redirect()->back()->withErrors(['The age must be greater-than 18 years old.']);
            }
        }

        if ($request->nrc_type == 'New Format'){
            $nrc = Client::where('nrc_number', $nrc_number)->first();
            if ($nrc != null) {
                return redirect()->back()->withErrors(['The NRC Number has been used']);
            }
        }

        //dd($request);

        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here

        if($request->you_are_a_guarantor){
            Guarantor::create(["nrc_type" => "New Format", "nrc_number" => $nrc_number, "full_name_en" => $request->name, "full_name_mm" => $request->name_other,
                        "father_name" => $request->father_name, "mobile" => $request->primary_phone_number, "phone" => $request->alternate_phone_number, "dob" => $request->dob,
                        "income" => $request->basic_salary, "photo" => $request->photo_of_client, "province_id" => $request->province_id, "district_id" => $request->district_id, "commune_id" => $request->commune_id,
                        "village_id" => $request->village_id, "house_number" => $request->house_number, "address" => $request->address1]);
        }
        // use $this->data['entry'] or $this->crud->entry

        //Client::saveEmployee($request,$this->crud->entry);

        $client_pending_id = $this->crud->entry->client_pending_id??0;
        $m = ClientPending::find($client_pending_id);

        if($m != null){
            $m->status = 'completed';
            $m->save();

            return redirect('admin/approve-client-pending');
        }
        //return $redirect_location;
        return redirect()->back();
    }

    public function update(UpdateRequest $request)
    {
//         dd($request->all());
        companyReportPart() == "company.moeyan" ? $request['nrc_type'] = "New Format" : "";
        if (companyReportPart() != 'company.bolika'){
            $this->NrcFormat($request);
        }
        $nrc_number = $request->nrc_number;
        $nrc_number_new = $request->nrc_number_new;

        if($request->you_are_a_guarantor){
            $is_guarantor = Guarantor::where('nrc_number', $nrc_number)->first();
            
            $guarantor = array("nrc_type" => "New Format", "nrc_number" => $nrc_number, "full_name_en" => $request->name, "full_name_mm" => $request->name_other,
                        "father_name" => $request->father_name, "mobile" => $request->primary_phone_number, "phone" => $request->alternate_phone_number, "dob" => $request->dob,
                        "income" => $request->basic_salary, "photo" => $request->photo_of_client, "province_id" => $request->province_id, "district_id" => $request->district_id, "commune_id" => $request->commune_id,
                        "village_id" => $request->village_id, "house_number" => $request->house_number, "address" => $request->address1);
            if($is_guarantor){
                foreach($guarantor as $key => $attr){
                    $is_guarantor[$key] = $attr;
                }
            }else{
                Guarantor::create($guarantor);
            }
        }else{
            $guarantor = Guarantor::where('nrc_number', $nrc_number)->first();
            optional($guarantor)->delete();
        }

        $date = $request->dob;

        if ($date != null) {
            $_age = floor((time() - strtotime($date)) / 31556926);

            if ($_age < 18.0) {
                return redirect()->back()->withErrors(['The age must be greater-than 18 years old.']);
            }
        }


        $nrc = Client::where('nrc_number', $nrc_number)->where('nrc_number',$nrc_number_new)->first();
        if ($nrc != null) {
            if ($nrc->id != $request->id) {
                return redirect()->back()->withErrors(['The NRC Number has been used']);
            }
        }
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here

        
        // use $this->data['entry'] or $this->crud->entry
        //Client::saveEmployee($request,$this->crud->entry);

        return $redirect_location;
    }

    public function NrcFormat($request)
    {
        //dd($request->nrc_type);
        if ($request->nrc_type != 'Old Format') {

            $nrc_number = ((!empty($request->nrc_old[1])) ? $request->nrc_old[1] : '') . '/' . ((!empty($request->nrc_old[2])) ? $request->nrc_old[2] : '')
            . '(' . ((!empty($request->nrc_old[3])) ? $request->nrc_old[3] : '') . ')' . ((!empty($request->nrc_old[4])) ? $request->nrc_old[4] : '');
        } else {
            $nrc_number = $request->nrc_number_new;
        }
        $request->request->set('nrc_number', $nrc_number);
    }

    public function show($id)
    {
        $content = parent::show($id);

        // $this->crud->addColumn([
        //     'name' => 'table',
        //     'label' => 'Table',
        //     'type' => 'table',
        //     'columns' => [
        //         'name'  => 'Name',
        //         'desc'  => 'Description',
        //         'price' => 'Price',
        //     ]
        // ]);
        // $this->crud->addColumn('text');
        $this->crud->removeColumn('family_registration_copy');
        $this->crud->removeColumn('nrc_photo');
        $this->crud->removeColumn('scan_finger_print');
        $this->crud->removeColumn('form_photo_front');
        $this->crud->removeColumn('form_photo_back');
        $this->crud->removeColumn('company_letter_head');
        $this->crud->removeColumn('community_recommendation');
        $this->crud->removeColumn('employment_certificate');
        $this->crud->removeColumn('other_document');

        return $content;
    }

    public function customerOptions(Request $request) {
        $term = $request->input('term');
        $options = CustomerGroup::where('name', 'like', '%'.$term.'%')
            ->get()->pluck('name', 'id');
        return $options;
    }
}
