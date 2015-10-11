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
  res.statusCode = 200
  res.setHeader("Access-Control-Allow-Origin", "*")
  switch(query['a']) {
    case 'info':
      var format = ' --format \
       "<div class=\\"info-container\\">\
          <h2>[[%title%]|[%file%]]</h2>\
          <p><strong>Artist:</strong> [%artist%]</p>\
          <p><strong>Album:</strong> [%album%]</p>\
          <div class=\\"animated button released\\" id=\\"insertNextTwo\\">\
            Insert Next Two\
          </div>\
        </div>" | head -n1'
      mpc += format
      childProcess.exec(mpc, callback)
      function callback(err, data){
        res.write(data)
        console.log(data)
      }
    break;
    case 'up':
      mpc += ' volume +5'
      childProcess.exec(mpc, callback)
      function callback(err, data){
        res.write('ok')
      }
    break;
    case 'dn':
      mpc += ' volume -5'
      childProcess.exec(mpc, callback)
      function callback(err, data){
        res.write('ok')
      }
    break;
    default:
      res.write('default')
    break;
  }
  res.end()
}).listen(8000, "0.0.0.0")
