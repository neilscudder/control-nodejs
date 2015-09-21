# control
A remote control for music systems in a commercial setting where physical access to the audio equipment is often obstructed. Intended for use in conjunction with the [paradigm connector](https://github.com/neilscudder/paradigm) to provide control from mobile data network devices over LAN-based music players. A high speed mobile web interface for use in commercial background music, compatible with old phones and congested networks.

This is a web based client interface for the [music player daemon](https://github.com/MaxKellermann/MPD), dependant on on php, mongodb and [mpc](http://git.musicpd.org/cgit/master/mpc.git/).

A minimum-security authentication framework provides an means of quickly sharing passwordd-less login. An end user is provided a URL with embedded credentials granting them access to certain controls. For each Control URL there is a matching Reset URL, which will re-generate both links. See notes on authentication below.

GUI download size: 3.74kb<br>
Requests: 2<br>
Loading time: 0.8ms

### Status
This is a Proof-of-Concept application. Alpha stage. Still flushing out the broad strokes of key features. Contributors welcomed.

### Installation
Install in web server root with shell access to mpc. This can be on the server running mpd, or any server with network access to mpd. Ensure your web server has write permissions to the 'cache' subdirectory. SSL is recommended.

### Client Usage
index.php?p=[MPDPASSWORD]&h=[MPDHOST]&m=[MPDPORT]&l=[LABEL]

- p is optional, requires host to be set also
- h is optional and defaults to localhost
- m is optional and defaults to 6600
- l is optional, and displays on the interface to identify the music zone being controlled
 
Missing parameters will fall back to the defaults in your mpc instance (MPD environment vars), with one exception: if you set 'p=VALUE' but not 'h' then 'localhost' will be substituted in the API, before the mpc call.

### Control API Usage
GET<br>
?a=[COMMAND]
- up - Volume Up 5
- dn - Volume Down 5
- fw - mpc next
- info - returns HTML formatted song info with youtube search link

POST<br>
a=[COMMAND], b=[TARGET]
- play TARGET - Clears playlist, adds TARGET dir, shuffles, and plays

COMMON PARAMETERS<br>
p=[MPDPASSWORD]<br>
h=[MPDHOST]<br>
m=[MPDPORT]<br>

### Features

* no js frameworks
* maximum usability/readability 
* minimal http requests
* portability / compatibility first
* basic controls / advanced reliability
* anticipates a broken network
* network status/error status indicators
* password-less access control
* temporary, revokable authentication

A simple lighweight mobile web interface for the music player daemon (http://musicpd.org). Intended for use in conjunction with the [paradigm connector](https://github.com/neilscudder/paradigm) to provide control from mobile data network devices over LAN-based music players.

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
