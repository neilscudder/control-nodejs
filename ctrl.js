var https = require('https')
var fs = require('fs')
var childProcess = require('child_process')

function mpdStatus() {
  childProcess.exec('/usr/bin/mpc -p 1027 -h user@localhost', mpdCallback)
  function mpdCallback(error, stdout, stderr) {
    return stdout
  }
}

var options = {
  key: fs.readFileSync('/etc/ssl/private/playnode.key'),
  cert: fs.readFileSync('/etc/ssl/certs/playnode.pem')
}

https.createServer(options, function (req, res) {
  res.writeHead(200)
//  res.end(mpdStatus())
  res.end("yo")
}).listen(8000, "0.0.0.0")
