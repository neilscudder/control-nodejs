// CONTROL 0.1.5 Copyright 2015 @neilscudder
// Licenced under the GNU GPL <http://www.gnu.org/licenses/>
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
