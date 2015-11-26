// CTRL.JS 0.4.0 Copyright 2015 @neilscudder
// Licenced under the GNU GPL <http://www.gnu.org/licenses/>

require('dotenv').load()
var https = require('https')
  , fs = require('fs')
  , childProcess = require('child_process')
  , url = require('url')
  , MongoClient = require('mongodb').MongoClient
  , assert = require('assert')
  , jade = require('jade')
  , path = require('path')
  , uuid = require('node-uuid')
  , querystring = require('querystring')

// TODO - Move these settings out...somewhere
var options = {
      key: fs.readFileSync(process.env.SSL_KEY),
      cert: fs.readFileSync(process.env.SSL_CRT),
    }
  , serverListenPort = process.env.PORT
  , mongourl = process.env.MONGOURL

console.log('Starting https server...')
https.createServer(options, function(req,res){
  if (process.setuid && (process.getuid() == 0)) {
    console.log('Current uid: ' + process.getuid());
    try {
      process.setuid(1000);
      console.log('New uid: ' + process.getuid());
    }
    catch (err) {
      console.log('Failed to set uid: ' + err);
    }
  }
  console.log('Server responding on port ' + serverListenPort)
  var url_parts = url.parse(req.url, true)
  var query = url_parts.query

  function authenticate(cmd) {
    MongoClient.connect(mongourl, function(err, db) {
      assert.equal(null, err)
      lookupKey(db, query['k'], function() {
          db.close()
      })
    }) 
    var lookupKey = function(db, key, callback) {
       var cursor =db.collection('playnodeca').find( { 'control': key } )
       cursor.each(function(err, doc) {
          assert.equal(err, null)
          if (doc != null) {
               console.log('Access Granted')
               childProcess.exec(cmd,returnData)
          } else {
               console.log('Access Denied ' + key )
               res.end('Access Denied','utf8')
          }
       })
    }
  }

  function parsePost(callback) {
    var data = ''
    req.on('data', function(chunk) {
      data += chunk
    })
    req.on('end', function() {
      var obj = querystring.parse(data)
      var readable = JSON.stringify(obj, null, 4)
      console.log('Post data parsed ' + readable)
      callback(obj)
    })
  }

  function authorize(data) {
    var controlURL
      , resetURL
      , rkey
      , ckey
    // TODO validate data here
    controlURL = data.CONTROLSERVER
    controlURL += '?p='
    controlURL += data.MPDPASS
    controlURL += '&h='
    controlURL += data.MPDHOST
    controlURL += '&m='
    controlURL += data.MPDPORT
    controlURL += '&l='
    controlURL += data.LABEL
    controlURL += '&k='
    resetURL = controlURL
    rkey = uuid.v4()
    ckey = uuid.v4()
    resetURL += rkey
    controlURL += ckey
    // CHEAT: setting oldRKey:
    var oldRKey = rkey
    MongoClient.connect(mongourl, function(err, db) {
      assert.equal(null, err)
      upsertKeys(db,function() {
          db.close()
      })
    }) 
    var upsertKeys = function(db,callback) {
       var collection = db.collection('playnodeca')
       collection.update({reset:rkey},{reset:rkey,control:ckey},{upsert:true},function upsertCB(err) {
         assert.equal(null, err)
       })
    }   
    authority(controlURL,resetURL)
  }

  function authority(controlURL,resetURL){
    console.log('Authority: ' + controlURL) 
    fs.readFile('authority.jade', 'utf8', function (err,data) {
      if (err) {
        return console.log(err)
      }
      console.log('Rendering the authority')
      var fn = jade.compile(data, {
        filename: path.join(__dirname, 'authority.jade'),
        pretty:   true
      })
      var htmlOutput = fn({
        url: {
          control: controlURL,
          reset: resetURL
        }
      })
      res.end(htmlOutput,'utf8')
    })
  }

  function showControlGUI() {
    fs.readFile('index.jade', 'utf8', function (err,data) {
      if (err) {
        return console.log(err)
      }
      console.log('Showing the Control GUI')
      var fn = jade.compile(data, {
        filename: path.join(__dirname, 'index.jade'),
        pretty:   true
      })
      var htmlOutput = fn({
        control: {
          mpdport: query['m'],
          mpdhost: query['h'],
          mpdpass: query['p'],
          label: query['l'],
          key: query['k']
        }
      })
      res.end(htmlOutput,'utf8')
    })
  }

  function processCommand() {
    var mpc = '/usr/bin/mpc'
    mpc += ' -h ' + query['p']
    mpc += '@' + query['h']
    mpc += ' -p ' + query['m']
    mpc += ' '
    res.statusCode = 200
    res.setHeader("Access-Control-Allow-Origin", "*")
    switch(query['a']) {
      case 'info':
	res.setHeader("Content-Type","text/html")
	var cmd = 'sh/mpdStatus.sh ' + '"' + mpc + '"'
	childProcess.exec(cmd,returnData)
      break;
      case 'up':
	mpc += ' volume +5'
	res.setHeader("Content-Type","text/html")
	var cmd = 'sh/cmd.sh ' + '"' + mpc + '"'
        authenticate(cmd)
      break;
      case 'dn':
	mpc += ' volume -5'
	res.setHeader("Content-Type","text/html")
	var cmd = 'sh/cmd.sh ' + '"' + mpc + '"'
	authenticate(cmd)
      break;
      case 'fw':
	mpc += ' next'
	var cmd = 'sh/cmd.sh ' + '"' + mpc + '"'
	authenticate(cmd)
      break;
      case 'random':
	mpc += ' random'
	var cmd = 'sh/cmd.sh ' + '"' + mpc + '"'
	authenticate(cmd)
      break;
      default:
	result = 'No match case'
	returnData()
    }
  }
  function returnData(err,stdout,stderr){
    if (err) {
      console.log(err)
    } else if (stdout) {
      res.end(stdout,'utf8')
    } else {
      res.end
    }
    if (stderr) console.log(stderr)
  } 
  
  if (query['a']) {
    console.log('Process command: ' + query['a'])
    processCommand()
  } else if (req.url == '/authority') {
    console.log('Authority Vanilla')
    authority()
  } else if (req.method == 'POST'){
    console.log('Authority data POST')
    parsePost(authorize)
  } else {
    console.log('Show GUI')
    showControlGUI()
  }
}).listen(serverListenPort, "0.0.0.0")

