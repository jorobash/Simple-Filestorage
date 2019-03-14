<?php

function validate_inputs($filed){
    $filed = trim($filed);
    $filed = stripcslashes($filed);
    $filed = htmlentities($filed);
    $filed = strip_tags($filed);
    return $filed;

}

function checkEmptyFields($field){
    $error = array();
    if(empty($field)){
      return  $error[] = 'The field is required';
    }else{
        return validate_inputs($field);
    }
}