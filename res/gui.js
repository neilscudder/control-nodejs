var clickEventType = ((document.ontouchstart!==null)?'click':'touchstart')
var PreviousInfo
var APIURL = document.getElementsByClassName("APIURL")[0].id
var APIALT = document.getElementsByClassName("APIALT")[0].id
var MPDPORT = document.getElementsByClassName("MPDPORT")[0].id
var MPDHOST = document.getElementsByClassName("MPDHOST")[0].id
var MPDPASS = document.getElementsByClassName("MPDPASS")[0].id
var KPASS = document.getElementsByClassName("KPASS")[0].id
// TODO fallback to APIALT if necessary
params = APIURL 
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
        var div = document.getElementById(id)
        div.innerHTML = CurrentInfo
        PreviousInfo = CurrentInfo
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
initialise()
