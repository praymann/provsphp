<?php

$_URL = 'https://api.onsip.com/api';

function post_onsip_action($action,$data) {

	global $_URL;

	$post = "Action=" . $action . '&' . $data;
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $_URL);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$response = curl_exec($ch);
	curl_close($ch);
	return $response;
};
?>
