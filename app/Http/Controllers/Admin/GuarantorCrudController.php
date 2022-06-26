<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\GuarantorRequest as StoreRequest;
use App\Http\Requests\GuarantorRequest as UpdateRequest;

/**
 * Class GuarantorCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class GuarantorCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Guarantor');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/guarantor');
        $this->crud->setEntityNameStrings('Guarantor', 'Guarantors');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns

        /**
         * add columns
         */


        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'nrc_number',
            'label'=> 'NRC Number'
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', function ($q)use ($value){
                    return $q->orWhere('nrc_number','LIKE',"%{$value}%");
                });
            }
        );

        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'name',
            'label'=> 'Guarantors Name'
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', function ($q)use ($value){
                    return $q->orWhere('nrc_number','LIKE',"%{$value}%")
                        ->orWhere('full_name_en','LIKE',"%{$value}%")
                        ->orWhere('full_name_mm','LIKE',"%{$value}%")
                        ->orWhere('phone','LIKE',"%{$value}%")
                        ->orWhere('mobile','LIKE',"%{$value}%");
                });
            }
        );






//        $this->crud->addFilter([ // daterange filter
//            'type' => 'date_range',
//            'name' => 'from_to',
//            'label'=> 'Date'
//        ],
//            false,
//            function($value) { // if the filter is active, apply these constraints
//                $dates = json_decode($value);
//                $this->crud->addClause('where', 'created_at', '>=', $dates->from);
//                $this->crud->addClause('where', 'created_at', '<=', $dates->to . ' 23:59:59');
//            });


        $this->crud->addColumn([
            'name' => 'nrc_number',
            'label' => _t('nrc_number'),
        ]);

//        $this->crud->addColumn([
//            'name' => 'title',
//            'label' => _t('title'),
//        ]);

        if (companyReportPart() == 'company.bolika'){
            $this->crud->addColumn([
                'name' => 'full_name_en',
                'label' => _t('First Name'),
            ]);
        }else{
            $this->crud->addColumn([
                'name' => 'full_name_en',
                'label' => _t('Full Name (English)'),
            ]);
        }

        if (companyReportPart() == 'company.bolika'){
            $this->crud->addColumn([
                'name' => 'full_name_mm',
                'label' => _t('Last Name'),
            ]);
        }else{
            $this->crud->addColumn([
                'name' => 'full_name_mm',
                'label' => _t('Full Name (Myanmar)'),
            ]);
        }


        $this->crud->addColumn([
            'name' => 'mobile',
            'label' => _t('phone'),
        ]);

        /**
         * add fields
         */



        $this->crud->addField([
            'name' => 'nrc_type',
            'label' => _t('nrc_type'),
            'type' => 'enum',
            'attributes' => [
                'id' => 'nrc_type',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4',
            ],
            'tab' => _t('General'),
        ]);
        $this->crud->addField([
            'name' => 'nrc_number_new',
            'label' => _t('nrc_number'),
            'type' => 'text',
            'attributes' => [
                'id' => 'nrc_new',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 nrc_old'
            ],
            'tab' => _t('General'),
        ]);
        $this->crud->addField([
            'name' => 'nrc_number_old',
            'label' => _t('nrc_number'),
            'type' => 'nrc',
            'attributes' => [
                'id' => 'nrc_old',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4 nrc_new'
            ],
            'tab' => _t('General'),
        ]);
//        $this->crud->addField([
//            'label' => _t('nrc_number'),
//            'name' => 'nrc_number',
//            'wrapperAttributes' => [
//                'class' => 'form-group col-md-4'
//            ],
//            'tab' => _t('General'),
//            'location_group' => 'General',
//        ]);
//
//


        // $this->crud->addField([
        //     'label' => _t('title'),
        //     'name' => 'title',
        //     'type' => 'enum',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-md-4'
        //     ],
        //     'tab' => _t('General'),
        //     'location_group' => 'General',
        // ]);

        if (companyReportPart() == 'company.bolika'){
            $this->crud->addField([
                'label' => _t('First Name'),
                'name' => 'full_name_en',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ],
                'tab' => _t('General'),
                'location_group' => 'General',
            ]);
        }else{
            $this->crud->addField([
                'label' => _t('Full Name (English)'),
                'name' => 'full_name_en',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ],
                'tab' => _t('General'),
                'location_group' => 'General',
            ]);
        }

        if (companyReportPart() == 'company.bolika'){
            $this->crud->addField([
                'label' => _t('Last Name'),
                'name' => 'full_name_mm',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ],
                'tab' => _t('General'),
                'location_group' => 'General',
            ]);
        }else{
            $this->crud->addField([
                'label' => _t('Full Name (Myanmar)'),
                'name' => 'full_name_mm',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ],
                'tab' => _t('General'),
                'location_group' => 'General',
            ]);
        }


        $this->crud->addField([
            'label' => _t('father_name'),
            'name' => 'father_name',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('General'),
            'location_group' => 'General',
        ]);

        $this->crud->addField([
            'label' => _t('mobile'),
            'type' => 'phone_11_digit',
            'name' => 'mobile',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('General'),
            'location_group' => 'General',
        ]);

        $this->crud->addField([
            'label' => _t('phone'),
            'type' => 'phone_11_digit',
            'name' => 'phone',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('General'),
            'location_group' => 'General',
        ]);

        // $this->crud->addField([
        //     'label' => _t('email'),
        //     'name' => 'email',
        //     'type' => 'email',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-md-4'
        //     ],
        //     'tab' => _t('General'),
        //     'location_group' => 'General',
        // ]);

       /* $this->crud->addField([
            'label' => _t('dob'),
            'name' => 'dob',
            'type' => 'date_picker',
            'attributes' => [
                'id' => 'dob',
            ],
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('General'),
            'location_group' => 'General', // normal add with address
        ]);*/

        $this->crud->addField([
            'name' => 'dob',
            'label' => _t('dob'),
            'type' => 'date2',
            'default' => '1999-01-01',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('General'),
        ]);

        /*$this->crud->addField([
            'label' => _t('Age'),
            'name' => 'age',
            'type' => 'text_read_age',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('General'),
//            'location_group' => 'General',
        ]);*/

        // $this->crud->addField([
        //     'label' => _t('place_of_birth'),
        //     'name' => 'place_of_birth',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-md-4'
        //     ],
        //     'tab' => _t('General'),
        // ]);

        $this->crud->addField([ // image
            'default' => asset('No_Image_Available.jpg'),
            'label' => _t('photo'),
            'name' => "photo",
            'type' => 'image',
            'upload' => true,
            'crop' => true, // set to true to allow cropping, false to disable
            'aspect_ratio' => 0, // ommit or set to 0 to allow any aspect ratio
            //disk' => 'local_public', // in case you need to show images from a different disk
            //'prefix' => 'uploads/images/Guarantors/', // in case your db value is only the file name (no path), you can use this to prepend your path to the image src (in HTML), before it's shown to the user;
            /* 'wrapperAttributes' => [
                 'class' => 'form-group col-md-4'
             ],*/
            'tab' => _t('Photo'),
        ]);

        $this->crud->addField([
            'label' => _t('attach_file'),
            'name' => 'attach_file',
            'type' => 'upload_multiple',
            'upload' => true,
            //'disk' => 'uploads', // if you store files in the /public folder, please ommit this; if you store them in /storage or S3, please specify it;
            'tab' => _t('Photo'),
        ]);

        $this->crud->addField([
            'label' => _t('description'),
            'name' => 'description',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-8'
            ],
            'tab' => _t('General'),
        ]);

        // $this->crud->addField([
        //     'label' => _t('marital_status'),
        //     'name' => 'marital_status',
        //     'type' => 'enum',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-md-4'
        //     ],
        //     'tab' => _t('General'),
        // ]);
        //
        // $this->crud->addField([
        //     'label' => _t('spouse_gender'),
        //     'name' => 'spouse_gender',
        //     'type' => 'enum',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-md-4'
        //     ],
        //     'tab' => _t('General'),
        // ]);
        //
        // $this->crud->addField([
        //     'label' => _t('spouse_name'),
        //     'name' => 'spouse_name',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-md-4'
        //     ],
        //     'tab' => _t('General'),
        // ]);
        //
        // $this->crud->addField([
        //     'label' => _t('spouse_date_of_birth'),
        //     'name' => 'spouse_date_of_birth',
        //     'type' => 'date_picker',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-md-4'
        //     ],
        //     'tab' => _t('General'),
        //     'location_group' => 'General', // normal add with address
        // ]);

        // $this->crud->addField([
        //     'label' => _t('number_of_child'),
        //     'name' => 'number_of_child',
        //     'type' => 'number2',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-md-4'
        //     ],
        //     'tab' => _t('General'),
        // ]);
        //
        // $this->crud->addField([
        //     'label' => _t('spouse_mobile_phone'),
        //     'name' => 'spouse_mobile_phone',
        //     'wrapperAttributes' => [
        //         'class' => 'form-group col-md-4'
        //     ],
        //     'tab' => _t('General'),
        // ]);


        $this->crud->addField([
            'label' => _t('Monthly Income'),
            'name' => 'income',
            'type' => 'number2',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-4'
            ],
            'tab' => _t('General'),
        ]);

        if(!companyReportPart() == "company.moeyan"){
            $this->crud->addField([
                'label' => _t('Annual Income'),
                'name' => 'annual_income',
                'type' => 'number2',
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-4'
                ],
                'tab' => _t('General'),
            ]);
        }

        $this->crud->addField([
            'label' => _t('Business Information'),
            'name' => 'business_info',
            'type' => 'textarea',
            'wrapperAttributes' => [
                'class' => 'form-group col-md-12'
            ],
            'tab' => _t('General'),
        ]);





        // =======  Address ========

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
            'address_name' => "address",
            'wrapperAttributes' => [
                'class' => 'form-group col-md-6'
            ],
            'tab' => _t(' Address')
        ]);

        */


        if(getEnvCountry() == 'KH') {
            $this->crud->addField([
                // 1-n relationship
                'label' => _t("Province", 'location'), // Table column heading
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
                'address_name' => "address",
                'wrapperAttributes' => [
                    'class' => 'form-group col-md-6'
                ],
                'tab' => _t(' Address')
            ]);


        }



        // add asterisk for fields that are required in GuarantorRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
        $this->setPermissions();
        $this->crud->enableExportButtons();


    }


    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'guarantor';
        if (_can2($this,'list-'.$fname)) {
            $this->crud->allowAccess('list');
        }

        // Allow create access
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


//        if (_can2($this,'clone-'.$fname)) {
//            $this->crud->allowAccess('clone');
//        }

    }

    public function store(StoreRequest $request)
    {

        //dd($request->all());

        // your additional operations before save here
        $this->NrcFormat($request);
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
    public function NrcFormat($request){
    //dd($request->nrc_type);
    if ($request->nrc_type != 'Old Format') {

        $nrc_number = ((!empty($request->nrc_old[1])) ? $request->nrc_old[1] : '') . '/' . ((!empty($request->nrc_old[2])) ? $request->nrc_old[2] : '')
            . '(' . ((!empty($request->nrc_old[3])) ? $request->nrc_old[3] : '') . ')' . ((!empty($request->nrc_old[4])) ? $request->nrc_old[4] : '');
    } else {
        $nrc_number = $request->nrc_number_new;
    }
    $request->request->set('nrc_number', $nrc_number);
}

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $this->NrcFormat($request);
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
