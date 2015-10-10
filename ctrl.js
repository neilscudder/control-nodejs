var childProcess = require('child_process')
childProcess.exec('/usr/bin/mpc -p 1027 -h user@localhost', mpdCallback)

function mpdCallback(error, stdout, stderr) {
  console.log(stdout)
}

