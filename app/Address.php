<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';
    protected $primaryKey = 'code';

//    protected $appends = [
//        'province','district','commune','village'
//    ];

    protected $casts = [
        'code' => 'string'
    ];

    public static function getProvince($country_code = '95',$q = '')
    {
        $m = Address::where('parent_code',$country_code)
            //->where('type','province')
            ->where(function ($query) use($q){
                if($q != '' && $q != null){
                    $query->where('name', 'LIKE', '%'.$q.'%')
                            ->orWhere('description', 'LIKE', '%'.$q.'%');
                }
            })
            ->paginate(100);

        return $m;

    }

    public static function getDistrict($province_code,$q = '')
    {
        //$province_code = $province_code -0<10?'0'.($province_code-0):$province_code;

        //$province_code = $province_code -0<10?'0'.($province_code-0):$province_code;



        $m = Address::where('parent_code',$province_code)
           // ->where('type','districts')
            ->where(function ($query) use($q){
                if($q != '' && $q != null){
                    $query->where('name', 'LIKE', '%'.$q.'%')
                        ->orWhere('description', 'LIKE', '%'.$q.'%');
                }
            })->paginate(100);

        return $m;
    }

    public static function getCommune($district_code, $q = '')
    {
        $m = Address::where('parent_code',$district_code)
            //->where('type','commune')
            ->where(function ($query) use($q){
                if($q != '' && $q != null){
                    $query->where('name', 'LIKE', '%'.$q.'%')
                        ->orWhere('description', 'LIKE', '%'.$q.'%');
                }
            })->paginate(100);

        return $m;
    }

    public static function getVillage($commune_code, $q='')
    {
        $m = Address::where('parent_code',$commune_code)
            //->where('type','village')
            ->where(function ($query) use($q){
                if($q != '' && $q != null){
                    $query->where('name', 'LIKE', '%'.$q.'%')
                        ->orWhere('description', 'LIKE', '%'.$q.'%');
                }
            })
            ->paginate(100);

        return $m;
    }


}
