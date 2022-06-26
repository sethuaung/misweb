<?php

namespace App\Http\Controllers\Api;

use App\Models\NRCprefix;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class NRCprefixController extends Controller
{

    public function index(Request $request, $id)
    {
        if ($id)
        {
            // $results = NRCprefix::whereStateId($id)->pluck('prefix')->toArray();
            $results = NRCprefix::whereStateIdEn($id)->pluck('prefix_en')->toArray();
        }

        return $results;
    }
}
