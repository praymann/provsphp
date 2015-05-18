<?php

// Include functions for XML 
include 'xml.php';

$_URL = 'https://api.onsip.com/api';

$_SESSION = null;

function post_onsip_action($action,$data) {
	global $_SESSION;
	global $_URL;

	if ( isset ( $_SESSION ) ) {
		$action .= "&SessionId=$_SESSION";
	}
	$post = "Action=" . $action . '&' . $data;
	if ( isset($_POST['dump']) ) {
		print $post . "\n";
	}
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $_URL);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$response = curl_exec($ch);
	curl_close($ch);
	return xml_to_array($response);
};

function set_onsip_session($xml) {
	global $_SESSION;
	$_SESSION = $xml['Context']['Session']['SessionId'];
	if ( isset($_POST['dump']) ) {
                print "SessionId:" . $_SESSION . "\n";
        }
};

?>
