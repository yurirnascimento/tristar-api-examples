<?php

$url = "https://sandbox.tristarexpress.com/v1/create-account";

$NAME = "Your Name";
$EMAIL = "your.email@tristarexpress.com";
$PASSWORD = "password";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
	'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS,
	http_build_query(["name" => $NAME, "email" => $EMAIL])
);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

print_r($response);

if($httpCode >= 200 && $httpCode < 300) {

}