<?php

// big script to handle parsing all of the nhl games :)
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require_once('/home/twolinep/public_html/inc/dbinfo.inc');

$mysql = new mysqli("localhost", $username, $password, "twolinep_stats");

// get the gameID from the DB
$result = $mysql->query("SELECT gameID FROM ParsedGames WHERE id = 1");
$row = $result->fetch_assoc();
$gameID = $row['gameID'];

$NUM_GAMES = 10;

for ($i = 0; $i < $NUM_GAMES; $i++)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'http://twolinepass.ca/inc/scrape/getPlayByPlay.php?id='.$gameID,
    ));
    $resp = curl_exec($curl);
    curl_close($curl);    
    
    if ($resp == "success")
    {
        echo "successfully parsed ".$gameID." <br />";
        
        $gameNumber = intval($gameID);
        $gameNumber++;
        $newGameID = padGame($gameNumber);    
        saveNewGameID($newGameID);
        
        $gameID = $newGameID;
    }
}

// clean up adv table

$query = "SELECT MAX(GP) AS max FROM BasicStats";
$result = $mysql->query($query);
$row = $result->fetch_assoc();
$maxGP = $row['max'];

$minGP = ceil($maxGP * 0.5);

//$query = "DELETE FROM AdvancedStats";

die();
exit;

function padGame($num)
{
    $gameID = "".$num;
    
    $len = strlen($gameID);
    $diff = 4 - $len;
    
    $zeros = "";
    
    for ($i = 0; $i < $diff; $i++)
    {
        $zeros .= "0";
    }
    
    return $zeros . $gameID;
}

function saveNewGameID($gameID)
{
    global $mysql;
    
    $query = "UPDATE ParsedGames SET gameID = '".$gameID."' WHERE id = 1";
    $result = $mysql->query($query);
}

?>