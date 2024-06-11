<?php

namespace App\DAO;

use App\Models\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginDAO
{

    public static function ultimoLogin($profid) {
       /*
        SELECT id from logins where user_id = 5 and entrada_momento = (
            SELECT max(entrada_momento) FROM `logins` WHERE user_id = 5)
        */

        $login = Login::query()
                        ->where('user_id', $profid)
                        ->latest() // ordenado por created_at que tem o mesmo valor do entrada_momento
                        ->take(1)
                        ->first();
        return $login;
    }
}
?>