<?php

namespace App\Http\Controllers\Api;

use App\Models\Ministry;
use App\Models\Position;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PositionController extends Controller
{
    public function index(Request $request)
    {
        $search_term = $request->input('q');
        $page = $request->input('page');

        if ($search_term) {
            $results = Position::orWhere('title', 'LIKE', '%' . $search_term . '%')
                ->orWhere('description', 'LIKE', '%' . $search_term . '%')
                ->paginate(100);
        } else {
            $results = Position::paginate(100);
        }

        return $results;

    }

    public function show($id)
    {
        return Position::find($id);
    }
}
