<?php

class UserCodes extends \User {

    protected $table = 'user_codes';

    public function users(){

        return $this->belongsTo('Accounts','user_id','id');
    }
}