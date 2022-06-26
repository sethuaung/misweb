<?php

namespace App\Console\Commands;

use App\Helpers\S;
use App\Models\GeneralJournalDetail;
use App\Models\GeneralJournalDetailTem;
use Illuminate\Console\Command;

class TransferJournal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:tj';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer Journal';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //S::insGenTemToGen();
        GeneralJournalDetailTem::chunk(500, function($data) {
            foreach ($data as $g) {
                $m = new GeneralJournalDetail();
                $m->section_id = $g->section_id;
                $m->journal_id = $g->journal_id;
                $m->currency_id = $g->currency_id;
                $m->acc_chart_id = $g->acc_chart_id;
                $m->dr = $g->dr;
                $m->cr = $g->cr;
                $m->j_detail_date = $g->j_detail_date;
                $m->description = $g->description;
                $m->tran_id = $g->tran_id;
                $m->tran_type = $g->tran_type;
                $m->class_id = $g->class_id;
                $m->job_id = $g->job_id;
                $m->name = $g->name;
                $m->created_at = $g->created_at;
                $m->updated_at = $g->updated_at;
                $m->exchange_rate = $g->exchange_rate;
                $m->currency_cal = $g->currency_cal;
                $m->dr_cal = $g->dr_cal;
                $m->cr_cal = $g->cr_cal;
                $m->sub_section_id = $g->sub_section_id;
                $m->created_by = $g->created_by;
                $m->updated_by = $g->updated_by;
                $m->branch_id = $g->branch_id;
                $m->external_acc_id = $g->external_acc_id;
                $m->acc_chart_code = $g->acc_chart_code;
                $m->external_acc_chart_id = $g->external_acc_chart_id;
                $m->external_acc_chart_code = $g->external_acc_chart_code;
                $m->round_id = $g->round_id;
                $m->warehouse_id = $g->warehouse_id;
                $m->cash_flow_code = $g->cash_flow_code;
                $m->product_id = $g->product_id;
                $m->category_id = $g->category_id;
                $m->qty = $g->qty;
                $m->sale_price = $g->sale_price;
                if($m->save()){
                    GeneralJournalDetailTem::where('id',$g->id)->delete();
//                    $this->info('The transfer has been proceed successfully.');
                }
            }
        });

    }
}
