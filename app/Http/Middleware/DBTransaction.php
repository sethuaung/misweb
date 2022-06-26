<?php

namespace App\Http\Middleware;

use App\Models\TUser;
use Closure;
use Illuminate\Http\Response;
class DBTransaction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);

       /* if ($request->isMethod('post')) {
            print('is post method');
        }*/
        if (!$request->isMethod('get')) {
            print('post method');

            $u_id = TUser::min('id');


            if ($u_id > 0) {
                do {
                    $updated = false;
                    //sleep(rand(1,2));
                    try {
                        $updated = TUser::where('id', $u_id)
                            ->update(['updated_by' => rand(1, 9999)]);
                    } catch (\Exception $e) {
                    }
                } while (!$updated);
            }


            \DB::beginTransaction();
            \DB::update('SET time_zone = ?', ['+07:00']);
            try {
                $response = $next($request);
            } catch (\Exception $e) {
                \DB::rollBack();
                //dd(1);
                throw $e;
            }
            if ($response instanceof Response && $response->getStatusCode() > 399) {
                \DB::rollBack();
                //dd(2);
            } else {
                \DB::commit();
                //dd(3);
            }

            return $response;
        }else{
            return $next($request);
        }
    }
}
