<?php

$url = "https://sandbox.tristarexpress.com/v1/create-account";

$SHIPMENT = [

];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
	'Accept: application/json',
	'Content-Type: application/x-www-form-urlencoded;charset=UTF-8'
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS,
	http_build_query($SHIPMENT)
);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if(!($httpCode >= 200 && $httpCode < 300)) {
	// Exception
	http_response_code(500);
	echo $response;
	exit;
}
	
try {
	
	$jsonResponse = json_decode($response, false, 512, JSON_THROW_ON_ERROR);
	
} catch (JsonException $e) {
	
	http_response_code(500);
	echo $response;
	exit;
	
}
	
// TODO: save the bearer_token
	
var_dump($jsonResponse);

/* EXPECT:

object(stdClass)#1 (2) {
	["id"]=> int(0)
	["bearer_token"]=>
	string(42) "0|fhdwUpwW56BEqDVFV9LQKARhVvSrJobQCNzWBpZb"
}

*/
