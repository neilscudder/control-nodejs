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
      var format = ' --format '
      format += ' "<div class=\\"info-container\\">'
      format += '   <h2>[[%title%]|[%file%]]</h2>'
      format += '   <p><strong>Artist:</strong> [%artist%]</p>'
      format += '   <p><strong>Album:</strong> [%album%]</p>'
      format += '   <div class=\\"animated button released\\" id=\\"insertNextTwo\\">'
      format += '     Insert Next Two'
      format += '   </div>'
      format += ' </div>" | head -n1'
      mpc += format
      res.setHeader("Content-Type", "text/html")
      childProcess.exec(mpc, function(err,stdout,stderr){
        result = stdout
      })
    break;
    case 'up':
      mpc += ' volume +5'
      childProcess.exec(mpc, function(err,stdout,stderr){
        result = 'Volume Up'
      })
    break;
    case 'dn':
      mpc += ' volume -5'
      childProcess.exec(mpc, function(err,stdout,stderr){
        result = 'Volume Down'
      })
    break;
    case 'fw':
      mpc += ' next'
      childProcess.exec(mpc, function(err,stdout,stderr){
        result = 'Next Track'
      })
    break;
    default:
        result = 'default'
  }
  console.log(result)
  res.end(result,'utf8')
}).listen(8000, "0.0.0.0")
