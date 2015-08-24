<?php

class UserWritings extends \User {

    protected $table = 'user_writings';

    public function user(){

        return $this->belongsTo('Accounts', 'user_id', 'id');
    }

    public function users(){

        return $this->belongsTo('Accounts','user_id','id');
    }
}