<!DOCTYPE html>
<html lang="en">
	
<?php require_once('inc/header.inc'); ?>
	
  <body>
    
    <div id="wrap">
      
      <div id="background-image"></div>
      
      <!-- Fixed navbar -->
      <?php $currentPage = "about"; require_once('inc/nav-bar.inc'); ?>

      <div class="container">
        <!-- Main component for a primary marketing message or call to action -->
        <div class="jumbotron">
            <h2>About</h2>
          <p>Two-line Pass is my own personal platform to calculate, analyze, and display the advanced statistics of the 2013-2014 NHL season.</p>
          <p>I parse each HTML game-log from the NHL's <a href="www.nhl.com">website</a>, then calculate, and store each advanced stat in my own dataset to be displayed here.</p>
          <p>If advanced hockey stats are new to you, here are the definitions:</p>
          <ul>
            <li><b>Fenwick:</b> Shots on net, as well as shot attempts that miss the net while a player is on the ice.</li>
            <li><b>FF:</b> Fenwick For</li>
            <li><b>FA:</b> Fenwick Against</li>
            <li><b>FF%</b> (Fenwick For %): 100 * FF / (FF + FA)</li>
            <br />
            <li><b>Corsi:</b> Shots on net, missed shots, and blocked shot attempts while a player is on the ice.</li>
            <li><b>CF:</b> Corsi For</li>
            <li><b>CA:</b> Corsi Against</li>
            <li><b>CF%</b> (Corsi For %): 100 * CF / (CF + CA)</li>
          </ul>
          <br />
          <p>If you have any questions, comments, or concerns, please email me at: alex at alexcr dot com</p>
          <p>Alex</p>
        </div>
  
      </div> <!-- /container -->
    </div>
  
    <div id="footer" class="blueline"></div>
 
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../../assets/js/jquery.js"></script>
    <script src="../../dist/js/bootstrap.min.js"></script>
    <!-- Two-line pass scripts -->
    <script src="js/twolinescripts.js"></script>
  </body>
</html>