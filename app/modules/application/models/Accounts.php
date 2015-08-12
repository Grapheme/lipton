<?php

class Accounts extends \User {

    public static $rules = array(
        'name' => 'required', 'surname' => 'required', 'email' => 'required|email', 'dd' => 'required', 'mm' => 'required',
        'phone' => 'required', 'acceptCheckbox' => 'required', 'sex' => 'required', 'yyyy' => 'required'
    );

    public static $update_rules = array(
        'name' => 'required', 'surname' => 'required', 'dd' => 'required', 'mm' => 'required', 'city' => 'required',
        'phone' => 'required', 'sex' => 'required', 'yyyy' => 'required'
    );

    public function ulogin() {

        return $this->hasOne('Ulogin', 'user_id', 'id');
    }

    public function prizes(){

        return $this->hasMany('UserPrizes','user_id','id');
    }

    public function codes(){

        return $this->hasMany('UserCodes','user_id','id');
    }

    public function writing(){

        return $this->hasOne('UserWritings','user_id','id');
    }
}