<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class ReportAccounting extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'general_journal_details';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function acc_chart()
    {
        return $this->belongsTo(AccountChart::class, 'acc_chart_id');
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public static function getAccountBalAllB($branch_id = null, $acc_chart_id = [], $start_date = null, $end_date = null, $acc_section_id = [], $is_begin = false)
    {
        return self::getAccountBalAll($acc_chart_id, $start_date, $end_date, $acc_section_id, $is_begin, $branch_id);
    }

    public static function getAccountBalAll($acc_chart_id = [], $start_date = null, $end_date = null, $acc_section_id = [], $is_begin = false, $branch_id = null)
    {

        return GeneralJournalDetail::where(function ($query) use ($acc_chart_id) {
            if (is_array($acc_chart_id)) {
                if (count($acc_chart_id) > 0) {
                    return $query->whereIn('acc_chart_id', $acc_chart_id);
                }
            }
        })->where(function ($query) use ($start_date, $end_date, $is_begin) {
            if ($start_date != null && $end_date == null) {
                if ($is_begin) {
                    return $query->whereDate('j_detail_date', '<', $start_date);
                } else {
                    return $query->whereDate('j_detail_date', '<=', $start_date);
                }
            } else if ($start_date != null && $end_date != null) {
                return $query->whereDate('j_detail_date', '>=', $start_date)
                    ->whereDate('j_detail_date', '<=', $end_date);
            }
            elseif($start_date == null && $end_date != null){
                return $query->where('j_detail_date', '<=', $end_date);
            }

        })
            ->where(function ($query) use ($branch_id) {
                if ($branch_id != null) {
                    if (is_array($branch_id)) {
                        if (count($branch_id) > 0) {
                            $query->whereIn('branch_id', $branch_id);
                        }
                    } else {
                        if (count($branch_id) > 0) {
                            $query->where('branch_id', $branch_id);
                        }
                    }
                }
            })
            ->where(function ($query) use ($acc_section_id) {
                if ($acc_section_id != null) {
                    if (is_array($acc_section_id)) {
                        if (count($acc_section_id) > 0) {
                            $query->whereIn('section_id', $acc_section_id);
                        }
                    } else {
                        if ($acc_section_id > 0) {
                            $query->where('section_id', $acc_section_id);
                        }
                    }
                }
            })
            ->selectRaw('section_id,acc_chart_id,branch_id,sum(dr) as t_dr, sum(cr) as t_cr, tran_type')
            ->groupBy('section_id', 'acc_chart_id', 'branch_id')
            ->get();

    }

    public static function getAccountBalAllByLoanBranch($acc_chart_id = [], $start_date = null, $end_date = null, $acc_section_id = [], $is_begin = false, $branch_id = null)
    {

        return GeneralJournalDetail::
        join('general_journals', 'general_journal_details.journal_id', '=', 'general_journals.id')
            ->join('loan_payments', 'general_journals.tran_id', '=', 'loan_payments.id')
            ->join(getLoanTable(), 'loan_payments.disbursement_id', '=', getLoanTable() . '.id')
            ->where(function ($query) use ($acc_chart_id) {
                if (is_array($acc_chart_id)) {
                    if (count($acc_chart_id) > 0) {
                        return $query->whereIn('general_journal_details.acc_chart_id', $acc_chart_id);
                    }
                }
            })->where(function ($query) use ($start_date, $end_date, $is_begin) {
                if ($start_date != null && $end_date == null) {
                    if ($is_begin) {
                        return $query->whereDate('general_journal_details.j_detail_date', '<', $start_date);
                    } else {
                        return $query->whereDate('general_journal_details.j_detail_date', '<=', $start_date);
                    }
                } else if ($start_date != null && $end_date != null) {
                    return $query->whereDate('general_journal_details.j_detail_date', '>=', $start_date)
                        ->whereDate('general_journal_details.j_detail_date', '<=', $end_date);

                    // return $query->whereDate('j_detail_date','<=',$end_date);
                }

            })
            ->where(function ($query) use ($branch_id) {
                if ($branch_id != null) {
                    if (is_array($branch_id)) {
                        if (count($branch_id) > 0) {
                            $query->whereIn(getLoanTable() . '.branch_id', $branch_id);
                        }
                    } else {
                        if ($branch_id > 0) {
                            $query->where(getLoanTable() . '.branch_id', $branch_id);
                        }
                    }
                }
            })
            ->where(function ($query) use ($acc_section_id) {
                if ($acc_section_id != null) {
                    if (is_array($acc_section_id)) {
                        if (count($acc_section_id) > 0) {
                            $query->whereIn('general_journal_details.section_id', $acc_section_id);
                        }
                    } else {
                        if ($acc_section_id > 0) {
                            $query->where('general_journal_details.section_id', $acc_section_id);
                        }
                    }
                }
            })
            ->selectRaw('general_journal_details.section_id,general_journal_details.acc_chart_id,loans.branch_id,sum(dr) as t_dr, sum(cr) as t_cr')
            ->groupBy('general_journal_details.section_id', 'general_journal_details.acc_chart_id', getLoanTable() . '.branch_id')
            ->get();

    }

    public static function getAccountBalAllByJob($acc_chart_id = [], $start_date = null, $end_date = null, $acc_section_id = [], $is_begin = false)
    {

        return GeneralJournalDetail::where(function ($query) use ($acc_chart_id) {
            if (is_array($acc_chart_id)) {
                if (count($acc_chart_id) > 0) {
                    return $query->whereIn('acc_chart_id', $acc_chart_id);
                }
            }
        })
            ->whereNotNull('job_id')
            ->where('job_id', '>', 0)
            ->where(function ($query) use ($start_date, $end_date, $is_begin) {
                if ($start_date != null && $end_date == null) {
                    if ($is_begin) {
                        return $query->whereDate('j_detail_date', '<', $start_date);
                    } else {
                        return $query->whereDate('j_detail_date', '<=', $start_date);
                    }
                } else if ($start_date != null && $end_date != null) {
                    return $query->whereDate('j_detail_date', '>=', $start_date)
                        ->whereDate('j_detail_date', '<=', $end_date);
                }
            })
            ->where(function ($query) use ($acc_section_id) {
                if ($acc_section_id != null) {
                    if (is_array($acc_section_id)) {
                        if (count($acc_section_id) > 0) {
                            $query->whereIn('section_id', $acc_section_id);
                        }
                    } else {
                        if ($acc_section_id > 0) {
                            $query->where('section_id', $acc_section_id);
                        }
                    }
                }
            })
            ->selectRaw('section_id,acc_chart_id,job_id,sum(dr) as t_dr, sum(cr) as t_cr')
            ->groupBy('section_id', 'acc_chart_id', 'job_id')->get();

    }

    public static function getAccountBalAllByClass($acc_chart_id = [], $start_date = null, $end_date = null, $acc_section_id = [], $is_begin = false)
    {

        return GeneralJournalDetail::where(function ($query) use ($acc_chart_id) {
            if (is_array($acc_chart_id)) {
                if (count($acc_chart_id) > 0) {
                    return $query->whereIn('acc_chart_id', $acc_chart_id);
                }
            }
        })
            ->whereNotNull('class_id')
            ->where('class_id', '>', 0)
            ->where(function ($query) use ($start_date, $end_date, $is_begin) {
                if ($start_date != null && $end_date == null) {
                    if ($is_begin) {
                        return $query->whereDate('j_detail_date', '<', $start_date);
                    } else {
                        return $query->whereDate('j_detail_date', '<=', $start_date);
                    }
                } else if ($start_date != null && $end_date != null) {
                    return $query->whereDate('j_detail_date', '>=', $start_date)
                        ->whereDate('j_detail_date', '<=', $end_date);
                }

            })
            ->where(function ($query) use ($acc_section_id) {
                if ($acc_section_id != null) {
                    if (is_array($acc_section_id)) {
                        if (count($acc_section_id) > 0) {
                            $query->whereIn('section_id', $acc_section_id);
                        }
                    } else {
                        if ($acc_section_id > 0) {
                            $query->where('section_id', $acc_section_id);
                        }
                    }
                }
            })
            ->selectRaw('section_id,acc_chart_id,class_id,sum(dr) as t_dr, sum(cr) as t_cr')
            ->groupBy('section_id', 'acc_chart_id', 'class_id')->get();

    }

    public static function getRetainedEarning($start_date = null, $end_date = null, $is_begin = false, $branch_id = [])
    {

        return GeneralJournalDetail::where(function ($query) use ($start_date, $end_date, $is_begin) {
            if ($start_date != null && $end_date == null) {
                if ($is_begin) {
                    return $query->whereDate('j_detail_date', '<', $start_date);
                } else {
                    return $query->whereDate('j_detail_date', '<=', $start_date);
                }
            } else if ($start_date != null && $end_date != null) {
                return $query->whereDate('j_detail_date', '>=', $start_date)
                    ->whereDate('j_detail_date', '<=', $end_date);
            }

        })
            ->where(function ($query) use ($branch_id) {
                if ($branch_id != null) {
                    if (is_array($branch_id)) {
                        if (count($branch_id) > 0) {
                            $query->whereIn('branch_id', $branch_id);
                        }
                    } else {
                        if ($branch_id > 0) {
                            $query->where('branch_id', $branch_id);
                        }
                    }
                }
            })
            ->whereIn('section_id', [30, 40, 50, 60, 70, 80])
            ->selectRaw('branch_id,sum(dr-cr) as bal')
            ->groupBy('branch_id')->get();
    }

    public static function getTransactionDetail($acc_chart_id = [], $start_date = null, $end_date = null, $acc_section_id = [], $is_begin = false)
    {
        return GeneralJournalDetail::where(function ($query) use ($acc_chart_id) {
            if (is_array($acc_chart_id)) {
                if (count($acc_chart_id) > 0) {
                    return $query->whereIn('acc_chart_id', $acc_chart_id);
                }
            }
        })->where(function ($query) use ($start_date, $end_date, $is_begin) {
            if ($start_date != null && $end_date == null) {
                if ($is_begin) {
                    return $query->whereDate('j_detail_date', '<', $start_date);
                } else {
                    return $query->whereDate('j_detail_date', '<=', $start_date);
                }
            } else if ($start_date != null && $end_date != null) {
                return $query->whereDate('j_detail_date', '>=', $start_date)
                    ->whereDate('j_detail_date', '<=', $end_date);
            }

        })
            ->where(function ($query) use ($acc_section_id) {
                if ($acc_section_id != null) {
                    if (is_array($acc_section_id)) {
                        if (count($acc_section_id) > 0) {
                            $query->whereIn('section_id', $acc_section_id);
                        }
                    } else {
                        if ($acc_section_id > 0) {
                            $query->where('section_id', $acc_section_id);
                        }
                    }
                }
            })
            ->get();
    }


    public static function getCashStatement($start_date = null, $end_date = null, $is_begin = false, $branch_id = [], $acc_chart_id = [])
    {

        return GeneralJournalDetail::where(function ($query) use ($acc_chart_id) {
            if (is_array($acc_chart_id)) {
                if (count($acc_chart_id) > 0) {
                    return $query->whereIn('acc_chart_id', $acc_chart_id);
                }
            }
        })
            ->where(function ($query) use ($start_date, $end_date, $is_begin) {
                if ($start_date != null && $end_date == null) {
                    if ($is_begin) {
                        return $query->whereDate('j_detail_date', '<', $start_date);
                    } else {
                        return $query->whereDate('j_detail_date', '<=', $start_date);
                    }
                } else if ($start_date != null && $end_date != null) {
                    return $query->whereDate('j_detail_date', '>=', $start_date)
                        ->whereDate('j_detail_date', '<=', $end_date);
                }

            })
            ->where(function ($query) use ($branch_id) {
                if ($branch_id != null) {
                    if (is_array($branch_id)) {
                        if (count($branch_id) > 0) {
                            $query->whereIn('branch_id', $branch_id);
                        }
                    } else {
                        if ($branch_id > 0) {
                            $query->where('branch_id', $branch_id);
                        }
                    }
                }
            })
            ->where('section_id', 10)
            ->selectRaw('acc_chart_id,branch_id,tran_type,sum(dr-cr) as bal')
            ->groupBy('acc_chart_id', 'tran_type', 'branch_id')
            ->havingRaw('sum(dr-cr)<>0')
            ->get();
    }

    public static function getCashStatementDetail($start_date = null, $end_date = null, $is_begin = false, $branch_id = [], $acc_chart_id = [])
    {

        return GeneralJournalDetail::where(function ($query) use ($acc_chart_id) {
            if (is_array($acc_chart_id)) {
                if (count($acc_chart_id) > 0) {
                    return $query->whereIn('acc_chart_id', $acc_chart_id);
                }
            }
        })
            ->where(function ($query) use ($start_date, $end_date, $is_begin) {
                if ($start_date != null && $end_date == null) {
                    if ($is_begin) {
                        return $query->whereDate('j_detail_date', '<', $start_date);
                    } else {
                        return $query->whereDate('j_detail_date', '<=', $start_date);
                    }
                } else if ($start_date != null && $end_date != null) {
                    return $query->whereDate('j_detail_date', '>=', $start_date)
                        ->whereDate('j_detail_date', '<=', $end_date);
                }

            })
            ->where(function ($query) use ($branch_id) {
                if ($branch_id != null) {
                    if (is_array($branch_id)) {
                        if (count($branch_id) > 0) {
                            $query->whereIn('branch_id', $branch_id);
                        }
                    } else {
                        if ($branch_id > 0) {
                            $query->where('branch_id', $branch_id);
                        }
                    }
                }
            })
            ->where('section_id', 10)
            ->orderBy('j_detail_date', 'asc')
            ->get();
    }

    public static function getRepaymentGeneralLeger($start_date = null, $end_date = null, $branch_id = [], $acc_chart_id = [])
    {
        return GeneralJournalDetail::
        select('general_journal_details.*')
            ->join('loan_payments', 'loan_payments.id', '=', 'general_journal_details.tran_id')
            ->join('loans', 'loans.id', '=', 'loan_payments.disbursement_id')
            ->where(function ($query) use ($acc_chart_id) {
                if (is_array($acc_chart_id)) {
                    if (count($acc_chart_id) > 0) {
                        return $query->whereIn('general_journal_details.acc_chart_id', $acc_chart_id);
                    }
                }
            })
            ->where(function ($query) use ($start_date, $end_date) {
                if ($start_date != null && $end_date != null) {
                    return $query->whereDate('general_journal_details.j_detail_date', '>=', $start_date)
                        ->whereDate('general_journal_details.j_detail_date', '<=', $end_date);
                }
            })
            ->where(function ($query) use ($branch_id) {
                if ($branch_id != null) {
                    return $query->whereIn('general_journal_details.branch_id', $branch_id)
                        ->orWhereIn('loans.branch_id', $branch_id);
                }
            })
            ->where('general_journal_details.tran_type', 'payment')
            ->orderBy('general_journal_details.j_detail_date', 'asc')
            ->get();
    }

    public static function getGeneralLeger($start_date = null, $end_date = null, $branch_id = [], $acc_chart_id = [])
    {
        return GeneralJournalDetail::where(function ($query) use ($acc_chart_id) {
            if (is_array($acc_chart_id)) {
                if (count($acc_chart_id) > 0) {
                    return $query->whereIn('acc_chart_id', $acc_chart_id);
                }
            }
        })
            ->where(function ($query) use ($start_date, $end_date) {
                if ($start_date != null && $end_date != null) {
                    return $query->whereDate('j_detail_date', '>=', $start_date)
                        ->whereDate('j_detail_date', '<=', $end_date);
                }

            })
            ->where(function ($query) use ($branch_id) {
                if ($branch_id != null) {
                    if (is_array($branch_id)) {
                        if (count($branch_id) > 0) {
                            $query->whereIn('branch_id', $branch_id);
                        }
                    } else {
                        if ($branch_id > 0) {
                            $query->where('branch_id', $branch_id);
                        }
                    }
                }
            })
            ->orderBy('j_detail_date', 'asc')
            ->get();
    }

    public static function getBeginGeneralLeger($start_date = null, $end_date = null, $branch_id = [], $acc_chart_id = [])
    {
        return GeneralJournalDetail::where(function ($query) use ($acc_chart_id) {
            if (is_array($acc_chart_id)) {
                if (count($acc_chart_id) > 0) {
                    return $query->whereIn('acc_chart_id', $acc_chart_id);
                }
            }
        })
            ->where(function ($query) use ($start_date, $end_date) {
                if ($start_date != null) {
                    return $query->whereDate('j_detail_date', '<', $start_date);
                }

            })
            ->where(function ($query) use ($branch_id) {
                if ($branch_id != null) {
                    if (is_array($branch_id)) {
                        if (count($branch_id) > 0) {
                            $query->whereIn('branch_id', $branch_id);
                        }
                    } else {
                        if ($branch_id > 0) {
                            $query->where('branch_id', $branch_id);
                        }
                    }
                }
            })
            ->selectRaw('acc_chart_id,sum(dr-cr) as amt')
            ->groupBy('acc_chart_id')
            ->get();
    }


    public static function getBeginGeneralLegerMoeyan($start_date = null, $end_date = null, $branch_id = [], $acc_chart_id = [])
    {
        $r_begin_dr = \App\Models\GeneralJournalDetail::where('acc_chart_id', $acc_chart_id)
            ->whereDate('j_detail_date', '<', $start_date)
            ->where(function ($query) use ($acc_chart_id) {
                if (is_array($acc_chart_id)) {
                    if (count($acc_chart_id) > 0) {
                        return $query->whereIn('acc_chart_id', $acc_chart_id);
                    }
                }
            })
            ->where('branch_id', $branch_id)
            ->sum('dr');

        $r_begin_cr = \App\Models\GeneralJournalDetail::where('acc_chart_id', $acc_chart_id)
            ->whereDate('j_detail_date', '<', $start_date)
            ->where(function ($query) use ($acc_chart_id) {
                if (is_array($acc_chart_id)) {
                    if (count($acc_chart_id) > 0) {
                        return $query->whereIn('acc_chart_id', $acc_chart_id);
                    }
                }
            })
            ->where('branch_id', $branch_id)
            ->sum('cr');

        $r_begin = $r_begin_dr - $r_begin_cr;

        return $r_begin;

    }


    public static function getCashTransaction($start_date = null, $end_date = null, $branch_id = [], $acc_chart_id = [])
    {

        return GeneralJournalDetail::where(function ($query) use ($acc_chart_id) {
            if (is_array($acc_chart_id)) {
                if (count($acc_chart_id) > 0) {
                    return $query->whereIn('acc_chart_id', $acc_chart_id);
                }
            }
        })
            ->where(function ($query) use ($start_date, $end_date) {
                if ($start_date != null && $end_date != null) {
                    return $query->whereDate('j_detail_date', '>=', $start_date)
                        ->whereDate('j_detail_date', '<=', $end_date);
                }

            })
            ->where(function ($query) use ($branch_id) {
                if ($branch_id != null) {
                    if (is_array($branch_id)) {
                        if (count($branch_id) > 0) {
                            $query->whereIn('branch_id', $branch_id);
                        }
                    } else {
                        if ($branch_id > 0) {
                            $query->where('branch_id', $branch_id);
                        }
                    }
                }
            })
            ->where('section_id', 10)
            ->orderBy('j_detail_date', 'asc')
            ->get();
    }

    public static function getBeginCashTransaction($start_date = null, $end_date = null, $branch_id = [], $acc_chart_id = [])
    {

        return GeneralJournalDetail::where(function ($query) use ($acc_chart_id) {
            if (is_array($acc_chart_id)) {
                if (count($acc_chart_id) > 0) {
                    return $query->whereIn('acc_chart_id', $acc_chart_id);
                }
            }
        })
            ->where(function ($query) use ($start_date, $end_date) {
                if ($start_date != null) {
                    return $query->whereDate('j_detail_date', '<', $start_date);
                }

            })
            ->where(function ($query) use ($branch_id) {
                if ($branch_id != null) {
                    if (is_array($branch_id)) {
                        if (count($branch_id) > 0) {
                            $query->whereIn('branch_id', $branch_id);
                        }
                    } else {
                        if ($branch_id > 0) {
                            $query->where('branch_id', $branch_id);
                        }
                    }
                }
            })
            ->where('section_id', 10)
            ->selectRaw('acc_chart_id,sum(dr-cr) as amt')
            ->groupBy('acc_chart_id')
            ->get();
    }


    public static function getCashBook($start_date = null, $end_date = null, $is_begin = false, $branch_id = [], $acc_chart_id = [])
    {

        return GeneralJournalDetail::where(function ($query) use ($acc_chart_id) {
            if (is_array($acc_chart_id)) {
                if (count($acc_chart_id) > 0) {
                    return $query->whereIn('acc_chart_id', $acc_chart_id);
                }
            }
        })
            ->where(function ($query) use ($start_date, $end_date, $is_begin) {
                if ($start_date != null && $end_date == null) {
                    if ($is_begin) {
                        return $query->whereDate('j_detail_date', '<', $start_date);
                    } else {
                        return $query->whereDate('j_detail_date', '<=', $start_date);
                    }
                } else if ($start_date != null && $end_date != null) {
                    return $query->whereDate('j_detail_date', '>=', $start_date)
                        ->whereDate('j_detail_date', '<=', $end_date);
                }

            })
            ->where(function ($query) use ($branch_id) {
                if ($branch_id != null) {
                    if (is_array($branch_id)) {
                        if (count($branch_id) > 0) {
                            $query->whereIn('branch_id', $branch_id);
                        }
                    } else {
                        if ($branch_id > 0) {
                            $query->where('branch_id', $branch_id);
                        }
                    }
                }
            })
            ->where('section_id', 10)
            ->selectRaw('branch_id,acc_chart_id')
            ->groupBy('branch_id', 'acc_chart_id')
//            ->havingRaw('sum(dr-cr)<>0')
            ->get();
    }



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
