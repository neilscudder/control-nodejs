<?php
// CONTROL 0.1.2 Copyright 2015 @neilscudder
// Licenced under the GNU GPL <http://www.gnu.org/licenses/>

setlocale(LC_CTYPE, "en_US.UTF-8"); // Fixes non ascii characters with escapeshellarg

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $pri=$_POST["pri"];
  $sec=$_POST["sec"];
  $portAlias=$_POST["serverAlias"];
} else {
  $getA=$_GET["a"];
  $portAlias=$_GET["b"];
}
$cacheDir="cache/";

function url_get_param($name) {
  $url=$_SERVER['REQUEST_URI'];
  parse_str(parse_url($url, PHP_URL_QUERY), $vars);
  return isset($vars[$name]) ? $vars[$name] : null;
}

$MPC="/usr/bin/mpc -p " . $portAlias;

if (isset($getA)) {
  switch ($getA) {
    case "dn":
      shell_exec("$MPC volume -5");
    break;
    case "up":
      shell_exec("$MPC volume +5");
    break;
    case "fw":
      shell_exec("$MPC  next");
    break;
    case "info":
      $cacheFile = "$cacheDir/$portAlias.cache";
      $comparisonFile = "$cacheDir/$portAlias.comparison";

      if (file_exists($comparisonFile)) {
        $previousFname = shell_exec("cat $comparisonFile");
      } else {
        $previousFname = null;
      }

      $infoQuery = $MPC . ' --format "
	          <div class="info-container">
		  <h2>[[%title%]|[%file%]]</h2>
                  <p><strong>Artist:</strong> [%artist%]</p>
                  <p><strong>Album:</strong> [%album%]</p>" | head -n4';

      $fnameQuery = $MPC . ' --format %file% | head -n1';
      $currentFname = shell_exec($fnameQuery);

      if ($currentFname != $previousFname){
        file_put_contents($comparisonFile, $currentFname); 
        $queryQuery = $MPC . '  --format "[%artist%] [[%title%]|[%file%]]" | head -n1';
        $searchParams = shell_exec($queryQuery);
        $encQuery = rawurlencode($searchParams);
        $ytLink = "https://www.youtube.com/results?search_query=${encQuery}";
        $currentInfo=shell_exec($infoQuery);
        $currentInfo .= "<div class='animated button'><a href='${ytLink}' target='_blank'>Find On YouTube</a></div></div>";
        echo $currentInfo;
        file_put_contents($cacheFile, $currentInfo);
      } else {
        include($cacheFile);
      }
    break;
    case "browser":
      $output = shell_exec($MPC . ' ls | sort');
      $results = preg_split('/[\r\n]+/', $output, -1, PREG_SPLIT_NO_EMPTY);
      echo "<ul>";
      foreach ($results as &$result) {
        $resultEncoded=rawurlencode($result);
        echo "
          <li class=\"full\">
            $result
            <div class=\"playbtn\">
              <svg version=\"1.1\" class=\"animated play\" id=\"$resultEncoded\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" x=\"0px\" y=\"0px\"
               viewBox=\"0 0 512 512\" enable-background=\"new 0 0 512 512\" xml:space=\"preserve\">
               <g>
               <path class=\"playPath\" d=\"M508.6,148.8c0-45-33.1-81.2-74-81.2C379.2,65,322.7,64,265,64c-3,0-6,0-9,0s-6,0-9,0
                c-57.6,0-114.2,1-169.6,3.6C36.6,67.6,3.5,104,3.5,149C1,184.6-0.1,220.2,0,255.8C-0.1,291.4,1,327,3.4,362.7
                c0,45,33.1,81.5,73.9,81.5c58.2,2.7,117.9,3.898,178.6,3.8c60.8,0.2,120.3-1,178.6-3.8c40.9,0,74-36.5,74-81.5
                c2.4-35.7,3.5-71.3,3.4-107C512.1,220.1,511,184.5,508.6,148.8z M207,353.9V157.4l145,98.2L207,353.9z\"/>
               </g>
              </svg>
            </div>
          </li>";
        }
      echo "</ul>";
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

if (isset($pri)) {
  switch ($pri) {
	case "play":
		$target=rawurldecode($sec);
		$escaped=escapeshellarg($target);
		shell_exec("$MPC clear; $MPC add " . $escaped . "; $MPC shuffle; $MPC play");
		break;
	case "test":
		$testCmd=$MPC . $portAlias;
		exec($testCmd,$output,$exitStatus);
		if($exitStatus != 0) {
			echo "error";
		} else {
			echo "success";
		}

		break;
  }
}

?>
