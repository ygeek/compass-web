<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone_number', 'password', 'email'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'intentions' => 'array'
    ];
    public function likedCollegeIds(){
        $key = User::likeKey($this->id);
        $value = Setting::get($key);
        if(is_null($value)){
            $value = [];
        }
        return $value;
    }

    //判断用户是否收藏了某学校
    public function isLikeCollege($college_id){
        $ids = $this->likedCollegeIds();
        return in_array($college_id, $ids);
    }

    public static function likeKey($user_id){
        return 'user:'. $user_id .':favorites';
    }

    public function getAvatarPath(){
        if($this->getAttribute('avatar_path')){
            return $this->getAttribute('avatar_path');
        }else{
            return $this->defaultAvatarPath();
        }
    }

    public function defaultAvatarPath(){
        return '/images/default-avatar.jpg'; 
    }
}
