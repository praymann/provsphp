<?php

// Include functions for onSip
include 'onsip.php';

// First check POST for correct data
if ( $_POST['user']===NULL or $_POST['pass']===NULL ) {
	header('X-PHP-Response-Code: 400', true, 400);
	exit("HTTP 400 - BAD REQUEST\nAre you passing {user} and {pass}?");
}

// Pull Session with onSip API
$_DATA = 'Username=' . urlencode($_POST['user']) . '&Password=' . $_POST['pass'];
$_XML = post_onsip_action('SessionCreate',$_DATA);
set_onsip_session($_XML);

// Pull this User's information from onSip API
$_DATA = 'UserAddress=' . urlencode($_POST['user']);
$_XML = post_onsip_action("UserRead",$_DATA);

// If the request has dump set to anything, dump the user information request from onSip API
if ( isset ( $_POST['dump'] ) ) {
	var_dump($_XML);

	$_DATA = "UserId=" . $_XML['Result']['UserRead']['User']['UserId'];
	$_DUMP = post_onsip_action("UserAddressBrowse",$_DATA);
	var_dump($_DUMP);
}

// Account property identifiers MUST always start with the “acc” substring. It doesn’t matter what you put after the “acc” as long as it’s unique between the accounts and the same across the different properties for the same account. But, again, you do need to start with “acc".

// Built the identifier with the POST information
$_USERNAME = split ( '@', $_POST['user'] );
$_ACC = "provisioned-" . $_USERNAME[0];

// Built the properties array(s)
$_SIP = array(
	"net.java.sip.communicator.impl.protocol.sip.acc" . $_ACC => "acc" . $_ACC,
	"net.java.sip.communicator.impl.protocol.sip.acc" . $_ACC . ".ACCOUNT_UID" => "SIP\:" . $_POST['user'],
	"net.java.sip.communicator.impl.protocol.sip.acc" . $_ACC . ".AUTHORIZATION_NAME" => $_XML['Result']['UserRead']['User']['Username'],
	"net.java.sip.communicator.impl.protocol.sip.acc" . $_ACC . ".DEFAULT_ENCRYPTION" => "true",
	"net.java.sip.communicator.impl.protocol.sip.acc" . $_ACC . ".PASSWORD" => $_XML['Result']['UserRead']['User']['Password'],
	"net.java.sip.communicator.impl.protocol.sip.acc" . $_ACC . ".IS_PRESENCE_ENABLED" => "true",
	"net.java.sip.communicator.impl.protocol.sip.acc" . $_ACC . ".KEEP_ALIVE_INTERVAL" => "25",
	"net.java.sip.communicator.impl.protocol.sip.acc" . $_ACC . ".KEEP_ALIVE_METHOD" => "REGISTER",
	"net.java.sip.communicator.impl.protocol.sip.acc" . $_ACC . ".PREFERRED_TRANSPORT" => "UDP",
	"net.java.sip.communicator.impl.protocol.sip.acc" . $_ACC . ".PROTOCOL_NAME" => "SIP",
	"net.java.sip.communicator.impl.protocol.sip.acc" . $_ACC . ".PROXY_ADDRESS" => "sip.onsip.com",
	"net.java.sip.communicator.impl.protocol.sip.acc" . $_ACC . ".PROXY_AUTO_CONFIG" => "false",
	"net.java.sip.communicator.impl.protocol.sip.acc" . $_ACC . ".PROXY_PORT" => "5060",
	"net.java.sip.communicator.impl.protocol.sip.acc" . $_ACC . ".SERVER_ADDRESS" => $_XML['Result']['UserRead']['User']['Domain'],
	"net.java.sip.communicator.impl.protocol.sip.acc" . $_ACC . ".SERVER_PORT" => "5060",
	"net.java.sip.communicator.impl.protocol.sip.acc" . $_ACC . ".USER_ID" => $_POST['user'],
	"net.java.sip.communicator.impl.protocol.sip.acc" . $_ACC . ".DISPLAY_NAME" => $_XML['Result']['UserRead']['User']['Contact']['Name'],
	"net.java.sip.communicator.impl.protocol.sip.acc" . $_ACC . ".VOICEMAIL_ENABLED" => "true"
);
$_JABBER = array(
	"net.java.sip.communicator.impl.protocol.jabber.acc" . $_ACC => "acc" . $_ACC,
	"net.java.sip.communicator.impl.protocol.jabber.acc" . $_ACC . ".ACCOUNT_UID" => "Jabber\:" . $_POST['user'] . '@' . $_XML['Result']['UserRead']['User']['Domain'],
	"net.java.sip.communicator.impl.protocol.jabber.acc" . $_ACC . ".DEFAULT_ENCRYPTION" => "true",
	"net.java.sip.communicator.impl.protocol.jabber.acc" . $_ACC . ".PASSWORD" => $_POST['pass'],
	"net.java.sip.communicator.impl.protocol.jabber.acc" . $_ACC . ".AUTO_DISCOVER_STUN" => "false",
	"net.java.sip.communicator.impl.protocol.jabber.acc" . $_ACC . ".ICE_ENABLED" => "false",
	"net.java.sip.communicator.impl.protocol.jabber.acc" . $_ACC . ".SEND_KEEP_ALIVE" => "false",
	"net.java.sip.communicator.impl.protocol.jabber.acc" . $_ACC . ".SERVER_ADDRESS" => $_XML['Result']['UserRead']['User']['Domain'],
	"net.java.sip.communicator.impl.protocol.jabber.acc" . $_ACC . ".SERVER_PORT" => "5222",
	"net.java.sip.communicator.impl.protocol.jabber.acc" . $_ACC . ".USER_ID" => $_POST['user']
);

// You can use the special property value “${null}” when you’d like to remove (unset) all properties beginning with the specified prefix. Note that properties are processed in the order that the provisioning script returns them. It is therefore possible to use ${null} in the beginning of a provisioning file, have it remove a group of properties like a SIP account for example, and then feed a new SIP account in the same provisioning file.
// remove all properties recursively for this property name

// Spit out all the properties, but make sure to clear the value first
foreach ($_SIP as $key => $value) {

	echo "$key" . '=' . "\${null}\n";
	echo "$key" . '=' . "$value\n";
}
foreach ($_JABBER as $key => $value) {

	echo "$key" . '=' . "\${null}\n";
	echo "$key" . '=' . "$value\n";
}

echo "net.java.sip.communicator.plugin.provisioning.METHOD=\${null}\n";
echo "net.java.sip.communicator.plugin.provisioning.URL=\${null}\n";

// destory sessionid http://developer.onsip.com/admin-api/Authentication/
post_onsip_action('SessionDestroy', "Burn it with fire");

?>
