<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require_once('../phpQuery.inc');
require_once('../dbinfo.inc');

$mysql = new mysqli("localhost", $username, $password, "twolinep_stats");

$query = "INSERT INTO Game (gameID, time, awayTeam, homeTeam) VALUES ";

phpQuery::newDocumentFileHTML('http://www.nhl.com');

$games = pq('.items');

$gameHTML = $games->html();

// iterate thru the games...
foreach ($games as $game)
{
    $query .= "(";
    
    /*
    // get the ID
    $id = $game->getAttribute('id');
    $id = substr($id, -4);
    echo $id . "<br />";
    */
    
    echo $game->nodeValue;
    echo "<br />";
    //die();
    
    //pq($game);
    
    // get Toronto
    //$time = pq('.defaultState', $game);
    //$timeStr = $time->html();
    //echo "time = ".$timeStr."<br />";
    
    /*
    // get the TIME
    $time = pq('.gmStatus span', $game);
    $timeStr = $time->html();
    $parts = explode(" ", $timeStr);
    $time = $parts[0]; // Ex: 7:00
    $AMPM = $parts[1];
    $timeZone = $parts[2];
    
    //echo "time = ".$time."<br/>";
    
    $timestamp = strtotime($time.' '.$AMPM, time());
    //echo $timestamp . "<br/>";
    
    // get the teams
    $away = pq('.awayLine .gmTeams span', $game);
    // echo "away = ".$awayTeam->html() . "<br />";
    $awayTeam = $away->html();
    $getTeamQuery = "SELECT id FROM Team WHERE abv = '".$awayTeam."'";
    $result = $mysql->query($getTeamQuery);
    $row = $result->fetch_assoc();
    $awayTeamID = $row['id'];
    
    $home = pq('.homeLine .gmTeams span', $game);
    $homeTeam = $home->html();
    $getTeamQuery = "SELECT id FROM Team WHERE abv = '".$homeTeam."'";
    $result = $mysql->query($getTeamQuery);
    $row = $result->fetch_assoc();
    $homeTeamID = $row['id'];
    
    
    $query .= "'".$id."', ".$timestamp.", ".$awayTeamID.", ".$homeTeamID."), ";
    */
}

/*
$query = substr($query, 0, -2);

//echo $query; die();

$mysql->query($query);
$mysql->close();
*/

echo "success";
exit();

?>