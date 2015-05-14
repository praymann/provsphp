# provsphp
PHP Scripts to provision Jitsi for onSip

# What am I?
Jitsi has the awesome functionality of provisioning, and this collection of php scripts allow a webserver to response with the properties to provision a client.
It leverages the onSip API to take in some onSip credentials and spit back the Jabber/Sip properties.

# Requirements
* php5
* php5-curl

# Install
* git clone [repo]
* Put Apache2/${webserver}/etc. in front

Example URI:

```
$ curl --data 'user=johndoe@example.onsip.com&pass=examplepassword' http://example.com/jitsi.php
```

# To-do List
* Add a lot more error handling
* Allow for onSip account aliases
* -Flesh out better functions for onSip API calls-
** Moved SessionID handling to a global set with a function
** Auto added to API Action if set
* -POST to destory onSip API SessionId-
