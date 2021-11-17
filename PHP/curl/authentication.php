<?php

$url = "https://sandbox.tristarexpress.com/v1/authentication";

$TOKEN = "2|8zuDuo94GQS81Lp9raj5RpHW572zviOw8Z7SlyFE";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
	'Accept: application/json',
	"Authorization: Bearer {$TOKEN}"
]);
curl_setopt($ch, CURLOPT_POST, false);
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
	
// TODO: check http status 201
	
var_dump($jsonResponse);

/* EXPECT:

object(stdClass)#1 (1) {
	["message"]=> string(13) "Authenticated"
}

*/
