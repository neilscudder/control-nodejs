<?php

setlocale(LC_CTYPE, "en_US.UTF-8"); // Fixes non ascii characters with escapeshellarg

// BUG error log fills when these values not provided
$portAlias=$_GET["portAlias"];
$playnode=$_GET["playnode"];
?>

<!DOCTYPE html>

<head>
<title><?php echo $playnode; ?></title>
<meta name="viewport"
content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<link rel="stylesheet" href="css/control.css" />
<link rel="icon" sizes="192x192" href="../sites/default/files/icon_playnode_0.png">
</head>

<body class="" ontouchstart="">
  <nav>
    <div class="row">
      <div id="dn" class="animated quarter">
        <svg class="toolbar" id="dn" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
          viewBox="0 0 24 24" enable-background="new 0 0 24 24" xml:space="preserve">
          <path fill="#002B36" d="M18.5,12c0-1.77-1.02-3.29-2.5-4.03v8.05C17.48,15.29,18.5,13.77,18.5,12z M5,9v6h4l5,5V4L9,9H5z"/>
          <path fill="none" d="M0,0h24v24H0V0z"/>
        </svg>
      </div>
      <div id="up" class="animated quarter">
        <svg class="toolbar" id="up" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
          viewBox="0 0 48 48" enable-background="new 0 0 48 48" xml:space="preserve">
          <path fill="#002B36" d="M6,18v12h8l10,10V8L14,18H6z M33,24c0-3.54-2.04-6.58-5-8.06v16.1C30.96,30.58,33,27.54,33,24z M28,6.46
            v4.12C33.779,12.3,38,17.66,38,24s-4.221,11.7-10,13.42v4.12c8.02-1.82,14-8.979,14-17.54C42,15.44,36.02,8.28,28,6.46z"/>
          <path fill="none" d="M0,0h48v48H0V0z"/>
        </svg>
      </div>
      <div id="fw" class="animated quarter">
        <svg class="toolbar" id="fw" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
          viewBox="0 0 48 48" enable-background="new 0 0 48 48" xml:space="preserve">
          <path fill="#002B36" d="M12,36l17-12L12,12V36z M32,12v24h4V12H32z"/>
          <path fill="none" d="M0,0h48v48H0V0z"/>
        </svg>
      </div>
      <div id="tog" class="quarter">
        <a href="index.php?portAlias=<?php echo $portAlias; ?>&playnode=<?php echo $playnode; ?>">
          <svg class="toolbar" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
            viewBox="0 0 48 48" enable-background="new 0 0 48 48" xml:space="preserve">
            <path fill="#002B36" d="M38,12.82L35.18,10L24,21.18L12.82,10L10,12.82L21.18,24L10,35.18L12.82,38L24,26.82L35.18,38L38,35.18
              L26.82,24L38,12.82z"/>
            <path fill="none" d="M0,0h48v48H0V0z"/>
          </svg>
        </a>
      </div> 
    </div>

    <div class="row">
      <div class="banner">
        <h3><?php echo $playnode;?></h3> 
      </div>
    </div>
  </nav>  
  
  
  <main>
    <!-- Browswer -->
    <section id="browser">
    </section>
    <!-- END Browser -->
  </main>
<div class="portAlias" id="<?php echo $portAlias; ?>"></div>
<div class="playnode" id="<?php echo $playnode; ?>"></div>
<script language="javascript" type="text/javascript" src="js/control.js"></script>
</body>
</html>

