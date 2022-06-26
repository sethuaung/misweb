<?php

namespace App\Http\Middleware;

use App\Models\Branch;
use Closure;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class CheckIfAdmin
{
    /**
     * Checked that the logged in user is an administrator.
     *
     * --------------
     * VERY IMPORTANT
     * --------------
     * If you have both regular users and admins inside the same table,
     * change the contents of this method to check that the logged in user
     * is an admin, and not a regular user.
     *
     * @param [type] $user [description]
     *
     * @return bool [description]
     */
    private function checkIfUserIsAdmin($user)
    {
        // return ($user->is_admin == 1);
        return true;
    }

    /**
     * Answer to unauthorized access request.
     *
     * @param [type] $request [description]
     *
     * @return [type] [description]
     */
    private function respondToUnauthorizedRequest($request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response(trans('backpack::base.unauthorized'), 401);
        } else {
            return redirect()->guest(backpack_url('login'));
        }
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->session()->has('branch_id'))
        {

        }else{
            $m = Branch::limit(1)->first();
            session([
                'branch_id'=> optional($m)->id
            ]);
        }

       /* if (backpack_auth()->check()){
            $expiresAt = Carbon::now()->addMinutes(5);
            if (!Cache::has('user-is-online-' . backpack_user()->id)){
                Cache::put('user-is-online-' . backpack_user()->id, true, $expiresAt);
            }
        }*/

        if (backpack_auth()->guest()) {
            return $this->respondToUnauthorizedRequest($request);
        }

        if (!$this->checkIfUserIsAdmin(backpack_user())) {
            return $this->respondToUnauthorizedRequest($request);
        }


        return $next($request);
    }

}
