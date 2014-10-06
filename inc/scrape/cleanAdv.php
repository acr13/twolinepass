<?php

// big script to handle parsing all of the nhl games :)
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require_once('/home/twolinep/public_html/inc/dbinfo.inc');

$mysql = new mysqli("localhost", $username, $password, "twolinep_stats");

$query = "SELECT MAX(GP) as MaxGP From AdvancedStats";
$result = $mysql->query($query);
$row = $result->fetch_assoc();
$maxGP = $row['MaxGP'];

$cutoff = ceil($maxGP * 0.5);

?>