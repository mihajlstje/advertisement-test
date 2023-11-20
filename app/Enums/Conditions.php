<?php

namespace App\Enums;

enum Conditions : int
{
    case NEW = 1;
    case USED = 2;

    public function name(){

        return match($this){
            self::NEW => 'New',
            self::USED => 'Used'
        };

    }
}