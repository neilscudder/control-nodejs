var https = require('https')
  , fs = require('fs')
  , childProcess = require('child_process')
  , url = require('url')
var options = {
  key: fs.readFileSync('/etc/ssl/private/playnode.key'),
  cert: fs.readFileSync('/etc/ssl/certs/playnode.pem')
}

https.createServer(options, function (req, res) {
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
}).listen(8000, "0.0.0.0")
