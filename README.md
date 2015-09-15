# control
Web based client interface for the [music player daemon](https://github.com/MaxKellermann/MPD). Depends on php and [mpc](http://git.musicpd.org/cgit/master/mpc.git/). This is a Proof-of-Concept application, for the demonstration of a high speed mobile web interface for use on old phones and congested networks.

### Installation
Install in web server root with shell access to mpc. This can be on the server running mpd, or any server with network access to mpd. Ensure your web server has write permissions to the 'cache' subdirectory. SSL is recommended.

### Client Usage
index.php?p=[MPDPASSWORD]&h=[MPDHOST]&m=[MPDPORT]&l=[LABEL]

- p is optional
- h is optional and defaults to localhost
- m is optional and defaults to 6600
- l is optional, and displays on the interface to identify the music zone being controlled

### API Usage
GET commands<br>
control.php?a=[COMMAND]&p=[MPDPASSWORD]&h=[MPDHOST]&m=[MPDPORT]
- up - Volume Up 5
- dn - Volume Down 5
- fw - mpc next
- info - returns HTML formatted song info with youtube search link

POST commands<br>
a=[COMMAND]&b=[TARGET]&p=[MPDPASSWORD]&h=[MPDHOST]&m=[MPDPORT]
- play TARGET - Clears playlist, adds TARGET dir, shuffles, and plays

### Features

* no js frameworks
* maximum usability/readability 
* minimal http requests
* portability / compatibility first
* basic controls / advanced reliability

A simple lighweight mobile web interface for the music player daemon (http://musicpd.org). Intended for use in conjunction with the paradigm connector to provide control from mobile data network devices over LAN-based music players.

Intended for a multi-user environment, where controls with varying permissions may be granted and revoked by a separate web based control panel. This control is part of the project at www.playnode.ca providing a platform for DJs to serve background music in commercial establishments.
