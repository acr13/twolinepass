<?php

$username = isset($_REQUEST['username']) ? $_REQUEST['password'] : -1;
$password = isset($_REQUEST['password']) ? $_REQUEST['password'] : -1;

if ($username == -1 || $password == -1)
{
    die('invalid params');
}

$SET_USER = "alex";
$SET_PASS = "alex";
$SET_HASH = md5($SET_USER."|".$SET_PASS);

$testHash = md5($username."|".$password);

if ($testHash === $SET_HASH)
{
    die(json_encode(array("success" => true)));
}

die(json_encode(array("success" => false)));

?>