var clickEventType = ((document.ontouchstart!==null)?'click':'touchstart')
var PreviousInfo
var API = document.getElementsByClassName("MPDPORT")[0].id
var MPDPORT = document.getElementsByClassName("MPDPORT")[0].id
var MPDHOST = document.getElementsByClassName("MPDHOST")[0].id
var MPDPASS = document.getElementsByClassName("MPDPASS")[0].id
var KPASS = document.getElementsByClassName("KPASS")[0].id

params = API 
  + "?MPDPORT=" + MPDPORT
  + "&MPDHOST=" + MPDHOST
  + "&MPDPASS=" + MPDPASS
  + "&KPASS=" + KPASS;

function getCmd(id){
  var x = document.getElementById(id)
  var xhr = new XMLHttpRequest()
  var localParams = params + "&a=" + id
  xhr.open("GET",localParams,true)
  xhr.send()
  xhr.onreadystatechange = function() {
    if (xhr.status == 200 && xhr.readyState == 4 && x.classList.contains("pushed")) {
      x.classList.add('released')
      x.classList.remove('pushed')
    } else if (xhr.readyState == 4 && x.classList.contains("pushed")) {
//      alert(xhr.responseText)
      x.classList.add('denied')
      x.classList.remove('pushed')
    } else {
      // Nothing
    }
  }
}

function autoRefresh(id) {
  var x = document.getElementById('info')
  x.classList.remove('opaque')
  x.classList.add('heartbeat')

  setTimeout(function(){ autoRefresh(id) },1500)
  var xhr = new XMLHttpRequest()
  var localParams = params + "&a=" + id
  xhr.open("GET",localParams,true)
  xhr.send()
  xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && xhr.status == 200) {
      var CurrentInfo = xhr.responseText;
      if (CurrentInfo !== PreviousInfo && !isEmpty(CurrentInfo)) {
        var infoWin = document.getElementById(id)
        infoWin.innerHTML = CurrentInfo
        window.PreviousInfo = CurrentInfo
        playListener()
        animatedButtonListener()
      } 
      x.classList.remove('heartbeat')
      x.classList.add('opaque')
    } 
  } 
} 
function isEmpty(str) {
    return (!str || 0 === str.length)
}
function initialise() {
  var id = document.getElementsByTagName('section')[0].id
  autoRefresh(id)
  animatedButtonListener()
}

//
// LISTENERS
//
function pushed(id){
    document.getElementById(id).classList.add('pushed')
    document.getElementById(id).classList.remove('released')
}
function animatedButtonListener() {
  var buttons = document.getElementsByClassName("animated")
  function pusher(e){
    var id = e.currentTarget.id
    var x = document.getElementById(id)
    if (x.classList.contains("released") && id.match(/tog/g)) {
      pushed(id)
      togBrowser(id)
    } else if (x.classList.contains("released")) {
      pushed(id)
      getCmd(id)
    }
  }
  for(i = 0; i<buttons.length; i++) {
      buttons[i].addEventListener(clickEventType, pusher, false)
  }
}
function playListener() {
  var playButton = document.getElementsByClassName("play")
  function otherPusher(e) {
    var nid = e.currentTarget.id
    var x = document.getElementById(nid)
    if (x.classList.contains("confirm")) {
      postCmd("play",nid)
      window.location.href = "index.php?MPDPORT=" + window.MPDPORT + "&LABEL=" + window.LABEL
    } else {
      x.classList.add('pushed')
      x.classList.remove('released')
    }
  }
  function confirmer(e) { 
    var id = e.currentTarget.id
    var x = document.getElementById(id)
    var shapes
    if (x.classList.contains("pushed")) {
        x.classList.add('confirm')
        shapes = x.getElementsByClassName("playPath")
        shapes[0].style.fill = "#eee8d5"
        x.classList.remove('pushed')
    } else if (x.classList.contains("confirm")) {
        setTimeout(function(){ buttonTimeout(id) },2200)
    } else {
        x.classList.add('released')
        shapes = x.getElementsByClassName("playPath")
        shapes[0].style.fill = "#93A1A1"
    }
  }
  function buttonTimeout(id) {
    document.getElementById(id).classList.remove("confirm")
    document.getElementById(id).classList.add('released')
  }
  for(var i=0; i<playButton.length; i++) {
      playButton[i].addEventListener(clickEventType, otherPusher, false)
      playButton[i].addEventListener("animationend", confirmer, false)
      playButton[i].addEventListener("webkitAnimationEnd", confirmer, false)
  }
}
initialise()
