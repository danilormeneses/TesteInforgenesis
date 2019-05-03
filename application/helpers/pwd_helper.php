<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists("senha_enc_dec")) {
    # code...
    function senha_enc_dec($pwd)
    {
        # code...
        $response = md5($pwd);
        
        return $response;
    }
}



