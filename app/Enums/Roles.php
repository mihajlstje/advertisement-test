<?php

namespace App\Enums;

enum Roles : int
{
    case CUSTOMER = 1;
    case ADMIN = 2;

    public function name(){

        return match($this){
            self::CUSTOMER => 'Customer',
            self::ADMIN => 'Admin'
        };

    }
}