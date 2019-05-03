<?php
/**
 * Created by PhpStorm.
 * User: saman
 * Date: 4/26/2019
 * Time: 1:42 PM
 */

function validColor($color){
    global $f3;
    return in_array($color,$f3->get('colors'));
}

function validString($string){
    if($string!="" && ctype_alpha($string)){
        return true;
    } else{
        return false;
    }
}

function validQty($qty)
{
    return !empty($qty) && ctype_digit($qty) && $qty >= 1;
}

function validBreed($breed)
{
    global $f3;

    if(empty($breed))
    {
        return false;
    }

    foreach($breed as $option)
    {
        if(!in_array($option, $f3->get('breeds')))
        {
            return false;
        }
    }
    return true;
}