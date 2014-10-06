<?php
    $username = "twolinep_admin";
    $password = "(O7Px5SNw0eM";

    function getBasicStats()
    {
        global $username;
        global $password;
        
        $mysql = new mysqli("localhost", $username, $password, "twolinep_stats");
        
        $query = "SELECT Rank, Name, Team, POS, GP, G, A, P, PM, PIM, PP, SH, GW, OT, S, SP, ToiG, SftG, FO FROM BasicStats ORDER BY Rank ASC";
        
        $result = $mysql->query($query);

        $stats = array();
        
        while ($row = $result->fetch_assoc())
        {
            $player = array(
                "Rank" => $row['Rank'],
                "Name" => $row['Name'],
                "Team" => $row['Team'],
                "POS" => $row['POS'],
                "GP" => $row['GP'],
                "G" => $row['G'],
                "A" => $row['A'],
                "P" => $row['P'],
                "PM" => $row['PM'],
                "PIM" => $row['PIM'],
                "PP" => $row['PP'],
                "SH" => $row['SH'],
                "GW" => $row['GW'],
                "OT" => $row['OT'],
                "S" => $row['S'],
                "SP" => $row['SP'],
                "ToiG" => $row['ToiG'],
                "SftG" => $row['SftG'],
                "FO" => $row['FO']
            );
            
            $stats[] = $player;
        }
        
        return $stats;
    }
    
    // Compairison function for CF%
    function cmp($a, $b)
    {
        $x = floatval($a['CF%']);
        $y = floatval($b['CF%']);
        
        //echo 'x = '.$x.' <br />';
        //echo 'y = '.$y.' <br />';
        //die();
        
        $r = null;
        
        if ($x == $y)
            $r = 0;
        else if ($x > $y)
            $r =  -1;
        else
            $r = 1;
            
        //echo "r = ".$r."<br />";
        //die();
        
        
        return $r;
    }
    
    function getAdvancedStats()
    {
        global $username;
        global $password;
        
        $mysql = new mysqli("localhost", $username, $password, "twolinep_stats");
        
        $query = "SELECT MAX(GP) as MaxGP From AdvancedStats";
        $result = $mysql->query($query);
        $row = $result->fetch_assoc();
        $maxGP = $row['MaxGP'];
        $cutoff = ceil($maxGP * 0.5);
        
        $query = "SELECT name, team, pos, GP, ff, fa, cf, ca FROM AdvancedStats WHERE GP > ".$cutoff." ORDER BY FF DESC";
        
        $result = $mysql->query($query);

        $stats = array();
        
        while ($row = $result->fetch_assoc())
        {
            $player = array(
                "Name" => $row['name'],
                "Team" => $row['team'],
                "Pos" => $row['pos'],
                "GP" => $row['GP'],
                "FF" => $row['ff'],
                "FA" => $row['fa'],
                "CF" => $row['cf'],
                "CA" => $row['ca'],
            );
            
            // calculate CF and FF percentage
            $ff = intval($row['ff']);
            $fa = intval($row['fa']);
            $cf = intval($row['cf']);
            $ca = intval($row['ca']);
            
            $cfPercentage = 100 * ($cf / ($cf + $ca));
            $ffPercentage = 100 * ($ff / ($ff + $fa));
            
            $player['CF%'] = number_format($cfPercentage, 2);
            $player['FF%'] = number_format($ffPercentage, 2);
            
            $stats[] = $player;
        }
        
        usort($stats, 'cmp');
        
        return $stats;
    }

    
    function build_cmp($key)
    {
        return function ($a, $b) use ($key)
        {
            $x = intval($a[$key]);
            $y = intval($b[$key]);
            
            
            if ($x == $y)
                return 0;
            else if ($x > $y)
                return -1;
            else
                return 1;
        };
    }
    
    function build_cmpFloat($key)
    {
        return function ($a, $b) use ($key)
        {
            $x = floatval($a[$key]);
            $y = floatval($b[$key]);
            
            
            if ($x == $y)
                return 0;
            else if ($x > $y)
                return -1;
            else
                return 1;
        };
    }
    
    function build_cmpTOIG($key)
    {
        return function ($a, $b) use ($key)
        {
            $x = intval(str_replace(':', '', $a[$key]));
            $y = intval(str_replace(':', '', $b[$key]));
            
            
            if ($x == $y)
                return 0;
            else if ($x > $y)
                return -1;
            else
                return 1;
        };
    }
    

?>