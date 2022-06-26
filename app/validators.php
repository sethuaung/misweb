<?php

\Illuminate\Support\Facades\Validator::extend(
    'valid_password',
    function ($attribute, $value, $parameters)
    {
        return preg_match('/^[a-zA-Z0-9!@#$%\/\^&\*\(\)\-_\+\=\|\[\]{}\\\\?\.,<>`\'":;]+$/u', $value);
    },'invalid password'
);

\Illuminate\Support\Facades\Validator::extend(
    'phone_number',
    function ($attribute, $value, $parameters)
    {
        return strlen(preg_replace('#^.*([0-9]{3})[^0-9]*([0-9]{3})[^0-9]*([0-9]{4})$#', '$1$2$3', $value)) == 10;
    },'invalid phone number'
);


\Illuminate\Support\Facades\Validator::extend(
    'phone_number',
    function ($attribute, $value, $parameters)
    {
        return strlen(preg_replace('#^.*([0-9]{3})[^0-9]*([0-9]{3})[^0-9]*([0-9]{4})$#', '$1$2$3', $value)) == 10;
    },'invalid phone number'
);


\Illuminate\Support\Facades\Validator::extend(
    'fixed_parent',
    function ($attribute, $value, $parameters)
    {
        return $value != $parameters[0];
    },'Parent of error!!'
);