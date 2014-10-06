<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

require_once('../phpQuery.inc');

$TEAM = isset($_REQUEST['team']) ? $_REQUEST['team'] : "";

if ($TEAM == "")
{
    die('error');
}

phpQuery::newDocumentFileHTML('http://www.nhl.com');

$games = pq('.items');
$gameHTML = $games->html();

// iterate thru the games...
foreach ($games as $game)
{
    $gameStr = $game->nodeValue;
 
    //echo "gameStr = ".$gameStr."<br />"; 
    
    // found the team
    // 04:32 1stTOR1COL0
    if (strpos($gameStr, $TEAM) !== false)
    {        
        // end of a period
        if (strpos($gameStr, 'END') !== false)
        {
            parseGameEnd($gameStr);
        }
        else if (strpos($gameStr, 'FINAL') !== false)
        {
            parseFinalGame($gameStr);
        }
        else if (strpos($gameStr, 'PM ET') !== false)
        {
            parseGameBefore($gameStr);
        }
        else // in play
        {
            parseGameInProgress($gameStr);
        }
        
    }
}

// period end
function parseGameEnd($gameStr)
{
    global $TEAM;
    
    //echo $gameStr;
    //echo "<br />";
    
    $time = substr($gameStr, 0, 3);
    //echo "time = ".$time."<br />";
    
    $period = substr($gameStr, 4, 3);
    //echo "period = ".$period."<br />";
    
    $awayTeam = substr($gameStr, 7, 3);
    //echo "awayTeam = ".$awayTeam."<br />";
    
    $awayScore = substr($gameStr, 10, 1);
    //echo "awayScore = ".$awayScore."<br />";
    
    $homeTeam = substr($gameStr, 11, 3);
    //echo "homeTeam = ".$homeTeam."<br />";
    
    $homeScore = substr($gameStr, 14, 1);
    //echo "homeScore = ".$homeScore."<br />";
    
     if ($awayTeam == $TEAM)
        die(json_encode(array('score' => 'END '.$period.' '.$awayScore.' - '.$homeScore)));
    else
        die(json_encode(array('score' => 'END '.$period.' '.$homeScore.' - '.$awayScore))); 
}

function parseFinalGame($gameStr)
{
    global $TEAM;
    
    //echo $gameStr;
    //echo "<br />";
    
    $time = substr($gameStr, 0, 5);
    //echo "time = ".$time."<br />";
    
    //$period = substr($gameStr, 6, 3);
    //echo "period = ".$period."<br />";
    
    $awayTeam = substr($gameStr, 5, 3);
    //echo "awayTeam = ".$awayTeam."<br />";
    
    $awayScore = substr($gameStr, 8, 1);
    //echo "awayScore = ".$awayScore."<br />";
    
    $homeTeam = substr($gameStr, 9, 3);
    //echo "homeTeam = ".$homeTeam."<br />";
    
    $homeScore = substr($gameStr, 12, 1);
    //echo "homeScore = ".$homeScore."<br />";
    
    if ($awayTeam == $TEAM)
        die(json_encode(array('score' => 'END '.$awayScore.' - '.$homeScore)));
    else
        die(json_encode(array('score' => 'END '.$homeScore.' - '.$awayScore)));
}

function parseGameBefore($gameStr)
{
    global $TEAM;
    
    $time = substr($gameStr, 0, 4);
    //echo "time = ".$time."<br />";
    
    //$period = substr($gameStr, 6, 3);
    //echo "period = ".$period."<br />";
    
    $awayTeam = substr($gameStr, 10, 3);
    //echo "awayTeam = ".$awayTeam."<br />";
    
    //$awayScore = substr($gameStr, 12, 1);
    //echo "awayScore = ".$awayScore."<br />";
    
    $homeTeam = substr($gameStr, 14, 3);
    //echo "homeTeam = ".$homeTeam."<br />";
    
    //$homeScore = substr($gameStr, 16, 1);
    //echo "homeScore = ".$homeScore."<br />";
    
    
    if ($awayTeam == $TEAM)
        die(json_encode(array('score' => ''.$time.' '.$homeTeam)));
    else
        die(json_encode(array('score' => ''.$time.' '.$awayTeam)));  
}

function parseGameInProgress($gameStr)
{
    global $TEAM;
    
    //echo $gameStr;
    //echo "<br />";
    
    $time = substr($gameStr, 0, 5);
    //echo "time = ".$time."<br />";
    
    $period = substr($gameStr, 6, 3);
    //echo "period = ".$period."<br />";
    
    $awayTeam = substr($gameStr, 9, 3);
    //echo "awayTeam = ".$awayTeam."<br />";
    
    $awayScore = substr($gameStr, 12, 1);
    //echo "awayScore = ".$awayScore."<br />";
    
    $homeTeam = substr($gameStr, 13, 3);
    //echo "homeTeam = ".$homeTeam."<br />";
    
    $homeScore = substr($gameStr, 16, 1);
    //echo "homeScore = ".$homeScore."<br />";
    
     if ($awayTeam == $TEAM)
        die(json_encode(array('score' => ''.$period.' '.$awayScore.' - '.$homeScore)));
    else
        die(json_encode(array('score' => ''.$period.' '.$homeScore.' - '.$awayScore)));        
}

echo "error";
exit();

?>