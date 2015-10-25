// CTRL.JS 0.1 Copyright 2015 @neilscudder
// Licenced under the GNU GPL <http://www.gnu.org/licenses/>
var https = require('https')
  , fs = require('fs')
  , childProcess = require('child_process')
  , url = require('url')
  , MongoClient = require('mongodb').MongoClient
  , assert = require('assert')
  , options = {
    key: fs.readFileSync('/etc/ssl/private/playnode.key'),
    cert: fs.readFileSync('/etc/ssl/certs/playnode.pem')
  }
https.createServer(options, start).listen(8000, "0.0.0.0")
function start(req, res) {
  var mongourl = 'mongodb://webserver:webmunster@localhost/authority'
  var url_parts = url.parse(req.url, true)
  var query = url_parts.query
  MongoClient.connect(mongourl, function(err, db) {
    assert.equal(null, err)
    authenticate(db, query['k'] , function() {
        db.close()
    })
  })
  var authenticate = function(db, key, callback) {
     var cursor =db.collection('playnodeca').find( { "KPASS": key } )
     cursor.each(function(err, doc) {
        assert.equal(err, null)
        if (doc != null) {
           console.dir(doc)
           proceed(req, res)
        } else {
           callback()
        }
     })
  }
}
function proceed(req, res) {
  var url_parts = url.parse(req.url, true)
  var query = url_parts.query
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
      childProcess.exec(cmd,theEnd)
    break;
    case 'up':
      mpc += ' volume +5'
      res.setHeader("Content-Type","text/html") 
      var cmd = 'sh/volume.sh ' + '"' + mpc + '"'
       childProcess.exec(cmd,theEnd)
    break;
    case 'dn':
      mpc += ' volume -5'
      res.setHeader("Content-Type","text/html") 
      var cmd = 'sh/volume.sh ' + '"' + mpc + '"'
      childProcess.exec(cmd,theEnd)
    break;
    case 'fw':
      mpc += ' next'
      childProcess.exec(mpc,theEnd)
    break;
    default:
      result = 'default'
      theEnd()
  }
  function theEnd(err,stdout,stderr){
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
