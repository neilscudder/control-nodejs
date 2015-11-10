// CTRL.JS 0.4.0 Copyright 2015 @neilscudder
// Licenced under the GNU GPL <http://www.gnu.org/licenses/>

var https = require('https')
  , fs = require('fs')
  , childProcess = require('child_process')
  , url = require('url')
  , MongoClient = require('mongodb').MongoClient
  , assert = require('assert')
  , jade = require('jade')
  , path = require('path')
  , uuid = require('node-uuid')

var options = {
      key: fs.readFileSync('../.ssl/private/playnode.key'),
      cert: fs.readFileSync('../.ssl/playnode.pem')
    }
  , serverListenPort = 443

https.createServer(options, function(req,res){

  if (process.getuid && process.setuid && (process.getuid() == 0)) {
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
    var mongourl = 'mongodb://webserver:webmunster@localhost/authority'
    MongoClient.connect(mongourl, function(err, db) {
      assert.equal(null, err)
      lookupKey(db, query['k'] , function() {
          db.close()
      })
    }) 
    var lookupKey = function(db, key, callback) {
       var cursor =db.collection('playnodeca').find( { "KPASS": key } )
       cursor.each(function(err, doc) {
          assert.equal(err, null)
          if (doc != null) {
               childProcess.exec(cmd,returnData)
          } else {
               res.end("Access Denied",'utf8')
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
//      var qs = require('querystring');
//      var post = qs.parse(data);
      console.log(data);
//      console.log(post.LABEL)
      callback(data)
    })
  }

  function authority(data){
    controlURL = data + '&k=' + uuid.v4()
    resetURL = data + '&k=' + uuid.v4()
    console.log('Authority: ' + controlURL) 
    // Assemble the html and return it
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

  function showGUI() {
    // Assemble the html and return it
    fs.readFile('index.jade', 'utf8', function (err,data) {
      if (err) {
        return console.log(err)
      }
      console.log('Returning the GUI')
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
	var cmd = 'sh/volume.sh ' + '"' + mpc + '"'
        authenticate(cmd)
      break;
      case 'dn':
	mpc += ' volume -5'
	res.setHeader("Content-Type","text/html")
	var cmd = 'sh/volume.sh ' + '"' + mpc + '"'
	authenticate(cmd)
      break;
      case 'fw':
	mpc += ' next'
	authenticate(cmd)
      break;
      default:
	result = 'No match case'
	returnData()
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
  } 
  if (query['a']) {
    console.log('Process command: ' + query['a'])
    processCommand()
  } else if (req.url == '/authority') {
    console.log('Authority Vanilla')
    authority()
  } else if (req.method == 'POST'){
    console.log('Authority data POST')
    parsePost(authority)
  } else {
    console.log('Show GUI')
    showGUI()
  }
}).listen(serverListenPort, "0.0.0.0")

