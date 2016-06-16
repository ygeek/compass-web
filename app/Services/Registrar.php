<?php namespace App\Services;

use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;

class Registrar implements RegistrarContract {

    public function validator(array $data){
        return false;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    public function create(array $data)
    {

        $properties = [
            'phone_number' => $data['phone_number'],
            'password' => bcrypt($data['password']),
            'register_time' => Carbon::now()->toDateTimeString(),
            'register_ip' => $data['register_ip']
        ];
        return User::create($properties);
    }

}
