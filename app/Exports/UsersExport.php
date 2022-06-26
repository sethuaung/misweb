<?php

namespace App\Exports;

use App\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UsersExport implements FromView
{

    public function view(): View
    {
        return view('welcome', [
            'users' => User::exclude(['photo','email_verified_at','created_at','updated_at'])->get()
        ]);
    }


}
