<?php
// name validation function
function validateName($name)
{
    return strlen($name) >= 5;
}

// email validation function
function validateEmail($email)
{
    return strpos($email, '@') && strlen($email) >= 5 ? true : false;
}

// password validation function
function validatePass($password)
{
    return strlen($password) >= 8;
}
?>