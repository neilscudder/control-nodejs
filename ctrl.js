var https = require('https')
  , fs = require('fs')
  , childProcess = require('child_process')

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
  var statusSnapshot = String(mpdStatus())
  res.writeHead(200)
  res.write(statusSnapshot)
  res.end()
}).listen(8000, "0.0.0.0")
