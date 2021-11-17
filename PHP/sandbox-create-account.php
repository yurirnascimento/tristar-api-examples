<?php

$url = "https://sandbox.tristarexpress.com/v1/create-account";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_BODY, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$head = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if($httpCode >= 200 && $httpCode < 300) {
	
	
	
}