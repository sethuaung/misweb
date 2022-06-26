<?php

namespace App\Http\Controllers\Api;



use App\Models\Guarantor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GuarantorController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term)
        {
            if (companyReportPart() == 'company.bolika') {

                $results = Guarantor::orWhere('full_name_en', 'LIKE', '%' . $search_term . '%')
                    ->orWhere('full_name_mm', 'LIKE', '%' . $search_term . '%')
                    ->orWhere('phone', 'LIKE', '%' . $search_term . '%')
                    ->orWhere('full_name_mm', 'LIKE', '%' . $search_term . '%')
                    ->orWhere('nrc_number', 'LIKE', '%' . $search_term . '%')
                    ->selectRaw("id,CONCAT(full_name_en,' ', full_name_mm) as full_name_en")
                    ->paginate(100);

            }else{
                $results = Guarantor::orWhere('full_name_en', 'LIKE', '%' . $search_term . '%')
                    ->orWhere('full_name_mm', 'LIKE', '%' . $search_term . '%')
                    ->orWhere('phone', 'LIKE', '%' . $search_term . '%')
                    ->orWhere('full_name_mm', 'LIKE', '%' . $search_term . '%')
                    ->orWhere('nrc_number', 'LIKE', '%' . $search_term . '%')
                    ->paginate(100);
            }

        }
        else
        {
            if (companyReportPart() == 'company.bolika') {
                $results = Guarantor::selectRaw("id,CONCAT(full_name_en,' ', full_name_mm) as full_name_en")
                ->paginate(10);
            }else{
                $results = Guarantor::paginate(10);
            }
        }

        return $results;
    }

    public function show($id)
    {
        return Guarantor::find($id);
    }
}
