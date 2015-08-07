<?php

class Accounts extends \User {

    public static $rules = array(
        'name' => 'required', 'surname' => 'required', 'email' => 'required|email', 'dd' => 'required', 'mm' => 'required',
        'phone' => 'required', 'acceptCheckbox' => 'required', 'sex' => 'required', 'yyyy' => 'required'
    );

    public function ulogin() {

        return $this->hasOne('Ulogin', 'user_id', 'id');
    }
}