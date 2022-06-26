<?php

namespace App;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;

class ModelHasPermission extends Model
{
    protected $table = 'model_has_permissions';

    public static function getPermissionRoleIds($user_id){
        $arr = [];

        $rows = ModelHasRole::where('model_id',$user_id)->get();

        if($rows != null) {

            foreach ($rows as $r) {
                $role_id = $r->role_id;
                $m = RoleHasPermission::where('role_id', $role_id)->get();

                if ($m != null) {

                        foreach ($m as $row) {
                            $arr[$row->permission_id] = $row->permission_id;
                        }

                }
            }

        }

        $ppm = self::where('model_id',$user_id)->get();
        if($ppm != null){

                foreach ($ppm as $row){
                    $arr[$row->permission_id] = $row->permission_id;
                }

        }

        return $arr;
    }


    public static function can($permission){

        if(backpack_auth()->check()) {
            $user_id = backpack_auth()->id();

            //return true;
            if($user_id == 1) return true;
            //dd($user_id);
            $arrPermission = self::getPermissionRoleIds($user_id);
            $pp = strtolower($permission);

            if ($arrPermission != null) {

                if (is_array($arrPermission)) {
                    if (count($arrPermission) > 0) {
                        $m = Permission::where('name', $pp)->whereIn('id', $arrPermission)->first();
                        if ($m != null) {
                            return true;
                        }
                    }
                }
            }
        }

        return false;

    }

}
