<?php

$genre = isset($_REQUEST['genre']) ? $_REQUEST['genre'] : -1;

require_once('/home/twolinep/public_html/inc/dbinfo.inc');

$mysql = new mysqli("localhost", $username, $password, "twolinep_music");

if ($genre == -1)
{
    die('invalid genre');
}

// to be filled
$music = array();

switch ($genre)
{
    case "hot":
        
        $query = "SELECT * FROM BillboardHot";
        
        $result = $mysql->query($query);
        
        while ($row = $result->fetch_assoc())
        {
            $music[] = $row;
        }
        
        break;
    default:
        
        echo "Default";
        
        break;
}

die(json_encode(array("resp" => "success", "music" => json_encode($music))));

?>