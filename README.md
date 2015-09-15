# control
Web based client interface for the [music player daemon](https://github.com/MaxKellermann/MPD). Depends on php and [mpc](http://git.musicpd.org/cgit/master/mpc.git/). This is a Proof-of-Concept application, for the demonstration of a high speed mobile web interface for use on old phones and congested networks. Minimal file sizes, minimal http requests and zero graphics make a GUI <10kb  possible.

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

###Authentication Ideas:
- Unique hash and user email must also be used to authenticate the temporary link URL.
- A new URL generator php page will produce a temporary control URL together with a reset link for that control, which immediately generates the two links again.
- Each URL is associated with a real user by email address.
- The control URL will be shortened and obfuscated.
- This provides an easy way to distribute control to many staff. Poor security improved by fast key revocation and reissue, bolstered with server-side monitoring for abuse.

A bartender can be given control over the background music, and she may share it with a friend, who may share it again. This situation may be acceptable, but the moment it isn't the bartender can click the second link, revoking access for the first one, and start again with a clean slate.

A manager may want her floor staff to have access to the volume control for the restaurant, but she has a high-turnover rate. Rather than making one control per employee, she may make one generic control with volume priveleges only, and re-issue it every month. The staff can share it with each other by text, email, dm or whatever.

Imagine plugging in a headless music player to the firewalled LAN, sending one waitress one link to control the music and then watching it go viral to every server and staff member in the establishment. That is the purpose of this client. Immediate control, without a login process, centrally managed.
