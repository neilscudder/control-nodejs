<?php 
// CONTROL 0.2.3 Copyright 2015 @neilscudder
// Licenced under the GNU GPL <http://www.gnu.org/licenses/>


if (!empty($_GET["r"])) { 
  resetKeys($_GET["r"]);
}
if (!empty($_GET["e"])) { 
  setKeys($_GET["e"]);
}
?>

<form 
  action="authority.php" 
  method="get" 
  accept-charset="UTF-8"
  enctype="application/x-www-form-urlencoded" 
  autocomplete="off" 
  novalidate>
  Playnode label:<br>
  <input type="text" name="l">
  <br>
  User email:<br>
  <input type="text" name="e" value="Not optional">
  <br>
  MPD port:<br>
  <input type="text" name="m" value="6600">
  <br>
  MPD host:<br>
  <input type="text" name="h" value="localhost">
  <br>
  MPD password:<br>
  <input type="text" name="p">
  <br>
  Server:<br>
  <input type="text" name="s">
  <br><br>
  <input type="submit" value="Submit">
</form>

<?php

function setKeys($userEmail){
 
  $controlURL = $_GET['SERVER'] . "control.php?m=" . $_GET['MPDPORT'];
  $authRow = array( "MPDPORT" => $_GET['MPDPORT'] );

  if (!empty($_GET['MPDPASS'])) {
    $controlURL .= "&p=" . $_GET['MPDPASS'];
    $authRow['MPDPASS'] = $_GET['MPDPASS'];
  }
  if (!empty($_GET['MPDHOST'])) {
    $controlURL .= "&m=" . $_GET['MPDHOST'];
    $authRow['MPDHOST'] = $_GET['MPDHOST'];
  }
  if (!empty($_GET['LABEL'])) {
    $controlURL .= "&l=" . $_GET['LABEL'];
    $authRow['LABEL'] = $_GET['LABEL'];
  }

  $authRow['KPASS'] = passGenerator();
  $authRow['RPASS'] = passGenerator();

  $controlURL .= "&k=" . $authRow['KPASS'];
  $resetURL = $_GET['SERVER'] . "authority.php?r=" . $authRow['RPASS'];

  $m = new MongoClient();
  $collection = $m->authority->keys;
  $collection->insert($authRow);

  printURL($controlURL);
  printURL($resetURL);
}

function passGenerator() {
  // http://stackoverflow.com/q/19017694/5045643 :
  $pass=substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 1).substr(md5(time()),1);
  // is it unique?
  return $pass;
}

function printURL($URL){
  echo "
    <p>
      <a href='{$URL}'>{$URL}</a>
    </p>
  ";
}

?>