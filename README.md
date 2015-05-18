# provsphp
PHP Scripts to provision Jitsi for onSip

# Purpose
This collection of php scripts leverages the onSip API to take in some onSip credentials and spit back the Jabber/Sip properties required for provisioning a Jitsi client.

# Requirements
* php5
* php5-curl

# Install
* git clone [repo]
* Put Apache2 in front
* ???

# Usage:

Jitsi Client:

Format and feed into Jitsi the URI like so:
```
https://example.com/jitsi.php?user=${username}&pass=${password}
```

Testing vi CLI:

```
$ curl -d 'user=johndoe@example.onsip.com&pass=examplepassword' http://example.com/jitsi.php
```

# To-do List
* Add a lot more error handling
* Handle API Timeouts due to bad password
* ~~Allow for onSip account aliases via Action UserAddressBrowse~~ (Added)
* ~~Flesh out better functions for onSip API calls~~ (Moved SessionID handling to a global varible, set with a function, & auto added to API Action if set )
* ~~POST to destroy onSip API SessionId~~
