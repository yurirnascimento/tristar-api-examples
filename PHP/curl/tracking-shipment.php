<?php

// Example: $trackingCode = "TR063602";
$trackingCode = "";

$SANDBOX_ENDPOINT = "https://sandbox.tristarexpress.com/v1/tracking/{$trackingCode}";
$PRODUCTION_ENDPOINT = "https://api.tristarexpress.com/v1/tracking/{$trackingCode}";
$isProduction = true;

// Example: $TOKEN = "1|JONxdCXOU9ysGAf254F61mY9FrhDkmTC8vHMtWrO";
$TOKEN = "";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $isProduction ? $PRODUCTION_ENDPOINT : $SANDBOX_ENDPOINT);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
	'Accept: application/json',
//	'Accept-Language: pt-BR,pt;',
	'Content-Type: application/json',
	"Authorization: Bearer {$TOKEN}"
]);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

//// Trata algum erro de requisicao
if(!($httpCode >= 200 && $httpCode < 300)) {
	// Exception
	http_response_code(500);
	echo "Request error: " . $response;
	exit;
}

try {

	$jsonResponse = json_decode($response, false, 512, JSON_THROW_ON_ERROR);

} catch (JsonException $e) {

	http_response_code(500);
	echo "Request reponse parse error: " . $response;
	exit;

}
/* EXPECT:

{
    "data": [
        {
            "id": 3,
            "tracking_event_id": 1,
            "code": "SC",
            "title_locale": {
                "en-US": "Shipment created",
                "pt-BR": "Remessa criada"
            },
            "short_description_locale": {
                "pt-BR": "Remessa criada mas ainda não movimentada"
            },
            "note": null,
            "country_id": null,
            "state_id": null,
            "event_at": "2022-03-14T19:16:43.000000Z",
            "created_at": "2022-03-14T19:16:43.000000Z"
        }
    ]
}

*/

// Checa se existe algum tracking
if(!empty($jsonResponse->data)) {
	$list = array_map(function($event) {
		$formatDate = date_format(date_create($event->event_at), 'd/m/Y H:m:i');
		return "<li>{$event->title_locale->{'pt-BR'}} at {$formatDate}</li>";
	}, $jsonResponse->data);

	$html = [
		"<h1>Tracking:</h1>",
		"<ul>",
		implode('', $list),
		"</ul>"
	];

	echo implode('', $html);
} else if($jsonResponse->message ?? false) {
	// Trata apenas mostrando a mensagen de retorno "Os dados fornecidos eram inválidos."
	echo "<h1>{$jsonResponse->message}</h1>";
} else {
	echo "<h1>API Tristar retornou um erro desconhecido (código: {$httpCode}).</h1>";
}