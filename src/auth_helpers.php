<?php
if (!function_exists('user_id')) {
    function user_id()
    {
        if (auth()->user()) {
            return auth()->user()->id;
        }
        return 0;
    }
}

if (!function_exists('user_name')) 
{
    function user_name()
    {
        if(auth()->user()){
            return auth()->user()->name;
        }
        return '';
    }
}
