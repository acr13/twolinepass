<!-- Fixed navbar -->
<div class="navbar navbar-default navbar-fixed-top blueline">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php">Two-line Pass</a>
    </div>
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li <?php if ($currentPage == 'home') { echo "class='active'"; } ?>><a href="index.php">Home</a></li>
        <li <?php if ($currentPage == 'stats') { echo "class='active'"; } ?>><a href="basic_stats.php">Stats</a></li>
        <li <?php if ($currentPage == 'advanced') { echo "class='active'"; } ?>><a href="advanced_stats.php">Advanced Stats</a></li>
        <li <?php if ($currentPage == 'about') { echo "class='active'"; } ?>><a href="about.php">About</a></li>
        
        <!--
        <li><a href="#contact">Contact</a></li>
        
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li class="divider"></li>
            <li class="dropdown-header">Nav header</li>
            <li><a href="#">Separated link</a></li>
            <li><a href="#">One more separated link</a></li>
          </ul>
        </li>
        -->
        
      </ul>
    </div><!--/.nav-collapse -->
  </div>
</div>