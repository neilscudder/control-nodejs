var https = require('https')
  , fs = require('fs')
  , childProcess = require('child_process')
  , url = require('url')

function mpdStatus(mpc) {
  childProcess.exec(mpc + '', 
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
  var mpc = '/usr/bin/mpc'  
  mpc += ' -h ' + query['p']
  mpc += '@' + query['h'] 
  mpc += ' -p ' + query['m'] 
  mpc += ' '
  switch(query['a']) {
    case 'info':
      mpdStatus(mpc)
      res.writeHead(200)
      res.write('info')
      res.end()
    break;
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
