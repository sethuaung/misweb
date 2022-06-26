<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreFixSeq extends Model
{
    protected $table = 'pre_fix_seq';

    public static function getSeq($key, $year)
    {
        $m = self::where('key', $key)->where('year', $year)->first();
        if ($m == null) {
            $m = new static();
            $m->key = $key;
            $m->year = $year;
            $m->seq = 0;
            $m->save();
            return 0;
        } else {
            $seq = $m->seq;
            if ($seq > 0) {
                return $seq;
            } else {
                return 0;
            }
        }

    }

    public static function nextSeq($key, $year)
    {
        $m = self::where('key', $key)->where('year', $year)->first();
        if ($m == null) {
            $m = new static();
            $m->key = $key;
            $m->year = $year;
            $m->seq = 1;
            $m->save();
            return 1;
        } else {
            $m->seq = $m->seq + 1;
            $m->save();
            return $m->seq;
        }
    }

    static function getAutoRefY($n, $option = null, $usePrefix = 1)
    {
        /*$option = [
            'pad' => 6,
            'use_date' => 1,
            'date' => date('d-m-Y'),
            'prefix' => 'INV'
        ];*/

        $pad = isset($option['pad']) ? $option['pad'] : 5;
        //$date = isset($option['date'])?$option['date']:'';
        $date = date('y');// isset($option['date'])?$option['date']:date('dmY');
        $prefix = isset($option['prefix']) ? $option['prefix'] : null;
        $use_date = isset($option['use_date']) ? $option['use_date'] : false;

        if ($usePrefix == 0) {
            $prefix = '';
        }

        if ($n > 0) {
            return $prefix . $date . '-' . str_pad($n, $pad, '0', STR_PAD_LEFT);
        }
        return '';


    }

    static function getAutoRef($n, $option = null)
    {
        /*$option = [
            'pad' => 6,
            'use_date' => 1,
            'date' => date('d-m-Y'),
            'prefix' => 'INV'
        ];*/

        $pad = isset($option['pad']) ? $option['pad'] : 5;
        //$date = isset($option['date'])?$option['date']:'';
        $date = isset($option['date']) ? $option['date'] : date('Y');
        $prefix = isset($option['prefix']) ? $option['prefix'] : null;
        $use_date = isset($option['use_date']) ? $option['use_date'] : false;

        if ($n > 0) {
            return $prefix . (date('y')) . '-' . str_pad($n, $pad, '0', STR_PAD_LEFT);
        }
        return '';


    }

}
