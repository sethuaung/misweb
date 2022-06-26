<?php
namespace App\Src\Backpack;

class CrudController extends \Backpack\CRUD\app\Http\Controllers\CrudController
{
    public function __construct()
    {
        if (! $this->crud) {
            $this->crud = app()->make(CrudPanel::class);
            $this->middleware(function ($request, $next) {
                $this->request = $request;
                $this->crud->request = $request;
                $this->setup();

                return $next($request);
            });
        }
    }
}
?>
