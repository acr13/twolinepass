<?php
    require_once('inc/db/stats.inc');
    
    // the beast of this page - get every row in the BasicStats table
    $basicStats = getBasicStats();
    
    //var_dump($basicStats);
    
    $rowsPerPage = 30;
    
    $page = isset($_REQUEST['pg']) ? $_REQUEST['pg'] : 1;
    
    if ($page < 1 || $page > 26)
    {
        $page = 1;
    }
?>

<!DOCTYPE html>
<html lang="en">

<?php $title = "Basic Stats"; require_once('inc/header.inc'); ?>

    <body>
    
    <div id="wrap">
      
        <div id="background-image"></div>
      
        <?php $currentPage = 'stats'; require_once('inc/nav-bar.inc'); ?>

        <div class="container">
            <div class="jumbotron">
            
                <?php
                    if ($page > 1)
                    {
                        echo "<a id='previous-page' href='basic_stats.php?pg=".($page-1)."'>Previous</a>";
                    }
                
                    if ($page < 25)
                    {
                        echo "<a id='next-page' href='basic_stats.php?pg=".($page+1)."'>Next</a>";
                    }
                ?>
            
                <table id="basic-stats" class="table table-striped table-condensed table-responsive">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Team</th>
                        <th>Pos</th>
                        <th>GP</th>
                        <th><a href="?sort=G">G</th>
                        <th><a href="?sort=A">A</th>
                        <th><a href="basic_stats.php">P</th>
                        <th><a href="?sort=PM">+/-</th>
                        <th><a href="?sort=PIM">PIM</th>
                        <th><a href="?sort=PP">PPG</th>
                        <th><a href="?sort=SH">SHG</th>
                        <th><a href="?sort=GW">GWG</th>
                        <th><a href="?sort=OT">OTG</th>
                        <th><a href="?sort=S">S</th>
                        <th><a href="?sort=SP">S%</th>
                        <th><a href="?sort=ToiG">TOI/G</th>
                        <th><a href="?sort=SftG">Sft/G</th>
                        <th><a href="?sort=FO">FO%</th>
                    </tr>
                
                    <?php
                        $startIndex = ($page - 1) * $rowsPerPage; 
                        $endIndex = $startIndex + 30;
                    
                        //$stats = array_slice($basicStats, $startIndex, 30);
                        //var_dump($stats);
                        
                        if (isset($_REQUEST['sort']))
                        {
                            $sort = $_REQUEST['sort'];
                            
                            // S%, Sft/G, FO%
                            if ($sort == 'SP' || $sort == 'SftG' || $sort == 'FO')
                            {
                                usort($basicStats, build_cmpFloat($sort));
                            }
                            else if ($sort == 'ToiG') // and TOI/G
                            {
                                usort($basicStats, build_cmpTOIG($sort));
                            }
                            else
                            {
                                usort($basicStats, build_cmp($sort));
                            }
                        }
                    
                        for ($i = $startIndex; $i < $endIndex; $i++)
                        {
                            $row = $basicStats[$i];
                            
                            echo "<tr>";
                            
                            foreach ($row as $key => $val)
                            {
                                echo "<td>".$val."</td>";
                            }
                            
                            echo "</tr>";
                        }
                    ?>
                
                </table>
            </div>
        </div> <!-- /container -->
    </div> <!-- /wrap -->
  
    <div id="footer" class="blueline"></div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../../assets/js/jquery.js"></script>
    <script src="../../dist/js/bootstrap.min.js"></script>
    <!-- Two-line pass scripts -->
    <script src="js/twolinescripts.js"></script>
    <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  
    ga('create', 'UA-46386605-1', 'twolinepass.ca');
    ga('send', 'pageview');
  
  </script>
  </body>
</html>