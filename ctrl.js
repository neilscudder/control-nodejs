var https = require('https')
  , fs = require('fs')
  , childProcess = require('child_process')
  , url = require('url')

function mpdStatus() {
  childProcess.exec('/usr/bin/mpc -p 1027 -h user@localhost', mpdCallback)
  function mpdCallback(error, stdout, stderr) {
    console.log(stdout)
    return stdout
  }
}

var options = {
  key: fs.readFileSync('/etc/ssl/private/playnode.key'),
  cert: fs.readFileSync('/etc/ssl/certs/playnode.pem')
}

https.createServer(options, function (req, res) {
  var url_parts = url.parse(req.url, true);
  var query = url_parts.query
  switch(query['a']) {
    case 'up':
      res.writeHead(200)
      res.write('up')
      res.end()
    break;
    default:
      res.writeHead(200)
      res.write('default')
      res.end()
    break;
  }
}).listen(8000, "0.0.0.0")
