<?php

//ini_set('display_errors',1);
//ini_set('display_startup_errors',1);
//error_reporting(-1);

require_once('/home/twolinep/public_html/inc/dbinfo.inc');

$mysql = new mysqli("localhost", $username, $password, "twolinep_stats");

// ultimate scraper 3000
// author: acr

// STEP ONE ********************************************************
// Move the current contents of the 'active' table to the 'history' table, but add a timestamp incase we fuck up
// this will be a beast of a query as this table grows in size... maybe ** TODO ** write a cron job to
// get only keep the last 4-5 backups ??? ~~3k rows max

$query = "INSERT INTO BasicStats_History (Timestamp, Rank, Name, Team, POS, GP, G, A,P, PM, PIM, PP, SH, GW, OT, S, SP, ToiG, SftG, FO) 
            SELECT UNIX_TIMESTAMP(), Rank, Name, Team, POS, GP, G, A,P, PM, PIM, PP, SH, GW, OT, S, SP, ToiG, SftG, FO
            FROM BasicStats";
if (!$mysql->query($query))
{
    die('unable to move basic stats to history table');
}

// Clear the active table
$query = "DELETE FROM BasicStats";
if (!$mysql->query($query))
{
    die('unable to delete all rows from basic stats');
}

$start = time();
$stop = false;
$page = 1;

while (!$stop) // gutty
{
    // get the stupid html
    $html = file_get_contents('http://www.nhl.com/ice/playerstats.htm?pg='.$page);
    $doc = new DOMDocument();
    $doc->loadHTML($html);
    
    // getting an xpath object makes querying the dom 1000x easier
    $xpath = new DOMXPath($doc);
    
    // get the table, then get the tbody element, then get all the rows in it (so sick)
    $rows = $xpath->query('//table[@summary="2013-2014 - Regular Season - Skater - Summary - Points"]/tbody/tr');
    
    // start the mySQL query
    $query = "INSERT INTO BasicStats (Rank, Name, Team, POS, GP, G, A, P, PM, PIM, PP, SH, GW, OT, S, SP, ToiG, SftG, FO) VALUES ";
    
    foreach ($rows as $row)
    {
        // the last page of stats will have <30 rows
        if ($rows->length < 30)
        {
            $stop = true;
        }
        
        $query .= "(";
        // get all of cols (cells)
        $cols = $xpath->query('.//td', $row);
        
        // append each value to the query, quoting any strings (for the DB)
        $query .= $cols->item(0)->nodeValue . ", ";
        $query .= "'" . $mysql->real_escape_string($cols->item(1)->nodeValue) . "', ";
        $query .= "'" . $cols->item(2)->nodeValue . "', ";
        $query .= "'" . $cols->item(3)->nodeValue . "', ";
        $query .= $cols->item(4)->nodeValue . ",";
        $query .= $cols->item(5)->nodeValue . ",";
        $query .= $cols->item(6)->nodeValue . ",";
        $query .= $cols->item(7)->nodeValue . ",";
        $query .= "'" . $cols->item(8)->nodeValue . "', "; // plus minus
        $query .= $cols->item(9)->nodeValue . ",";          // PIM
        $query .= $cols->item(11)->nodeValue . ",";         // PP
        $query .= $cols->item(13)->nodeValue . ",";         // SH
        $query .= $cols->item(14)->nodeValue . ",";         // GW
        $query .= $cols->item(15)->nodeValue . ",";         // OT
        $query .= $cols->item(16)->nodeValue . ",";         // S
        $query .= $cols->item(17)->nodeValue . ",";         // S%
        $query .= "'" . $cols->item(18)->nodeValue . "', ";
        $query .= $cols->item(19)->nodeValue . ",";
        $query .= $cols->item(20)->nodeValue;
        
        $query .= "), ";
    }
    
    //strip the last ', ' from the query - last 2 chars
    $query = substr($query, 0 , -2);

    // add it to the db
    $result = $mysql->query($query);
    
    if (!$result)
    {
        echo $mysql->error;
        echo "<br />";
    }
        
    if ($stop)
    {
        break;
    }
    
    $page++;
}

$mysql->close();

$end = time();

echo "success<br />";
echo "elapsed time = ".($end - $start)." seconds.";
exit();

?>