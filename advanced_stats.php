<?php
    require_once('inc/db/stats.inc');
    
    $stats = getAdvancedStats();
    
    //var_dump($stats);
    //die();
    
    $rowsPerPage = 30;
    
    $page = isset($_REQUEST['pg']) ? $_REQUEST['pg'] : 1;
    
    if ($page < 1 || $page > 26)
    {
        $page = 1;
    }
?>

<!DOCTYPE html>
<html lang="en">

<?php $title = "Advanced Stats"; require_once('inc/header.inc'); ?>

    <body>
    
    <div id="wrap">
      
        <div id="background-image"></div>
      
        <?php $currentPage = 'advanced'; require_once('inc/nav-bar.inc'); ?>

        <div class="container">
            <div class="jumbotron">
            
                <?php
                    if ($page > 1)
                    {
                        echo "<a id='previous-page' href='advanced_stats.php?pg=".($page-1)."'>Previous</a>";
                    }
                
                    if ($page < 25)
                    {
                        echo "<a id='next-page' href='advanced_stats.php?pg=".($page+1)."'>Next</a>";
                    }
                ?>
            
                <table id="advanced-stats" class="table table-striped table-condensed table-responsive">
                    <tr>
                        <!-- <th>#</th> -->
                        <th>Name</th>
                        <th>Team</th>
                        <th>Position</th>
                        <th><a href="?sort=GP">GP</a></th>
                        <th><a href="?sort=FF">FF</a></th>
                        <th><a href="?sort=FA">FA</a></th>
                        <th><a href="?sort=FF%">FF%</a></th>
                        <th><a href="?sort=CF">CF</a></th>
                        <th><a href="?sort=CA">CA</a></th>
                        <th><a href="?sort=CF%">CF%</a></th>
                    </tr>
                
                    <?php
                        $startIndex = ($page - 1) * $rowsPerPage; 
                        $endIndex = $startIndex + 30;
                        
                        //var_dump($stats[0]);
                        
                        if (isset($_REQUEST['sort']))
                        {
                            $sort = $_REQUEST['sort'];
                            
                            // S%, Sft/G, FO%
                            if ($sort == 'FF%' || $sort == 'CF%')
                            {
                                usort($stats, build_cmpFloat($sort));
                            }
                            else
                            {
                                usort($stats, build_cmp($sort));
                            }
                        }
                    
                        for ($i = $startIndex; $i < $endIndex; $i++)
                        {
                            $row = $stats[$i];
                            
                            echo "<tr>";
                            
                            echo "<td>".$row['Name']."</td>";
                            echo "<td>".$row['Team']."</td>";
                            echo "<td>".$row['Pos']."</td>";
                            echo "<td>".$row['GP']."</td>";
                            echo "<td>".$row['FF']."</td>";
                            echo "<td>".$row['FA']."</td>";
                            echo "<td>".$row['FF%']."</td>";
                            echo "<td>".$row['CF']."</td>";
                            echo "<td>".$row['CA']."</td>";
                            echo "<td>".$row['CF%']."</td>";
                            
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