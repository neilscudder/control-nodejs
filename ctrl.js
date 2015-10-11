var https = require('https')
  , fs = require('fs')
  , childProcess = require('child_process')
  , url = require('url')

function mpdStatus() {
  childProcess.exec('/usr/bin/mpc -p 1027 -h user@localhost', 
    function(error, stdout, stderr) 
    {
      console.log(stdout)
      return stdout
    }
  )
}
function volumeUp(mpc) {
  childProcess.exec(mpc + 'volume +5', 
    function(error, stdout, stderr) 
    {
      console.log(stdout)
    }
  )
}

var options = {
  key: fs.readFileSync('/etc/ssl/private/playnode.key'),
  cert: fs.readFileSync('/etc/ssl/certs/playnode.pem')
}

https.createServer(options, function (req, res) {
  var url_parts = url.parse(req.url, true)
  var query = url_parts.query
  var mpc // executable with params
  // TODO authenticate
  mpc = '/usr/bin/mpc -h ' + query['p'] + '@' + query['h'] + ' -p ' + query['m'] + ' '
  switch(query['a']) {
    case 'up':
      volumeUp(mpc)
      res.writeHead(200)
      res.write('ok')
      res.end()
    break;
    default:
      res.writeHead(200)
      res.write('default')
      res.end()
    break;
  }
}).listen(8000, "0.0.0.0")
