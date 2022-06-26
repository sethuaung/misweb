<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 10/07/2019
 * Time: 10:58 AM
 */

namespace App\Exports;

use App\Models\Client;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Carbon\Carbon;


class ClientInformationExport implements FromView
{
    public function __construct($view, $search = "")
    {
        $this->view = $view;
        $this->data = $search;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $search = $this->data;
        $data = Client::orderBy('id', 'DESC');
        if (!empty($search)) {
            $data->where('referent_no', 'LIKE', '%'.$search.'%');
        }
        $reports = $data->get();

        $params = ['page'=>'page', 'reports'=>$reports];

        return view($this->view, $params);
    }
}