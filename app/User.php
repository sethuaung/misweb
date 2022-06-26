<?php

namespace App;

use App\Models\Client;
use App\Models\Branch;
use App\Models\BranchU;
use App\Models\CenterLeader;
use App\Models\Loan;
use App\Models\UserBranch;
use App\Traits\Excludable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Backpack\CRUD\CrudTrait; // <------------------------------- this one
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Traits\HasRoles;// <---------------------- and this one
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use Notifiable;
    use CrudTrait; // <----- this

    use HasRoles; // <------ and this

    use Excludable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        //'name', 'email', 'password','branch_id','center_leader_id'
        'name', 'email', 'password','user_code'
    ];

    //protected $casts = ['branch_id'=>'array','center_leader_id'=>'array'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function getLoanTable($branch_id=''){
       // return session('s_branch_id');

        if(companyReportPart()=='company.mkt') {
            if (empty($branch_id)) {
                if(isset($_REQUEST['branch_id'])){
                    if(is_array($_REQUEST['branch_id'])){
                        $branch_id = session('s_branch_id');
                    }else{
                        $branch_id = $_REQUEST['branch_id'];
                    }

                }else {
                    $branch_id = session('s_branch_id');
                }

                $tablename = 'loans_' . $branch_id;


            } else {
                $tablename = 'loans_' . $branch_id;
            }
        }else{
            $tablename = 'loans';
        }

        return $tablename;
    }
    public static function getLoanChargeTable($branch_id=''){
       // return session('s_branch_id');

        if(companyReportPart()=='company.mkt') {
            if (empty($branch_id)) {
                if(isset($_REQUEST['branch_id'])){
                    if(is_array($_REQUEST['branch_id'])){
                        $branch_id = session('s_branch_id');
                    }else{
                        $branch_id = $_REQUEST['branch_id'];
                    }

                }else {
                    $branch_id = session('s_branch_id');
                }

                $tablename = 'loan_charge_' . $branch_id;


            } else {
                $tablename = 'loan_charge_' . $branch_id;
            }
        }else{
            $tablename = 'loan_charge';
        }

        return $tablename;
    }
    public static function getLoanCompulsoryTable($branch_id=''){
       // return session('s_branch_id');

        if(companyReportPart()=='company.mkt') {
            if (empty($branch_id)) {
                if(isset($_REQUEST['branch_id'])){
                    if(is_array($_REQUEST['branch_id'])){
                        $branch_id = session('s_branch_id');
                    }else{
                        $branch_id = $_REQUEST['branch_id'];
                    }

                }else {
                    $branch_id = session('s_branch_id');
                }

                $tablename = 'loan_compulsory_' . $branch_id;


            } else {
                $tablename = 'loan_compulsory_' . $branch_id;
            }
        }else{
            $tablename = 'loan_compulsory';
        }

        return $tablename;
    }

    public static function getLoanCalculateTable($branch_id=''){
       // return session('s_branch_id');

        if(companyReportPart()=='company.mkt') {
            if (empty($branch_id)) {
                if(isset($_REQUEST['branch_id'])){
                    if(is_array($_REQUEST['branch_id'])){
                        $branch_id = session('s_branch_id');
                    }else{
                        $branch_id = $_REQUEST['branch_id'];
                    }

                }else {
                    $branch_id = session('s_branch_id');
                }

                $tablename = 'loan_disbursement_calculate_' . $branch_id;


            } else {
                $tablename = 'loan_disbursement_calculate_' . $branch_id;
            }
        }else{
            $tablename = 'loan_disbursement_calculate';
        }

        return $tablename;
    }

    public function user_branch(){
        return $this->hasMany('App\Models\UserBranch');
    }

    public function user_branch_m(){
        return $this->hasMany('App\Models\UserBranch')->orderBy('branch_id','ASC');
    }

    public function loanOfficerAccess()
    {
        return $this->hasMany(Client::class, 'loan_officer_access_id');
    }

    public function leader_name()
    {
        return $this->hasMany(Loan::class, 'center_leader_id');
    }

    public function officer_name()
    {
        return $this->hasMany(Loan::class, 'loan_officer_id');
    }

    function branches(){
        return $this->belongsToMany(BranchU::class,'user_branches','user_id','branch_id');
    }
    function center(){
        return $this->belongsToMany(CenterLeader::class,'user_centers','user_id','center_id');
    }

    function branch_manager() {
        return $this->hasMany(Branch::class,'branch_manager_id');
    }

    public function isOnline()
    {
        return Cache::has('user-is-online-' . $this->id);
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('id', function (Builder $builder) {

            $user_id = 0;
            foreach ( session()->all() as $k => $v){
                if(str_contains($k,'login_web')){
                    $user_id = $v;
                }
            }

            $u_id = [];
            if($user_id>0){
                $u = User::withoutGlobalScopes()->find($user_id);

                $branch_id = [];
                if(isset($u->branches)) {
                    if($u->branches != null) {
                        foreach ($u->branches as $b) {
                            $branch_id[$b->id] = $b->id;
                        }
                    }
                }

                $user = UserBranch::whereIn('branch_id',$branch_id)->get();
                if($user != null){
                    foreach ($user as $us){
                        $u_id[$us->user_id] = $us->user_id;
                    }
                }
                /*$branch_id = optional($u)->branch_id;
                if($branch_id != null){
                    if(!is_array($branch_id)){
                        $branch_id = json_decode($branch_id);
                    }
                }*/
            }

            if($user_id>1 && is_array($u_id)) {

                if (count($u_id)) {
                    $builder->where(function ($q) use ($u_id) {
                        $q->whereIn('users.id', $u_id);
                    });
                }
            }

        });


        /* static::creating(function ($obj) { // before delete() method call this

         });

         static::updating(function ($obj) { // before delete() method call this

         });*/


    }

}
