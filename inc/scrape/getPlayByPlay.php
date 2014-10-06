<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

$gameID = isset($_REQUEST['id']) ? $_REQUEST['id'] : -1;

if ($gameID == -1)
{
    die('invalid game id');
}

require_once('../phpQuery.inc');
require_once('../dbinfo.inc');

$mysql = new mysqli("localhost", $username, $password, "twolinep_stats");

/*
$awayQuery = "SELECT abv FROM Game AS g, Team AS t WHERE gameID = '0499' AND t.id = g.awayTeam";
$homeQuery = "SELECT abv FROM Game AS g, Team AS t WHERE gameID = '0499' AND t.id = g.homeTeam";

$result = $mysql->query($awayQuery);
$row = $result->fetch_assoc();
$awayTeamABV = $row['abv'];

$result = $mysql->query($homeQuery);
$row = $result->fetch_assoc();
$homeTeamABV = $row['abv'];

echo "home = ".$homeTeamABV.", away = ".$awayTeamABV."<br />";
*/

$playersInGame = array();

$url = "http://www.nhl.com/scores/htmlreports/20132014/PL02".$gameID.".HTM";
phpQuery::newDocumentFileHTML($url);

$header = pq('td[width="10%"]');

$count = 0;
foreach ($header as $td)
{
    if ($count == 2)
        break;
    
    pq($td);
    
    $teamABV = substr($td->nodeValue, 0, 3);
    
    if ($count == 0)
        $awayTeamABV = $teamABV;
    else if ($count == 1)
        $homeTeamABV = $teamABV;
        
    $count++;
}


//echo "home = ".$homeTeamABV.", away = ".$awayTeamABV."<br />";
//die();

// get all of the plays
$plays = pq('.evenColor');

// TYPES OF PLAYS
// PSTR - Period Start
// FAC - Faceoff
// HIT
// SHOT
// MISS - Missed Shot
// STOP
// PENL - Penalty
// GIVE - Giveaway
// TAKE - Takeaway
// BLOCK - Blocked shot
// GOAL

// Parse each play
foreach($plays as $play)
{
    pq($play);
    
    $data = pq('td', $play);

    parsePlay($data);
}

//var_dump($playersInGame);

addGamesPlayed($playersInGame);

echo "success";

// read in each table cell
// 0 - play number
// 1 - period (4 = OT, 5 = Shootout)
// 2 - Strength (EV, SH, PP)
// 3 - Elapsed Time / Time Remaining
// 4 - Play Type (see above)
// 5 - Desc
// 8 - Away Team On Ice
// 12
// 16
// 20
// 24
// 28 (goalie)
// 32 - Home Team On Ice
// 36
// 40
// 44
// 48
// 52 (goalie)
function parsePlay($play)
{
    global $homeTeamABV, $awayTeamABV;
    
    pq($play);
    
    $type = $play->elements[4]->nodeValue;
    
    switch($type)
    {
        case "PSTR":
            //echo "Period Start";
            break;
        case "PEND":
            //echo "Period End";
            break;
        case "STOP":
            //echo "Stoppage in play";
            break;
        case "FAC":
            //echo "Faceoff";
            break;
        case "HIT":
            //echo "Hit";
            break;
        case "SHOT":
            
            $strength = $play->elements[2]->nodeValue;
            
            // fenwick is ev only
            if ($strength != "EV")
                break;
            
            $desc = $play->elements[5]->nodeValue;
            $forTeam = substr($desc,0, 3);
            
            if ($forTeam == $awayTeamABV)
            {
                $forTeam = "away";
            }
            else if ($forTeam == $homeTeamABV)
            {
                $forTeam = "home";
            }
            else
            {
                die('invalid team abv in shot on goal. play number: '.$play->elements[0]->nodeValue);
            }
            
            $htmlPlayers = pq('font', $play);
            pq($htmlPlayers);
            
            $playerData = getPlayersOnIce($htmlPlayers);
            
            $endHome = $playerData['1'];
            $players = $playerData['players'];
            
            // 5 on 5 play
            if (count($players) != 12)
                break;
            
            $playerIndexes = array(8,12,16,20,24,28,32,36,40,44,48,52);
            
            for ($i = 0; $i < count($players); $i++)
            {
                $players[$i]['jersey'] = trim($play->elements[$playerIndexes[$i]]->nodeValue);
            }
            
            /*
            // away team
            $players[0]['jersey'] = trim($play->elements[8]->nodeValue);
            $players[1]['jersey'] = trim($play->elements[12]->nodeValue);
            $players[2]['jersey'] = trim($play->elements[16]->nodeValue);
            $players[3]['jersey'] = trim($play->elements[20]->nodeValue);
            $players[4]['jersey'] = trim($play->elements[24]->nodeValue);
            $players[5]['jersey'] = trim($play->elements[28]->nodeValue);
            
            // home team
            $players[6]['jersey'] = trim($play->elements[32]->nodeValue);
            $players[7]['jersey'] = trim($play->elements[36]->nodeValue);
            $players[8]['jersey'] = trim($play->elements[40]->nodeValue);
            $players[9]['jersey'] = trim($play->elements[44]->nodeValue);
            $players[10]['jersey'] = trim($play->elements[48]->nodeValue);
            $players[11]['jersey'] = trim($play->elements[52]->nodeValue);
            */
            
            addFenwick($forTeam, $players, $endHome);
            
            addCorsi($forTeam, $players, $endHome);
            
            //echo "Shot";
            
            break;
        case "MISS":
            
            /*
            $strength = $play->elements[2]->nodeValue;
            
            //  is ev only
            if ($strength != "EV")
                break;
            */
            
            $desc = $play->elements[5]->nodeValue;
            $forTeam = substr($desc,0, 3);
            
            if ($forTeam == $awayTeamABV)
            {
                $forTeam = "away";
            }
            else if ($forTeam == $homeTeamABV)
            {
                $forTeam = "home";
            }
            else
            {
                die('invalid team abv in missed shot on goal. play number: '.$play->elements[0]->nodeValue);
            }
            
            $htmlPlayers = pq('font', $play);
            pq($htmlPlayers);
            
            $playerData = getPlayersOnIce($htmlPlayers);
            
            $endHome = $playerData['1'];
            $players = $playerData['players'];
            
            // 5 on 5 play
            if (count($players) != 12)
                break;
            
            $playerIndexes = array(8,12,16,20,24,28,32,36,40,44,48,52);
            
            for ($i = 0; $i < count($players); $i++)
            {
                $players[$i]['jersey'] = trim($play->elements[$playerIndexes[$i]]->nodeValue);
            }
            
            addFenwick($forTeam, $players, $endHome);
            
            addCorsi($forTeam, $players, $endHome);
            
            //echo "Missed Shot";
            break;
        case "BLOCK":
            
             $desc = $play->elements[5]->nodeValue;
            $forTeam = substr($desc,0, 3);
            
            if ($forTeam == $awayTeamABV)
            {
                $forTeam = "away";
            }
            else if ($forTeam == $homeTeamABV)
            {
                $forTeam = "home";
            }
            else
            {
                die('invalid team abv in blocked shot on goal. play number: '.$play->elements[0]->nodeValue);
            }
            
            $htmlPlayers = pq('font', $play);
            pq($htmlPlayers);
            
            $playerData = getPlayersOnIce($htmlPlayers);
            
            $endHome = $playerData['1'];
            $players = $playerData['players'];
            
            // 5 on 5 play
            if (count($players) != 12)
                break;
            
            $playerIndexes = array(8,12,16,20,24,28,32,36,40,44,48,52);
            
            for ($i = 0; $i < count($players); $i++)
            {
                $players[$i]['jersey'] = trim($play->elements[$playerIndexes[$i]]->nodeValue);
            }
            
            addCorsi($forTeam, $players, $endHome);
            
            //echo "Blocked Shot";
            break;
        case "GIVE":
            //echo "Giveaway";
            break;
        case "TAKE":
            //echo "Takeaway";
            break;
        case "PENL":
            //echo "Penalty";
            break;
        case "GOAL":
            //echo "GGGGGGGGGOALLLLLLLLLLL";
            break;
        case "SOC":
            //echo "Shootout Completed";
            break;
        case "GEND":
            //echo "Game End";
            break;
        case "EISTR":
            //echo "Early intermission start";
            break;
        case "EIEND":
            //
            break;
        case "GOFF":
            break;
        default:
            echo "**************** DEFAULT! ***************";
            die("unkown play type ".$type);// shouldn't happen
            break;
    }
        
    //echo "<br />";
}

function getPlayersOnIce($htmlPlayers)
{
    // ret will hold the players, as well as the indexes so we know if its EV, PP, 4on4, etc
    $ret = array();
    
    $ret['players'] = array();
    //$ret[0] = 0;
    $index = 0;
    
    foreach ($htmlPlayers as $player)
    {
        pq($player);
        
        // EX: Center - SEAN COUTURIER
        $fullString = $player->getAttribute('title');
        $parts = explode("-", $fullString);
        
        // end of section
        if (trim($parts[0]) == "Goalie" && !isset($ret[1]))
        {
            $ret[1] = ($index+1); // end of home players (separator)
        }
        
        //echo $parts[0] . "<br />";
        
        $ret['players'][] = array(
          'name' => trim($parts[1]),
          'pos' => trim($parts[0]),
          'jersey' => -1
        );
        
        $index++;
        
    }
    
    return $ret;
}

function addPlayerToList($team, $whichTeam, $onIce, $endHome)
{
    global $playersInGame;
    
    if ($whichTeam == "away")
    {
        $startIndex = 0;
        $endIndex = $endHome;
    }
    else
    {
        $startIndex = $endHome;
        $endIndex = count($onIce);
    }
    
    
    // for each player on the ice...
    for ($i = $startIndex; $i < $endIndex; $i++)
    {
        $found = false;
        
        // check if the player on ice the is in the unique list
        for ($j = 0; $j < count($playersInGame); $j++)
        {
            if (!$found &&
                $onIce[$i]['name'] == $playersInGame[$j]['name'] &&
                $onIce[$i]['pos'] == $playersInGame[$j]['pos'] &&
                $onIce[$i]['jersey'] == $playersInGame[$j]['jersey'])
            {
                $found = true;
            }
        }
        
        // if they aren't in the list, add them.
        if (!$found)
        {
            $onIce[$i]['team'] = $team;
            
            $playersInGame[] = $onIce[$i];
        }
    }
}

function addGamesPlayed($playersInGame)
{
    global $mysql;
    
    foreach ($playersInGame as $player)
    {
        if ($player['pos'] != "Goalie")
        {
            $query = "UPDATE AdvancedStats
                    SET GP = GP + 1
                    WHERE jersey = ".$player['jersey']."
                    AND name = '".$mysql->real_escape_string($player['name'])."'
                    AND team = '".$player['team']."'";
            $result = $mysql->query($query);
        }
    }
}

function checkPlayers($abv, $players, $whichTeam, $endHome)
{
    global $mysql;
    
    $query = "SELECT id FROM Team WHERE abv = '".$abv."';";
    $result = $mysql->query($query);
    $row = $result->fetch_assoc();
    $teamID = $row['id'];
    
    if ($whichTeam == "away")
    {
        $startIndex = 0;
        $endIndex = $endHome;
    }
    else
    {
        $startIndex = $endHome;
        $endIndex = count($players);
    }
    
    
    for ($i = $startIndex; $i < $endIndex; $i++)
    {
        $query = "SELECT id FROM Player WHERE name = '".$mysql->real_escape_string($players[$i]['name'])."' AND team = ".$teamID;
        $result = $mysql->query($query);
        
        //echo $mysql->real_escape_string($players[$i]['name']) . "<br />";
        //echo "teamID = ".$teamID."<br />";
        //var_dump($result);
        
        $row = $result->fetch_assoc();
        
        // add to databse
        if ($row == NULL)
        {
            // Add the Player to the Player Table
            $query = "INSERT INTO Player (number, name, team, pos) VALUES (".$players[$i]['jersey'].", '".$players[$i]['name']."', ".$teamID.", '".$players[$i]['pos']."')";
            $result = $mysql->query($query);
            
            // Add to the Advanced Stats Table
            $query = "INSERT INTO AdvancedStats (name, team, jersey, pos) VALUES ('".$players[$i]['name']."', '".$abv."', ".$players[$i]['jersey'].", '".$players[$i]['pos']."')";
            $result = $mysql->query($query);
        }
    }
}

function addCorsi($forTeam, $players, $endHome)
{
    global $homeTeamABV, $awayTeamABV;
    
    // away team shot
    if ($forTeam == "away")
    {
        checkPlayers($awayTeamABV, $players, "away", $endHome);
        
        addCorsiFor($awayTeamABV, $players, "away", $endHome);
        addCorsiAgainst($homeTeamABV, $players, "home", $endHome);
    }
    else // $forTeam == "home"
    {
        checkPlayers($homeTeamABV, $players, "home", $endHome);
        
        addCorsiFor($homeTeamABV, $players, "home", $endHome);
        addCorsiAgainst($awayTeamABV, $players, "away", $endHome);
    }
}

function addCorsiFor($team, $players, $whichTeam, $endHome)
{
    global $mysql;
    
    if ($whichTeam == "away")
    {
        $startIndex = 0;
        $endIndex = $endHome;
    }
    else
    {
        $startIndex = $endHome;
        $endIndex = count($players);
    }
    
    for ($i = $startIndex; $i < $endIndex; $i++)
    {
        if ($players[$i]['pos'] != "Goalie")
        {
            $query = "UPDATE AdvancedStats
                    SET cf = cf + 1
                    WHERE jersey = ".$players[$i]['jersey']."
                    AND name = '".$mysql->real_escape_string($players[$i]['name'])."'
                    AND team = '".$team."'";
            $result = $mysql->query($query);
        }
    }
    
    addPlayerToList($team, $whichTeam, $players, $endHome);
    
    return true;
}

function addCorsiAgainst($team, $players, $whichTeam, $endHome)
{
    global $mysql;
    
    if ($whichTeam == "away")
    {
        $startIndex = 0;
        $endIndex = $endHome;
    }
    else
    {
        $startIndex = $endHome;
        $endIndex = count($players);
    }
    
    for ($i = $startIndex; $i < $endIndex; $i++)
    {
        if ($players[$i]['pos'] != "Goalie")
        {
            $query = "UPDATE AdvancedStats
                    SET ca = ca + 1
                    WHERE jersey = ".$players[$i]['jersey']."
                    AND name = '".$mysql->real_escape_string($players[$i]['name'])."'
                    AND team = '".$team."'";
            $result = $mysql->query($query);
        }
    }
    
    addPlayerToList($team, $whichTeam, $players, $endHome);
    
    return true;
}


// right now we're assuming its ev strength (array has indexes)
// 0-5 away
// 6-11 home
function addFenwick($forTeam, $players, $endHome)
{
    global $homeTeamABV, $awayTeamABV;
    
    // away team shot
    if ($forTeam == "away")
    {
        checkPlayers($awayTeamABV, $players, "away", $endHome);
        
        addFenwickFor($awayTeamABV, $players, "away", $endHome);
        addFenwickAgainst($homeTeamABV, $players, "home", $endHome);
    }
    else // $forTeam == "home"
    {
        checkPlayers($homeTeamABV, $players, "home", $endHome);
        
        addFenwickFor($homeTeamABV, $players, "home", $endHome);
        addFenwickAgainst($awayTeamABV, $players, "away", $endHome);
    }
}

function addFenwickFor($team, $players, $whichTeam, $endHome)
{
    global $mysql;
    
    if ($whichTeam == "away")
    {
        $startIndex = 0;
        $endIndex = $endHome;
    }
    else
    {
        $startIndex = $endHome;
        $endIndex = count($players);
    }
    
    for ($i = $startIndex; $i < $endIndex; $i++)
    {
        if ($players[$i]['pos'] != "Goalie")
        {
            $query = "UPDATE AdvancedStats
                    SET ff = ff + 1
                    WHERE jersey = ".$players[$i]['jersey']."
                    AND name = '".$mysql->real_escape_string($players[$i]['name'])."'
                    AND team = '".$team."'";
            $result = $mysql->query($query);
            
            /*
            if ($players[$i]['jersey'] == 76)
            {
                echo $mysql->real_escape_string($players[$i]['name']); echo "<br />";
            }
            */
        }
    }
    
    addPlayerToList($team, $whichTeam, $players, $endHome);
    
    return true;
}

function addFenwickAgainst($team, $players, $whichTeam, $endHome)
{
    global $mysql;
    
    if ($whichTeam == "away")
    {
        $startIndex = 0;
        $endIndex = $endHome;
    }
    else
    {
        $startIndex = $endHome;
        $endIndex = count($players);
    }
    
    for ($i = $startIndex; $i < $endIndex; $i++)
    {
        if ($players[$i]['pos'] != "Goalie")
        {
            $query = "UPDATE AdvancedStats
                    SET fa = fa + 1
                    WHERE jersey = ".$players[$i]['jersey']."
                    AND name = '".$mysql->real_escape_string($players[$i]['name'])."'
                    AND team = '".$team."'";
            $result = $mysql->query($query);
        }
    }
    
    addPlayerToList($team, $whichTeam, $players, $endHome);
    
    return true;
}

?>