<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class ReportSRD extends Model
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
        return $this->belongsTo(AccountChartExternal::class, 'acc_chart_id');
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public static function getAccountBalAll($acc_chart_id = [], $start_date = null, $end_date = null, $acc_section_id = [], $is_begin = false)
    {

        return GeneralJournalDetail::where(function ($query) use ($acc_chart_id) {
            if (is_array($acc_chart_id)) {
                if (count($acc_chart_id) > 0) {
                    return $query->whereIn('external_acc_id', $acc_chart_id);
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
            ->selectRaw('section_id,external_acc_id,sum(dr) as t_dr, sum(cr) as t_cr')
            ->groupBy('section_id', 'external_acc_id')->get();

    }
    
    public static function getAccountBalAllQuicken($acc_chart_id, $branch_id = null, $start_date = null, $end_date = null, $is_begin = false)
    {
        //dd($branch_id,$acc_chart_id,$start_date,$end_date);
        if($start_date != Null){
            $date_start=date_create($start_date);
        }
        
        $date_end=date_create($end_date);
        
        if($start_date != Null){
            $start_date = date_format($date_start,"Y-m-d");
        }
        
        $end_date = date_format($date_end,"Y-m-d");
        $acc_code = AccountChart::where('code',$acc_chart_id)->first();
        //dd($acc_code,$acc_chart_id);
        $dr =  GeneralJournalDetail::where(function ($query) use ($acc_chart_id) {
            if (isset($acc_chart_id)) {
                return $query->where('acc_chart_code', 'LIKE', $acc_chart_id . '%');
            }
        })
        ->where(function ($query) use ($start_date, $end_date, $is_begin) {
            if ($start_date != null && $end_date == null) {
                //dd("this one");
                if ($is_begin) {
                    return $query->whereDate('j_detail_date', '<', $start_date);
                } else {
                    return $query->whereDate('j_detail_date', '<=', $start_date);
                }
            } elseif ($start_date != null && $end_date != null) {
                //dd("this two");
                return $query->where('j_detail_date', '>=', $start_date)
                    ->where('j_detail_date', '<=', $end_date);
            }
            elseif($start_date == null && $end_date != null){
                //dd("this three");
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
                        if ($branch_id > 0) {
                            $query->where('branch_id', $branch_id);
                        }
                    }
                }
            })
            ->sum('dr');

            $cr =  GeneralJournalDetail::where(function ($query) use ($acc_chart_id) {
                if (isset($acc_chart_id)) {
                    return $query->where('acc_chart_code', 'LIKE', $acc_chart_id . '%');
                }
            })
            ->where(function ($query) use ($start_date, $end_date, $is_begin) {
                if ($start_date != null && $end_date == null) {
                    if ($is_begin) {
                        return $query->whereDate('j_detail_date', '<', $start_date);
                    } else {
                        return $query->whereDate('j_detail_date', '<=', $start_date);
                    }
                } elseif ($start_date != null && $end_date != null) {
                    return $query->where('j_detail_date', '>=', $start_date)
                        ->where('j_detail_date', '<=', $end_date);
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
                            if ($branch_id > 0) {
                                $query->where('branch_id', $branch_id);
                            }
                        }
                    }
                })
                ->sum('cr');
            //dd($dr,$cr);
            $balance = collect([
                (object) [
                    'acc_chart_id' => optional($acc_code)->id,
                    'amt' => $dr-$cr,
                ],
            ]);
            //dd($balance);
            return $balance;
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
            ->selectRaw('section_id,external_acc_id,job_id,sum(dr) as t_dr, sum(cr) as t_cr')
            ->groupBy('section_id', 'external_acc_id', 'job_id')->get();

    }

    public static function getAccountBalAllByClass($acc_chart_id = [], $start_date = null, $end_date = null, $acc_section_id = [], $is_begin = false)
    {

        return GeneralJournalDetail::where(function ($query) use ($acc_chart_id) {
            if (is_array($acc_chart_id)) {
                if (count($acc_chart_id) > 0) {
                    return $query->whereIn('external_acc_id', $acc_chart_id);
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
            ->selectRaw('section_id,external_acc_id,class_id,sum(dr) as t_dr, sum(cr) as t_cr')
            ->groupBy('section_id', 'external_acc_id', 'class_id')->get();

    }

    public static function getRetainedEarning($start_date = null, $end_date = null, $is_begin = false)
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

        })->whereIn('section_id', [40, 50, 60, 70, 80])
            ->selectRaw('sum(dr-cr) as bal')->first();
    }

    public static function getTransactionDetail($acc_chart_id = [], $start_date = null, $end_date = null, $acc_section_id = [], $is_begin = false)
    {
        return GeneralJournalDetail::where(function ($query) use ($acc_chart_id) {
            if (is_array($acc_chart_id)) {
                if (count($acc_chart_id) > 0) {
                    return $query->whereIn('external_acc_id', $acc_chart_id);
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

    public static function getCashStatement($start_date = null, $end_date = null, $is_begin = false)
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

        })->where('section_id', 10)
            ->selectRaw('external_acc_id,tran_type,sum(dr-cr) as bal')
            ->groupBy('external_acc_id', 'tran_type')
            ->havingRaw('sum(dr-cr)<>0')
            ->get();
    }

    public static function getCashStatementDetail($start_date = null, $end_date = null, $is_begin = false)
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

        })->where('section_id', 10)
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
