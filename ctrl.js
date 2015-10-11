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
      childProcess.exec(mpc, function(err, data, stderr){
//        res.setHeader("Content-Type", "text/html")
//        res.setHeader("Content-Length", data.length)
        res.write(data)
        console.log(data)
      })
    break;
    case 'up':
      mpc += ' volume +5'
      childProcess.exec(mpc, function(err,stdout,stderr){
        res.write('ok')
      })
    break;
    case 'dn':
      mpc += ' volume -5'
      childProcess.exec(mpc, function(err,stdout,stderr){
        res.write('ok')
      })
    break;
    case 'fw':
      mpc += ' next'
      childProcess.exec(mpc, function(err,stdout,stderr){
        res.write('ok')
      })
    break;
    default:
      res.write('default')
  }
  res.end()
}).listen(8000, "0.0.0.0")
