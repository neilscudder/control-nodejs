var https = require('https')
  , fs = require('fs')
  , childProcess = require('child_process')
  , url = require('url')
var result
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
      var cmd = './mpdStatus.sh ' + '"' + mpc + '"'
      childProcess.exec(cmd, function(err,stdout,stderr){
        result = stdout
      })
    break;
    case 'up':
      mpc += ' volume +5'
      childProcess.exec(mpc)
    break;
    case 'dn':
      mpc += ' volume -5'
      childProcess.exec(mpc)
    break;
    case 'fw':
      mpc += ' next'
      childProcess.exec(mpc)
    break;
    default:
        result = 'default'
  }
  console.log(result)
  res.end(result,'utf8')
}).listen(8000, "0.0.0.0")
