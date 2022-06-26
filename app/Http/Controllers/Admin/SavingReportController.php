<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\CrudPanel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SavingDepositExport;
use App\Models\GeneralJournal;
use App\Models\Saving;
use App\Models\SavingAccrueInterests;
use App\Models\SavingTransaction;

/**
 * Class PaidDisbursementCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SavingReportController extends CrudController
{
    public function setup()
    {
        $param = request()->param;
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\CompulsorySavingTransaction');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/report/saving-report');
        $this->crud->setEntityNameStrings('Saving Report', 'Saving Report');
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */
        if (companyReportPart() == 'company.mkt') {
            $this->crud->addClause('LeftJoin', getLoanTable(), function ($join) {
                $join->on(getLoanTable() . '.id', '=', 'compulsory_saving_transaction.loan_id');
            });
            $this->crud->addClause('LeftJoin', 'branches', function ($join) {
                $join->on('branches.id', '=', getLoanTable() . '.branch_id');
            });
            $this->crud->addClause('where', getLoanTable() . '.branch_id', session('s_branch_id'));
        }
        if (companyReportPart() != 'company.mkt') {
            $this->crud->addFilter(
                [ // Branch select2_ajax filter
                    'name' => 'branch_id',
                    'type' => 'select2_ajax',
                    'label' => 'Branch',
                    'placeholder' => 'Select Branch'
                ],
                url('/api/branch-option'), // the ajax route
                function ($value) { // if the filter is active
                    $this->crud->addClause('whereHas', 'loan', function ($query) use ($value) {
                        $query->where('branch_id', $value);
                    });
                }
            );
        }
        $this->crud->addFilter(
            [ // daterange filter
                'type' => 'date_range',
                'name' => 'from_to',
                'label' => 'Date'
            ],
            false,
            function ($value) { // if the filter is active, apply these constraints
                $dates = json_decode($value);
                $this->crud->addClause('where', 'tran_date', '>=', $dates->from);
                $this->crud->addClause('where', 'tran_date', '<=', $dates->to . ' 23:59:59');
            }
        );

        $this->crud->addColumn([
            'label' => _t('Client ID'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function ($entry) {
                return optional(optional($entry->loan)->client_name)->client_number;
            }
        ]);
        $this->crud->addColumn([
            'label' => _t('Name (Eng)'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function ($entry) {
                return optional(optional($entry->loan)->client_name)->name;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Loan Officer ID'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function ($entry) {
                return optional(optional($entry->loan)->officer_name)->user_code;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Loan Officer Name'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function ($entry) {
                return optional(optional($entry->loan)->officer_name)->name;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Center Leader ID'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function ($entry) {
                return optional(optional($entry->loan)->center_leader_name)->code;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Center Leader Name'),
            'type' => 'closure',
            'orderable' => true,
            'function' => function ($entry) {
                return optional(optional($entry->loan)->center_leader_name)->title;
            }
        ]);

        $this->crud->addColumn([
            'label' => _t('Group Leader ID'),
        ]);

        $this->crud->addColumn([
            'label' => _t('Group Leader Name'),
        ]);

        $this->crud->addColumn([
            'label' => _t('Compulsory Saving Principle'),
        ]);

        $this->crud->addColumn([
            'label' => _t('Compulsory Saving Interest'),
        ]);

        $this->crud->addColumn([
            'label' => _t('Compulsory Saving Total'),
        ]);

        $this->crud->addColumn([
            'label' => _t('Voluntory Saving Principle'),
        ]);

        $this->crud->addColumn([
            'label' => _t('Voluntory Saving Interest'),
        ]);

        $this->crud->addColumn([
            'label' => _t('Voluntory Saving Total'),
        ]);

        $this->crud->addColumn([
            'label' => _t('Total Principle'),
        ]);

        $this->crud->addColumn([
            'label' => _t('Total Interest'),
        ]);

        $this->crud->addColumn([
            'label' => _t('Total'),
        ]);

        $this->crud->disableResponsiveTable();
        $this->crud->setDefaultPageLength(10);
        $this->crud->setListView('partials.loan_disbursement.saving-deposit');
        $this->crud->removeAllButtons();
        $this->crud->enableExportButtons();

        //$this->setPermissions();
    }

    public function setPermissions()
    {
        // Deny all accesses
        $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'clone']);

        $fname = 'my-paid-disbursement';
        if (_can2($this, 'list-' . $fname)) {
            $this->crud->allowAccess('list');
        }
    }

    public function excel(Request $request)
    {
        return Excel::download(new SavingDepositExport("partials.loan-payment.saving-deposit-list", $request->all()), 'Saving_Deposit_Report_' . date("d-m-Y_H:i:s") . '.xlsx');
    }

    public function savingsReport(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $saving_number = $request->saving_number;
        $reference_no = $request->reference_no;
        $perPage = request('perPage', 10);
        $client_id = $request->client_id - 0;
        $product_type = $request->saving_product - 0;
        $branch_id = $request->branch_id - 0;
        $report_type = $request->type;

        if ($report_type == "normal") {
            $rows = $this->savingReport($start_date, $end_date, $saving_number, $client_id, $branch_id, $product_type, $perPage);
        } else if ($report_type == "customer") {
            $rows = $this->savingJournal($start_date, $end_date, $saving_number, $reference_no, $client_id, $branch_id, $product_type, $perPage);
        }

        if ($report_type == "normal") {
            return view('partials.reports.saving.saving-report', ['savings' => $rows]);
        } else if ($report_type == "customer") {
            return view('partials.reports.saving.saving-customer-report', ['journals' => $rows]);
        }
    }

    public function interestCalReport(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $saving_number = $request->saving_number;
        $reference_no = $request->reference_no;
        $perPage = request('perPage', 10000);
        $client_id = $request->client_id - 0;
        $product_type = $request->saving_product - 0;
        $branch_id = $request->branch_id - 0;
        $transactions = $this->savingTransactions($start_date, $end_date, $saving_number, $reference_no, $client_id, $branch_id, $product_type, $perPage);

        $totals = $this->getTotalCalculation($start_date, $end_date, $saving_number, $reference_no, $client_id, $product_type, $branch_id);

        return view('partials.reports.saving.interest-calculation-report', ['transactions' => $transactions, 'totals' => $totals]);
    }

    public function savingJournal($start_date, $end_date, $saving_number, $reference_no, $client_id, $branch_id, $product_type, $perPage)
    {
        // dd($start_date,$end_date,$reference_no,$client_id,$branch_id);
        return SavingTransaction::join('savings', 'savings.id', 'saving_transactions.saving_id')
            ->join('general_journals', 'general_journals.reference_no', 'saving_transactions.reference')
            ->join('general_journal_details', 'general_journal_details.journal_id', 'general_journals.id')
            ->where(function ($query) use ($start_date, $end_date) {
                if ($start_date != null && $end_date == null) {
                    //dd("date");
                    return $query->whereDate('general_journals.date_general', '<=', $start_date);
                } else if ($start_date != null && $end_date != null) {
                    //dd("date");
                    return $query->whereDate('general_journals.date_general', '>=', $start_date)
                        ->whereDate('general_journals.date_general', '<=', $end_date);
                }
            })
            ->where(function ($query) use ($saving_number) {
                if ($saving_number != null) {
                    return $query->where('savings.saving_number', $saving_number);
                }
            })
            ->where(function ($query) use ($reference_no) {
                if ($reference_no != null) {
                    return $query->where('saving_transactions.reference', $reference_no);
                }
            })
            ->where(function ($query) use ($client_id) {
                if ($client_id != null) {
                    return $query->where('savings.client_id', $client_id);
                }
            })
            ->where(function ($query) use ($branch_id) {
                if ($branch_id > 0) {
                    //dd($branch_id);
                    return $query->where('general_journal_details.branch_id', $branch_id);
                }
            })
            ->where(function ($query) use ($product_type) {
                if ($product_type > 0) {
                    return $query->where('saving_transactions.saving_product_id', $product_type);
                }
            })
            ->whereIn('general_journals.tran_type', ['saving-deposit', 'saving-withdrawal', 'compound-interest'])
            ->where('general_journal_details.dr', '>', 0)
            ->selectRaw('general_journal_details.*, general_journals.reference_no, general_journals.tran_reference,
                                            saving_transactions.saving_id, saving_transactions.total_principal')
            ->orderBy('general_journal_details.j_detail_date', 'asc')
            ->orderBy('general_journal_details.created_at', 'ASC')
            ->paginate($perPage);
    }

    public function savingReport($start_date, $end_date, $saving_number, $client_id, $branch_id, $product_type, $perPage)
    {
        // dd($start_date,$end_date,$reference_no,$client_id,$branch_id);
        return Saving::where(function ($query) use ($saving_number) {
            if ($saving_number != null) {
                return $query->where('savings.saving_number', $saving_number);
            }
        })
            ->where(function ($query) use ($client_id) {
                if ($client_id != null) {
                    return $query->where('savings.client_id', $client_id);
                }
            })
            ->where(function ($query) use ($branch_id) {
                if ($branch_id > 0) {
                    //dd($branch_id);
                    return $query->where('savings.branch_id', $branch_id);
                }
            })
            ->where(function ($query) use ($product_type) {
                if ($product_type > 0) {
                    return $query->where('savings.saving_product_id', $product_type);
                }
            })
            ->orderBy('savings.active_date', 'asc')
            ->paginate($perPage);
    }

    public function savingTransactions($start_date, $end_date, $saving_number, $reference_no, $client_id, $branch_id, $product_type, $perPage)
    {
        // dd($start_date,$end_date,$reference_no,$client_id,$branch_id);
        return SavingTransaction::join('savings', 'savings.id', 'saving_transactions.saving_id')
            ->where(function ($query) use ($start_date, $end_date) {
                if ($start_date != null && $end_date == null) {
                    //dd("date");
                    return $query->whereDate('saving_transactions.date', '<=', $start_date);
                } else if ($start_date != null && $end_date != null) {
                    //dd("date");
                    return $query->whereDate('saving_transactions.date', '>=', $start_date)
                        ->whereDate('saving_transactions.date', '<=', $end_date);
                }
            })
            ->where(function ($query) use ($saving_number) {
                if ($saving_number != null) {
                    return $query->where('savings.saving_number', $saving_number);
                }
            })
            ->where(function ($query) use ($reference_no) {
                if ($reference_no != null) {
                    return $query->where('saving_transactions.reference', $reference_no);
                }
            })
            ->where(function ($query) use ($client_id) {
                if ($client_id != null) {
                    return $query->where('savings.client_id', $client_id);
                }
            })
            ->where(function ($query) use ($branch_id) {
                if ($branch_id > 0) {
                    //dd($branch_id);
                    return $query->where('saving_transactions.branch_id', $branch_id);
                }
            })
            ->where(function ($query) use ($product_type) {
                if ($product_type > 0) {
                    return $query->where('saving_transactions.saving_product_id', $product_type);
                }
            })
            ->selectRaw('saving_transactions.*, savings.center_id, savings.saving_number')
            ->orderBy('saving_transactions.date', 'asc')
            ->orderBy('saving_transactions.created_at', 'ASC')
            ->paginate($perPage);
    }

    public function getTotalCalculation($start_date, $end_date, $saving_number, $reference_no, $client_id, $branch_id, $product_type)
    {

        $totals = [
            'deposit' => 0,
            'withdrawal' => 0,
            'accrue-interest' => 0,
            'compound-interest' => 0
        ];

        foreach ($totals as $tranType => $value) {
            $totals[$tranType] = SavingTransaction::join('savings', 'savings.id', 'saving_transactions.saving_id')
                ->where(function ($query) use ($start_date, $end_date) {
                    if ($start_date != null && $end_date == null) {
                        //dd("date");
                        return $query->whereDate('saving_transactions.date', '<=', $start_date);
                    } else if ($start_date != null && $end_date != null) {
                        //dd("date");
                        return $query->whereDate('saving_transactions.date', '>=', $start_date)
                            ->whereDate('saving_transactions.date', '<=', $end_date);
                    }
                })
                ->where(function ($query) use ($saving_number) {
                    if ($saving_number != null) {
                        return $query->where('savings.saving_number', $saving_number);
                    }
                })
                ->where(function ($query) use ($reference_no) {
                    if ($reference_no != null) {
                        return $query->where('saving_transactions.reference', $reference_no);
                    }
                })
                ->where(function ($query) use ($client_id) {
                    if ($client_id != null) {
                        return $query->where('savings.client_id', $client_id);
                    }
                })
                ->where(function ($query) use ($branch_id) {
                    if ($branch_id > 0) {
                        //dd($branch_id);
                        return $query->where('saving_transactions.branch_id', $branch_id);
                    }
                })
                ->where(function ($query) use ($product_type) {
                    if ($product_type > 0) {
                        return $query->where('saving_transactions.saving_product_id', $product_type);
                    }
                })
                ->where('tran_type', $tranType)
                ->selectRaw('SUM(amount) as amount')->pluck('amount')->first();
        }

        return $totals;
    }
}
