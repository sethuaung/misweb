<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class OldDuc extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'erp_quote_photos';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    // protected $guarded = ['id'];
    // protected $fillable = ['name','description'];
    // protected $hidden = [];
    // protected $dates = [];

    static function toMyArr($s)
    {
        return [];
        $arr = [];
        if ($s != null) {
            if (is_array($s)) {
                return $s;
            } else {
                if (is_string($s)) {
                    return \GuzzleHttp\json_decode($s);
                }
            }
        }
        return $arr;
    }

    static function updateOldDuc($id)
    {
        if ($id > 0) {
            $f = 'uploads/documents/';
            $client = ClientO::find($id);
            if ($client != null) {
                $nrc_photo = self::toMyArr($client->nrc_photo);
                $scan_finger_print = self::toMyArr($client->scan_finger_print);
                $photo_of_client = $client->photo_of_client;
                $form_photo_front = self::toMyArr($client->form_photo_front);
                $form_photo_back = self::toMyArr($client->form_photo_back);
                $company_letter_head = self::toMyArr($client->company_letter_head);
                $community_recommendation = self::toMyArr($client->community_recommendation);
                $employment_certificate = self::toMyArr($client->employment_certificate);
                $other_document = self::toMyArr($client->other_document);

                $nrc = $client->nrc_number;
                if ($nrc != null) {

                    $m = self::where('nrc', $nrc)->get();
                    if ($m != null) {
                        if (count($m) > 0) {

                            foreach ($m as $r) {
                                $t = $r->type;
                                if ($t == 'government_id') {
                                    $nrc_photo[] = $f . $r->name;
                                } else if ($t == 'finger_print') {
                                    $scan_finger_print[] = $f . $r->name;
                                } else if ($t == 'applicant_photo') {
                                    $photo_of_client = $photo_of_client == null || $photo_of_client == '' ? $f . $r->name : $photo_of_client;
                                } else if ($t == 'form_photo_front') {
                                    $form_photo_front[] = $f . $r->name;
                                } else if ($t == 'form_photo_back') {
                                    $form_photo_back[] = $f . $r->name;
                                } else if ($t == 'company_letter') {
                                    $company_letter_head[] = $f . $r->name;
                                } else if ($t == 'police_recommendation') {
                                    $community_recommendation[] = $f . $r->name;
                                } else if ($t == 'employment_certificate') {
                                    $employment_certificate[] = $f . $r->name;
                                } else if ($t == 'other_document' || $t == 'ward_recommendation') {
                                    $other_document[] = $f . $r->name;
                                }
                            }

                            $client->nrc_photo = json_encode($nrc_photo);
                            $client->scan_finger_print = json_encode($scan_finger_print);
                            $client->photo_of_client = $photo_of_client;
                            $client->form_photo_front = json_encode($form_photo_front);
                            $client->form_photo_back = json_encode($form_photo_back);
                            $client->company_letter_head = json_encode($company_letter_head);
                            $client->community_recommendation = json_encode($community_recommendation);
                            $client->employment_certificate = json_encode($employment_certificate);
                            $client->other_document = json_encode($other_document);
                            $client->save();
                        }
                    }
                }
            }
        }
    }

}
