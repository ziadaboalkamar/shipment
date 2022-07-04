<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Laravel\Passport\HasApiTokens;
use App\Models\AddClientsMainComp;
use App\Models\AddBranchUser;


class AllUser extends Authenticatable implements JWTSubject
{
    use Notifiable, LaratrustUserTrait, HasApiTokens;
    protected $primaryKey = 'code_';
    protected $guarded = [];
    public $timestamps = false;
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function getAuthPassword() {
        return $this->PASSWORD;
    }
    public function userPhone() {


    }
    public function Khazna()
    {
        return $this->belongsToMany(khazna::class,'5azna_user','5azna_id','user_id');
    }


}
