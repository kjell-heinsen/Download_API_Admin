<?php

namespace downapiadmin\app\core;

class csrf {



public static function getToken():string
{
    if(isset($_SESSION['csrf_token'])){
        return $_SESSION['csrf_token'];
    } else {
        $token = self::RandomToken();
        $_SESSION['csrf_token'] = $token;
        return $token;
    }
}


public static function ValToken(?string $token):void{
    if($token == self::getToken()){
        $_SESSION['csrf_token'] = NULL;
      self::getToken();
    } else {
        throw new \Exception('Kein gültiger CSRF Token vorhanden.');
    }
}


private static function RandomToken():string{
    $token = bin2hex(random_bytes(32));
    return $token;
}


public static function Form():string{
    $html = '<input type="hidden" name=csrf_token value="'.self::getToken().'">';
    return $html;
}

}
