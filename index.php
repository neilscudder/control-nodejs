<?php
// CONTROL 0.1.6 Copyright 2015 @neilscudder
// Licenced under the GNU GPL <http://www.gnu.org/licenses/>

setlocale(LC_CTYPE, "en_US.UTF-8"); // Fixes non ascii characters with escapeshellarg

if (isset($_GET["m"])) {
  $MPDPORT=$_GET["m"];
}
if (isset($_GET["h"])) {
  $MPDHOST=$_GET["h"];
}
if (isset($_GET["p"])) {
  $PASSWORD=$_GET["p"];
}
if (isset($_GET["l"])) {
  $LABEL=$_GET["l"];
} elseif (isset($MPDHOST){
  $LABEL="Music server: $MPDHOST";
} else {
  $LABEL="Music server: localhost";
}
?>

<!DOCTYPE html>

<head>
<title><?php echo $LABEL; ?></title>
<meta name="viewport"
content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<link rel="stylesheet" href="css/control.css" />
<link rel="icon" sizes="192x192" href="../sites/default/files/icon_LABEL_0.png">
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
	<a href="browser.php?MPDPORT=<?php echo $MPDPORT; ?>&LABEL=<?php echo $LABEL; ?>">
          <svg class="toolbar" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
            viewBox="0 0 48 48" enable-background="new 0 0 48 48" xml:space="preserve">
            <path fill="none" d="M0,0h48v48H0V0z"/>
            <path fill="#002B36" d="M30,12H6v4h24V12z M30,20H6v4h24V20z M6,32h16v-4H6V32z M34,12v16.359C33.38,28.141,32.7,28,32,28
              c-3.32,0-6,2.68-6,6s2.68,6,6,6s6-2.68,6-6V16h6v-4H34z"/>
          </svg>
        </a>
      </div> 
    </div>
    <div class="row">
      <div class="banner">
        <h3><?php echo $LABEL;?></h3> 
      </div>
    </div>
  </nav>    
  <main>
    <!-- Viewer -->
    <section id="info"><br><br><br><br><br><br><br><br><br>just a sec..
    </section>
    <!-- END Viewer -->    
  </main>
<div class="MPDPORT" id="<?php echo $MPDPORT; ?>"></div>
<div class="MPDHOST" id="<?php echo $MPDHOST; ?>"></div>
<div class="PASSWORD" id="<?php echo $PASSWORD; ?>"></div>
<div class="LABEL" id="<?php echo $LABEL; ?>"></div>
<script language="javascript" type="text/javascript" src="js/control.js"></script>
</body>
</html>

