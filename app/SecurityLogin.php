<?php

namespace App;

use App\Models\SSLKey;
use Illuminate\Database\Eloquent\Model;

class SecurityLogin extends Model
{
    public $private_key;
    protected $table = 'security_login';

    //protected $appends = ['private_key'];

    public static function createSslKey($arr = [])
    {
        $m = self::getSslKey($arr);

        if ($m != null) {
            $m->delete();
        }

        $m = new static();
        if ($m != null) {
            $m->user_id = isset($arr['user_id']) ? $arr['user_id'] : 0;
            $m->user_type = isset($arr['user_type']) ? $arr['user_type'] : 1;
            $m->mac_address = isset($arr['mac_address']) ? $arr['mac_address'] : '';
            $m->machine_serial_number = isset($arr['machine_serial_number']) ? $arr['machine_serial_number'] : '';
            //$m->device_type = isset($arr['device_type']) ? $arr['device_type'] : '';
            //$m->device_browser_type = isset($arr['device_browser_type']) ? $arr['device_browser_type'] : '';
            //$m->from_location = isset($arr['from_location']) ? $arr['from_location'] : '';
            //$m->ip_address = isset($arr['ip_address']) ? $arr['ip_address'] : '';

            if ($m->save()) {

                //=============
                //=============
                //$_bcrypt = self::encrypt($m->id . '#' . $m->user_id . '#' . $m->mac_address . '#' . $m->machine_serial_number);
                $_bcrypt = ($m->id . '#' . $m->user_id . '#' . $m->mac_address . '#' . $m->machine_serial_number);
                //$___bcrypt = bcrypt($_bcrypt);
                $___bcrypt =   app('hash')->make($_bcrypt);
                //============
                $k = new SSLKey();

                $k->setPassprase($_bcrypt);

                $k->genPrivateKey();
                $k->genPublicKey();

                //=============
                //=============
                if ($k->compareKey($___bcrypt)) {
                    $m->public_key = $k->getPublicKey();
                    $m->bcrypt = $___bcrypt;

                    $m->private_key = $k->getPrivateKey();

                    if ($m->save()) {

                        return $m;
                    }
                }

            }


        }

        return null;


    }

    public static function getSslKey($arr = [])
    {
        return self::where('user_id', isset($arr['user_id']) ? $arr['user_id'] : 0)
            ->where('user_type', isset($arr['user_type']) ? $arr['user_type'] : 1)
            ->where('mac_address', isset($arr['mac_address']) ? $arr['mac_address'] : '')
            ->where('machine_serial_number', isset($arr['machine_serial_number']) ? $arr['machine_serial_number'] : '')
            ->first();
    }

    static function encrypt($string)
    {

        $len = strlen($string);
        //loop through it and print it reverse
        $re = '';
        if ($len == 1) {
            return $string . rand(0, 9) . chr(rand(97, 122)) . chr(rand(97, 122));
        }
        for ($i = $len - 1; $i >= 0; $i--) {
            $re .= substr($string, $i, 1) . rand(0, 9) . chr(rand(98, 122)) . chr(rand(98, 122));
        }
        //$re = self::_Encode($re,1);
        return $re;

    }

    static function decrypt($string)
    {

        //$string = self::_Decode($string);

        $len = strlen($string);
        //loop through it and print it reverse
        $re = '';
        $r = 0;
        for ($i = $len - 1; $i >= 0; $i--) {
            $r++;
            if ($r % 4 == 0) $re .= substr($string, $i, 1);
        }
        return $re;

    }


}
