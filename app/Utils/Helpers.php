<?php


namespace App\Utils;

use Carbon\Carbon;

class Helpers
{

    public static function error_processor($validator)
    {
        $err_keeper=[];
        foreach($validator->errors()->getMessages() as $i => $error){
            array_push($err_keeper,['code' => $i, 'message' =>$error[0]]);
         }
         return $err_keeper;
    }
}