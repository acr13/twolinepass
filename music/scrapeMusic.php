<?php

require_once('phpQuery.inc');

require_once('/home/twolinep/public_html/inc/dbinfo.inc');

$mysql = new mysqli("localhost", $username, $password, "twolinep_music");

// ultimate scraper 3000
// author: acr

/*
 *TODO: SETUP A MOVE TO HISTORY TABLE INCASE SOMETHING GOES WRONG... FOR NOW... SCREW IT
$query = "INSERT INTO BasicStats_History (Timestamp, Rank, Name, Team, POS, GP, G, A,P, PM, PIM, PP, SH, GW, OT, S, SP, ToiG, SftG, FO) 
            SELECT UNIX_TIMESTAMP(), Rank, Name, Team, POS, GP, G, A,P, PM, PIM, PP, SH, GW, OT, S, SP, ToiG, SftG, FO
            FROM BasicStats";
if (!$mysql->query($query))
{
    die('unable to move basic stats to history table');
}
*/

$url = "http://www.billboard.com/charts/hot-100";

phpQuery::newDocumentFileHTML($url);

// song titles
$song_titles = pq('div.chart_listing article header h1');
$artist_titles = pq('div.chart_listing article header p.chart_info a');
$album_titles = pq('div.chart_listing article header p.chart_info');

$songs = array();
$artists = array();
$albums = array();

foreach ($song_titles as $title)
{
    //echo $title->nodeValue . "<br />";
    $songs[] = trim($title->nodeValue);
}

foreach ($artist_titles as $artist)
{
    //echo $artist->nodeValue . "<br />";
    $artists[] = trim($artist->nodeValue);
}

//var_dump($artists); echo "<br /><br />";

$i = 0;
foreach ($album_titles as $album)
{
    $album = trim(str_replace($artists[$i], "", $album->nodeValue));
    
    if ($album == "")
    {
       $albums[] = "";
    }
    else
    {
        $albums[] = $album;
    }
    
    $i++;
}

$query = "INSERT INTO BillboardHot (Rank, Song, Artist, Album) VALUES ";

for ($i = 0; $i < count($songs); $i++)
{
    //echo $artists[$i] . " - " . $songs[$i] . " (".$albums[$i].") <br />";
    $query .= "(".($i+1).", '".$songs[$i]."', '".$artists[$i]."', '".$albums[$i]."'), ";
}

// remove the comma space from the last value pair
$query = substr($query, 0, -2);

//var_dump($query);

if (!$mysql->query($query))
{
    die('unable to add top 10 to billboard table');
}

echo "success";

?>