<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('user_loged')){
    
    function user_loged( $session ){

        if( $session != TRUE ){
            redirect("page");
		} 
    }

}
