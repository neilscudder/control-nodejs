<?php 
// AUTHORITY 0.1.3 Copyright 2015 @neilscudder
// Licenced under the GNU GPL <http://www.gnu.org/licenses/>
setlocale(LC_CTYPE, "en_US.UTF-8"); 
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);
?>
<html>
<head>
<title>Playnode Control Authority</title>
<meta 
  name="viewport"
  content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<style type="text/css">
body{
  background-color: #002b36;
  font-family: sans-serif;
  color: #93a1a1;
}
input{
  font-size: 1.3em;
  color: #002b36;
  background-color: #eee8d5;
  width: 90%;
}
h4{
  margin: 5px 0 5px 0;
}
a{
  color: #eee8d5;
}

</style>
</head>
<body>
<form 
  action="authority.php" 
  method="get" 
  accept-charset="UTF-8"
  enctype="application/x-www-form-urlencoded" 
  autocomplete="off" 
  novalidate>
  <h4>Playnode label:</h4>
  <input type="text" name="LABEL" value="Office">
  <h4>User email:</h4>
  <input type="text" name="EMAIL" value="dude@my.car">
  <h4>MPD port:</h4>
  <input type="text" name="MPDPORT" value="1027">
  <h4>MPD host:</h4>
  <input type="text" name="MPDHOST" value="localhost">
  <h4>MPD password:</h4>
  <input type="text" name="MPDPASS" value="user">
  <h4>Server:</h4>
  <input type="text" name="CONTROLSERVER" value="https://playnode.ca/control/">
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

  $m = new MongoClient("mongodb://webserver:webmunster@localhost/authority");
  $db = $m->authority;
  $c = $db->playnodeca;
//  $c->insert($authRow);
  $c->update(
    array( "MPDPORT" => $_GET['MPDPORT'] ),
    $authRow,
    array( "upsert" => true )
  );

  var_dump($c->findOne());
//  var_dump($authRow);
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
