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
<script language="javascript" type="text/javascript">
var controlScript = "control.php";
var clickEventType=((document.ontouchstart!==null)?'click':'touchstart');
var PreviousInfo = null;

function setAlias(){
  MPDPORT = document.getElementsByClassName("MPDPORT")[0].id;
  MPDHOST = document.getElementsByClassName("MPDHOST")[0].id;
  PASSWORD = document.getElementsByClassName("PASSWORD")[0].id; 
//  LABEL = document.getElementsByClassName("LABEL")[0].id;  
}
function getCmd(id){  
  var x = document.getElementById(id);
  xmlhttp=new XMLHttpRequest();
  params = controlScript;
  params += "?a=" + id;
  params += "&m=" + MPDPORT;
  params += "&h=" + MPDHOST;
  params += "&p=" + PASSWORD;
  xmlhttp.open("GET",params,false);
  xmlhttp.send();
}
function postCmd(command,id) {
  xmlhttp=new XMLHttpRequest();
  xmlhttp.open("POST",controlScript,true);
  params="a=" + command
    + "&b=" + id 
    + "&m=" + window.MPDPORT 
    + "&h=" + window.MPDHOST
    + "&p=" + window.PASSWORD; 
  xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
  xmlhttp.setRequestHeader("Content-length", params.length);
  xmlhttp.setRequestHeader("Connection", "close");
  xmlhttp.send(params);
}
function autoRefresh(id) {
  setTimeout(function(){ autoRefresh(id) },3000);
  xmlhttp=new XMLHttpRequest();
//  params=controlScript + "?a=" + id + "&b=" + window.MPDPORT;
  params = controlScript;
  params += "?a=" + id;
  params += "&m=" + MPDPORT;
  params += "&h=" + MPDHOST;
  params += "&p=" + PASSWORD;
  xmlhttp.open("GET",params,true);
  xmlhttp.send();
  xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
      var CurrentInfo = xmlhttp.responseText;
      // Compare new text with stored one
      if(CurrentInfo !== PreviousInfo && !isEmpty(CurrentInfo)) {
        var infoWin = document.getElementById(id);
        infoWin.innerHTML = CurrentInfo;
        window.PreviousInfo = CurrentInfo;
        playListener();
      }
    }
  }
}
function isEmpty(str) {
    return (!str || 0 === str.length);
}
function initialise() {
  setAlias();
  var id = document.getElementsByTagName('section')[0].id;
  autoRefresh(id);
  toolbarListener();
}
function pushed(id){
    document.getElementById(id).classList.add('pushed');
    document.getElementById(id).classList.remove('released');
}
function toolbarListener() {
  var classname = document.getElementsByClassName("quarter");
  function pusher(e){
    var id = e.currentTarget.id;
    pushed(id);
  }
  function released(e){
    var id = e.currentTarget.id;
    var x = document.getElementById(id);
    if (x.classList.contains("pushed")) {
      document.getElementById(id).classList.add('released');
      document.getElementById(id).classList.remove('pushed');
      getCmd(id);
    }
  }
  for(var i=0; i<classname.length; i++) {
      classname[i].addEventListener(clickEventType, pusher, false);
      classname[i].addEventListener("animationend", released, false);
      classname[i].addEventListener("webkitAnimationEnd", released, false);
  }
}
function playListener() {
  var playButton = document.getElementsByClassName("play");
  function otherPusher(e) {
    var nid = e.currentTarget.id;
    var x = document.getElementById(nid);
    if (x.classList.contains("confirm")) {
      postCmd("play",nid);
      window.location.href = "index.php?MPDPORT=" + window.MPDPORT + "&LABEL=" + window.LABEL;
    } else {
      x.classList.add('pushed');
      x.classList.remove('released');
    }
  }
  function confirmer(e) { 
    var id = e.currentTarget.id;
    var x = document.getElementById(id);
    if (x.classList.contains("pushed")) {
        // Fires after push, 
        x.classList.add('confirm');
        var shapes = x.getElementsByClassName("playPath");
        shapes[0].style.fill = "#eee8d5";
        x.classList.remove('pushed');
    } else if (x.classList.contains("confirm")) {
//        x.classList.remove('confirm');
        setTimeout(function(){ buttonTimeout(id) },2200);
    } else {
        x.classList.add('released');
        var shapes = x.getElementsByClassName("playPath");
        shapes[0].style.fill = "#93A1A1";
    }
  }
  function buttonTimeout(id) {
    document.getElementById(id).classList.remove("confirm");
    document.getElementById(id).classList.add('released');
  }
  for(var i=0; i<playButton.length; i++) {
      playButton[i].addEventListener(clickEventType, otherPusher, false);
      playButton[i].addEventListener("animationend", confirmer, false);
      playButton[i].addEventListener("webkitAnimationEnd", confirmer, false);
  }
}
initialise();
</script>
<style type="text/css">
  html {   
    position: relative;    
    min-height: 100%;    
    -webkit-touch-callout: none;    
    -webkit-user-select: none;    
    -khtml-user-select: none;    
    -moz-user-select: none;    
    -ms-user-select: none;    
    user-select: none;
    -webkit-tap-highlight-color: rgba(0,0,0,0);
}    
body {    
  margin: 0 0 60px 0;    
  max-width: 1200px;    
  background-color: #002b36 ;    
  color: #eee8d5 ;    
  font-family: sans-serif;   
  font-size: 120%;    
  font-weight: 400;     
}
ul {  
  border: 0;  
  margin: 90px 0 0 0;
  padding: 0;  
}   
li {  
  border-top: 1px solid #073642;
  padding: 0;  
}  
img.playbtn {    
  height: 60px;    
  display: block;    
  margin-left: auto;  
  margin-right: auto;  
}   
svg.play {    
  height: 40px;    
  display: block;    
  margin: auto;
/*  margin-left: auto;  
  margin-right: auto;  */
} 
svg.toolbar {  
  pointer-events: none;
  height: 60px;  
  display: block;  
  margin-left: auto;  
  margin-right: auto;  
}    
.playPath {
  fill: #93A1A1;
}

nav { 
  display: table; 
  overflow: hidden;  
  margin: 0;  
  padding: 0;  
  height: 60px;  
  line-height: 60px;  
  text-align: center;  
/*  background-color: #002b36;  */
  background-color: #073642;
  position: fixed;  
  top: 0;  
  width: 100%;  
  max-width: 1200px;  
  z-index: 100;  
}
.row {
  display: table;
  width: 100%;
}  
.banner h3 {
  padding: 3px;
  margin: 0;
  font-size: .8em;
  line-height: 20px;
}
 .info-container{
  margin: 100px auto 30px auto;
}

#dn { 
  background-color: #b58900;
}
#up { 
  background-color: #cb4b16;
}
#fw { 
  background-color: #dc322f;
}
#tog {  
  background-color: #d33682;
  color: #002b36;
  font-weight:800;
  font-size: 3.75em;
}
#tog a, a:active { 
  text-decoration: none;
  color: #002b36;
}
.button { 
  display: table-cell;  
  width: 200px;
  height: 60px;
  line-height: 60px;
  background-color: #268bd2;
  border: 3px solid #002b36;
  border-radius: 16px;
  text-align: center;
  text-decoration: none;
}
.button a { 
  text-decoration: none;
  font-weight: 800;
  color: #002b36; 
}
.quarter {  
  display: table-cell;  
  height: 60px;  
  line-height: 60px;  
  width: 25%; 
  border-radius: 15px; 
  border: 3px solid #002b36;
}    
.full {  
  display: block;  
  height: 60px;  
  line-height: 60px;  
  width: 100%;  
}   

.playbtn {  
  height: 100%;   
  width: 80px;
  float: right;
}   
.solbrmagenta { color: #6c71c4; }  
.solblue { color: #268bd2; }  
.solcyan { color: #2aa198; }  
.solbrblack { color: #002b36; }

.animated {
  -webkit-animation-duration: .12s;
  animation-duration: .12s;
  -webkit-animation-fill-mode: both;
  animation-fill-mode: both;
  transform: translate3d(0,0,0);
  -webkit-transform: translate3d(0,0,0);
}
.pushed {
  -webkit-animation-name: pusher;
  animation-name: pusher;
}
.timeout {
  -webkit-animation-name: pusher;
  animation-name: pusher;
}
.released {
  -webkit-animation-name: releaser;
  animation-name: releaser;
}
.confirm {
  -webkit-animation-name: confirmer;
  animation-name: confirmer;
  height: 100px;
}
path.confirm {
  fill: red;
  width: 1.5em;
  height: 1.5em;
}

@-webkit-keyframes pusher {
  0% {
    -webkit-transform: scale3d(1, 1, 1);
    transform: scale3d(1, 1, 1);
  }
  100% {
    -webkit-transform: scale3d(1, 0, 1);
    transform: scale3d(1, 0, 1);
  }
}

@keyframes pusher {
  0% {
    -webkit-transform: scale3d(1, 1, 1);
    transform: scale3d(1, 1, 1);
  }
  100% {
    -webkit-transform: scale3d(1, 0, 1);
    transform: scale3d(1, 0, 1);
  }
}
@-webkit-keyframes releaser {
  0% {
    -webkit-transform: scale3d(0, 1, 1);
    transform: scale3d(0, 1, 1);
  }
  100% {
    -webkit-transform: scale3d(1, 1, 1);
    transform: scale3d(1, 1, 1);
  }
}

@keyframes releaser {
  0% {
    -webkit-transform: scale3d(0, 1, 1);
    transform: scale3d(0, 1, 1);
  }
  100% {
    -webkit-transform: scale3d(1, 1, 1);
    transform: scale3d(1, 1, 1);
  }
}
@-webkit-keyframes confirmer {
  0% {
    -webkit-transform: scale3d(0, 1, 1);
    transform: scale3d(0, 1, 1);
  }
  100% {
    -webkit-transform: scale3d(2,2,2);
    transform: scale3d(2,2,2);
  }
}
@keyframes confirmer {
  0% {
    -webkit-transform: scale3d(0, 1, 1);
    transform: scale3d(0, 1, 1);
  }
  100% {
    -webkit-transform: scale3d(2, 2, 2);
    transform: scale3d(2, 2, 2);
  }
}
</style>
</body>
</html>

