<?php
// CONTROL 0.2.6 Copyright 2015 @neilscudder
// Licenced under the GNU GPL <http://www.gnu.org/licenses/>

setlocale(LC_CTYPE, "en_US.UTF-8"); // Fixes non ascii characters with escapeshellarg

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

$CACHEDIR="cache/";

function authenticate() {
  $db_ini = parse_ini_file("res/db.ini");
  $m = new MongoClient($db_ini['dbConnectionString']);
  $c = $m->$db_ini['db']->$db_ini['collection'];
  $k = $_GET["k"];
  $key = $c->findOne(array("KPASS" => "{$k}"));
  if (empty($key['KPASS'])) {
    echo "Access Denied";
    return false;
  } else {
    echo "ok";
    return true;
  }
}

function showInfo(){
  global $CACHEDIR, $MPC, $MPDPORT;
  $cacheFile = "$CACHEDIR/$MPDPORT.cache";
  $comparisonFile = "$CACHEDIR/$MPDPORT.comparison";
  if (file_exists($comparisonFile)) {
    $previousFname = shell_exec("cat $comparisonFile");
  } else {
    $previousFname = null;
  }
  $fnameQuery = $MPC . ' --format %file% | head -n1';
  $currentFname = shell_exec($fnameQuery);
  if ($currentFname != $previousFname){
    file_put_contents($comparisonFile, $currentFname); 
    $infoQuery = $MPC . ' --format "
      <div class="info-container">
        <h2>[[%title%]|[%file%]]</h2>
        <p><strong>Artist:</strong> [%artist%]</p>
        <p><strong>Album:</strong> [%album%]</p>
        <div class=\"animated button\" id=\"insertNextTwo\">
          Insert Next Two
        </div>
      </div>" | head -n9';
    $currentInfo=shell_exec($infoQuery);
    echo $currentInfo;
    file_put_contents($cacheFile, $currentInfo);
  } else {
    include($cacheFile);
  }
}

function insertNextTwo(){
  global $MPC;
  $shellCmd = "insertNextTwo.sh '$MPC'";
  $result = shell_exec($shellCmd);
  if ( $result == "0" ){
    shell_exec("$MPC next");
  } else {
     // Debug:
  }
}

function showBrowser(){
  global $CACHEDIR, $MPC, $MPDPORT;
  $output = shell_exec($MPC . ' ls | sort');
  $results = preg_split('/[\r\n]+/', $output, -1, PREG_SPLIT_NO_EMPTY);
  echo "<ul>";
  foreach ($results as &$result) {
    $resultEncoded=rawurlencode($result);
    echo "
      <li class='full'>
        $result
        <div class='playbtn'>
          <svg
            class='animated play' 
            id='$resultEncoded' 
            x='0px' 
            y='0px'
            viewBox='0 0 512 512' 
            enable-background='new 0 0 512 512' 
            xml:space='preserve'>
           <g>
            <path class='playPath' d='M508.6,148.8c0-45-33.1-81.2-74-81.2C379.2,65,322.7,64,265,64c-3,0-6,0-9,0s-6,0-9,0
              c-57.6,0-114.2,1-169.6,3.6C36.6,67.6,3.5,104,3.5,149C1,184.6-0.1,220.2,0,255.8C-0.1,291.4,1,327,3.4,362.7
              c0,45,33.1,81.5,73.9,81.5c58.2,2.7,117.9,3.898,178.6,3.8c60.8,0.2,120.3-1,178.6-3.8c40.9,0,74-36.5,74-81.5
              c2.4-35.7,3.5-71.3,3.4-107C512.1,220.1,511,184.5,508.6,148.8z M207,353.9V157.4l145,98.2L207,353.9z'/>
           </g>
          </svg>
        </div>
      </li>";
    }
  echo "</ul>";
}

if ($_POST) {
  if (!empty($_POST["a"])) {
    $POSTA=$_POST["a"];
  }
  if (!empty($_POST["b"])) {
    $POSTB=$_POST["b"];
  }
  if (!empty($_POST["m"])) {
    $MPDPORT=$_POST["m"];
  }
  if (!empty($_POST["h"])) {
    $MPDHOST=$_POST["h"];
  }
  if (!empty($_POST["p"])) {
    $MPDPASS=$_POST["p"];
  }
} else {
  if (!empty($_GET["a"])) {
    $GETA=$_GET["a"];
  }
  if (!empty($_GET["m"])) {
    $MPDPORT=$_GET["m"];
  }
  if (!empty($_GET['h'])) {
    $MPDHOST=$_GET['h'];
  }
  if (!empty($_GET["p"])) {
    $MPDPASS=$_GET["p"];
  }
}

$MPC = "/usr/bin/mpc";
if (isset($MPDHOST,$MPDPASS)) {
  $MPC .= " -h {$MPDPASS}@{$MPDHOST}"; 
} elseif (isset($MPDHOST)) {
  $MPC .= " -h $MPDHOST";
} elseif (isset($MPDPASS)) {
  $MPC .= " -h {$MPDPASS}@localhost";
}

if (isset($MPDPORT)) {
  $MPC .= " -p $MPDPORT";
}

if (isset($GETA)) {
  switch ($GETA) {
    case "dn":
      authenticate() or die("access denied");
      $output = shell_exec("$MPC volume -5");
    break;
    case "up":
      authenticate() or die("access denied");
      shell_exec("$MPC volume +5");
    break;
    case "fw":
      authenticate() or die("access denied");
      shell_exec("$MPC next");
    break;
    case "info":
      showInfo();
    break;
    case "insertNextTwo":
      authenticate() or die("access denied");
      insertNextTwo();
    break;

    case "browser":
      authenticate() or die("access denied");
      showBrowser();
    break;
    case "test":
      $testCmd=$MPC ;
      exec($testCmd,$output,$exitStatus);
      if($exitStatus != 0) {
        echo "error";
      } else {
        echo "success";
      }
    break;
  }
}

if (isset($POSTA)) {
  switch ($POSTA) {
  	case "play":
//  authenticate() or die("access denied");
  		$target=rawurldecode($POSTB);
  		$escaped=escapeshellarg($target);
  		shell_exec("$MPC clear; $MPC add " . $escaped . "; $MPC shuffle; $MPC play");
  	break;
  	case "test":
  		$testCmd=$MPC . $MPDPORT;
  		exec($testCmd,$output,$exitStatus);
  		if($exitStatus != 0) {
  			echo "error";
  		} else {
  			echo "success";
  		}
  	break;
  }
}



// A mess of code, some makes a youtube button, some makes an iframe:
//        $ytLink = "https://www.youtube.com/results?search_query=${encQuery}";
//        $queryQuery = $MPC . ' --format "[%artist%] [[%title%]|[%file%]]" | head -n1';
//        $searchParams = shell_exec($queryQuery);
//        $encQuery = rawurlencode($searchParams);
//        $ytLink = "https://www.youtube.com/embed?fs=0&controls=0&listType=search&list=${encQuery}";
//        $currentInfo .= "
//              <p>Preview:</p>
//              <iframe src=\"${ytLink}\" frameborder=\"0\"></iframe>
//            </div>";      
//        $currentInfo .= "
//            <div class='animated button'>
//              <a href='${ytLink}' target='_blank'>
//              Find On YouTube
//              </a>
//            </div>
//          </div>";

?>
