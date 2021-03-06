This repo has been abandoned in favour of a new version written in go. 😀

# control
A remote control for music systems where physical access to the audio equipment is obstructed. Intended for use in conjunction with the [paradigm connector](https://github.com/neilscudder/paradigm) to provide control from mobile data network devices over LAN-based music players. A high speed mobile web interface for use in the background music industry, compatible with old phones and congested networks.

NOTE: Conversion to nodejs in progress. Documentation is innaccurate.

'Control' is a client interface for the [music player daemon](https://github.com/MaxKellermann/MPD), dependant on on php, mongodb and [mpc](http://git.musicpd.org/cgit/master/mpc.git/). Uses [Solarized Palette](https://github.com/altercation/solarized).

An authentication framework provisions password-less login URLs. An end user is provided a 'Control URL' with embedded credentials granting them access to certain controls. For each 'Control URL' there is a matching 'Reset URL', which will re-generate both links. See notes on authentication below.

GUI download size: 3.74kb<br>
Requests: 2<br>
Loading time: 0.8ms

### Status
This is a Proof-of-Concept application. Alpha stage. In process of conversion from php to nodejs. Contributors welcomed.

### Installation
Install in web server root with shell access to mpc. This can be any server with network access to mpd. Ensure your web server has write permissions to the 'cache' subdirectory. SSL is secommended. Create a file called db.ini with your db credentials in this format:

<pre>
dbConnectionString = "mongodb://USER:PASSWORD@HOST/DATABASE"
db = DATABASE
collection = COLLECTION
</pre>

### Client Usage
index.php?p=[MPDPASSWORD]&h=[MPDHOST]&m=[MPDPORT]&l=[LABEL]&k=[KPASS]

- p is optional, requires host to be set also
- h is optional and defaults to localhost
- m is optional and defaults to 6600
- l is optional, and displays on the interface to identify the music zone being controlled
- k is required, and must be generated by authority.php
 
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
k=[KPASS]<br>

### Features

* fast and light=
* easy to read, hard to screw up
* limited control for continuous loop environments
* easily managed security
* portability / compatibility first
* anticipates a broken network
* anticipates link-sharing
* gracefully degrades obsolete links to read-only

### Goals
* DDOS stress managed at control server
* user activity is logged
* server auto-revokes abused accounts

Intended for a multi-user environment, where controls with varying permissions may be granted and revoked by a separate web based control panel. This control is part of the project at [www.playnode.ca](https://playnode.ca) providing a platform for DJs to serve background music in commercial establishments.

###Authentication Ideas:
- The URL generator php page will produce a temporary control URL together with a reset link for that control, which immediately generates the two links again.
- Each URL is associated with a real user by email address.
- This provides an easy way to distribute control to many staff. Poor security improved by fast key revocation and reissue, bolstered with server-side monitoring for abuse.

A bartender can be given control over the background music, and she may share it with a friend, who may share it again. This situation may be acceptable, but the moment it isn't the bartender can click the second link, revoking access for the first one, and start again with a clean slate.

A manager may want her floor staff to have access to the volume control for the restaurant, but she has a high-turnover rate. Rather than making one control per employee, she may make one generic control with volume priveleges only, and re-issue it every month. The staff can share it with each other by text, email, dm or whatever.

Imagine plugging in a headless music player to the firewalled LAN, sending one waitress one link to control the music and then watching it go viral to every server and staff member in the establishment. That is the purpose of this client. Immediate control, without a login process, centrally managed.
