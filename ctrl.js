var https = require('https')
  , fs = require('fs')
  , childProcess = require('child_process')
  , url = require('url')

function mpdStatus(mpc) {
  var format
  format = '--format '
  format+= '"<div class=\\"info-container\\">'
  format+= '  <h2>[[%title%]|[%file%]]</h2>'
  format+= '  <p><strong>Artist:</strong> [%artist%]</p>'
  format+= '  <p><strong>Album:</strong> [%album%]</p>'
  format+= '  <div class=\\"animated button released\\" id=\\"insertNextTwo\\">'
  format+= '    Insert Next Two'
  format+= '  </div>'
  format+= '</div>" | head -n1'
  mpc += format
  childProcess.exec(mpc, 
    function(error, stdout, stderr) 
    {
      console.log(stdout)
      return format
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
      content = mpdStatus(mpc)
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
