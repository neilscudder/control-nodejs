<?php 
// AUTHORITY 0.1.2 Copyright 2015 @neilscudder
// Licenced under the GNU GPL <http://www.gnu.org/licenses/>
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);
?>
<html>
<head>
<title>Playnode Control Authority</title>
<meta name="viewport"
content="width=device-width, initial-scale=1.5, maximum-scale=1.5, user-scalable=no" />
</head>
<body>
<form 
  action="authority.php" 
  method="get" 
  accept-charset="UTF-8"
  enctype="application/x-www-form-urlencoded" 
  autocomplete="off" 
  novalidate>
  Playnode label:<br>
  <input type="text" name="LABEL">
  <br>
  User email:<br>
  <input type="text" name="EMAIL" value="Not optional">
  <br>
  MPD port:<br>
  <input type="text" name="MPDPORT" value="6600">
  <br>
  MPD host:<br>
  <input type="text" name="MPDHOST" value="localhost">
  <br>
  MPD password:<br>
  <input type="text" name="MPDPASS">
  <br>
  Server:<br>
  <input type="text" name="CONTROLSERVER">
  <br><br>
  <input type="submit" value="Submit">
</form>
</body>
</html>

<?php

function setKeys($userEmail){
 
  $controlURL = $_GET['CONTROLSERVER'] . "?m=" . $_GET['MPDPORT'];
  $authRow = array( "MPDPORT" => $_GET['MPDPORT'] );
  $authRow['EMAIL'] = $userEmail;

  if (!empty($_GET['MPDPASS'])) {
    $controlURL .= "&p=" . $_GET['MPDPASS'];
    $authRow['MPDPASS'] = $_GET['MPDPASS'];
  }
  if (!empty($_GET['MPDHOST'])) {
    $controlURL .= "&h=" . $_GET['MPDHOST'];
    $authRow['MPDHOST'] = $_GET['MPDHOST'];
  }
  if (!empty($_GET['LABEL'])) {
    $controlURL .= "&l=" . $_GET['LABEL'];
    $authRow['LABEL'] = $_GET['LABEL'];
  }

  $authRow['KPASS'] = passGenerator();
  $authRow['RPASS'] = passGenerator();

  $controlURL .= "&k=" . $authRow['KPASS'];
  $resetURL = $_GET['CONTROLSERVER'] . "authority.php?r=" . $authRow['RPASS'];

  printURL($controlURL, "Control Link");
  printURL($resetURL, "Reset Link");

  var_dump($authRow);

  $m = new MongoClient("mongodb://webserver:webmunster@localhost/authority");
  $db = $m->authority;
  $collection = $db->keys;
  $collection->insert($authRow);

  echo "PHEW";
}

function passGenerator() {
  // http://stackoverflow.com/q/19017694/5045643 :
  $pass=substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 1).substr(md5(time()),1);
  return $pass;
}

function printURL($url, $name){
  echo "
    <p>
      <a href='{$url}'>{$name}</a>
    </p>
  ";
}

if (!empty($_GET['r'])) { 
  resetKeys($_GET['r']);
}
if (!empty($_GET['EMAIL'])) { 
  setKeys($_GET['EMAIL']);
}

?>
