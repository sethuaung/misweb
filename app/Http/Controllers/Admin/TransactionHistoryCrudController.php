<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use Backpack\CRUD\CrudPanel;

/**
 * Class TransactionHistoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class TransactionHistoryCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\TransactionHistory');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/transaction-history');
        $this->crud->setEntityNameStrings('Transaction History', 'Transaction Histories');
        $this->crud->denyAccess(['create', 'update', 'delete']);
        $this->crud->removeAllButtons();

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'client_id',
            'label'=> 'Client'
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'customer_name', 'LIKE', "%$value%");
                $this->crud->addClause('select', 'transaction_histories'.'.*');
        
            }
        );

        $this->crud->addFilter([ // simple filter
            'type' => 'text',
            'name' => 'reference_no',
            'label'=> 'Reference No'
        ],
            false,
            function($value) { // if the filter is active
                $this->crud->addClause('where', 'reference_no', '=', $value);
            }
        );
        // TODO: remove setFromDb() and manually define Fields and Columns
        $this->crud->addColumn([
            'name' => 'reference_no',
            'label' => _t('Reference Number'),
        ]);
        $this->crud->addColumn([
            'label' => _t('Customer Name'),
            'type' => "select",
            'name' => 'customer_name', // the column that contains the ID of that connected entity
            'entity' => 'client', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\\Models\\Client",
        ]);
        $this->crud->addColumn([
            'name' => 'description',
            'label' => _t('Description')
        ]);
        $this->crud->addColumn([
            'name' => 'amount',
            'label' => _t('Amount')
        ]);
        $this->crud->addColumn([
            'name' => 'deleted_at',
            'type' => 'datetime',
            'label' => _t('Delected At'),
        ]);

        $this->crud->addColumn([
            'label' => _t('Deleted By'),
            'type' => "select",
            'name' => 'user_id', // the column that contains the ID of that connected entity
            'entity' => 'user', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\\Models\\BackpackUser",
        ]);

        // add asterisk for fields that are required in TransactionHistoryRequest
    }
}
