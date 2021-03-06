<?php

$url = "https://sandbox.tristarexpress.com/v1/shipments";

$TOKEN = "2|8zuDuo94GQS81Lp9raj5RpHW572zviOw8Z7SlyFE";

$SHIPMENT = [
	"order_number" => 1,
//	"from_title" => "Optional", // Used to save address for Backoffice
	"from_person_type" => 1, // 1 Individual | 2 Legal
//	"from_name" => "Jhon Doe", // Required for from_person_type 1
	"from_responsible" => "Jhon Doe",
	"from_document_type" => 1, // TAX_ID = 1, PASSPORT = 2
	"from_document" => "000.000.000-00",
	"from_trading_name" => "Tristar Express",
	"from_country_id" => 236,
//	"from_country_a2" => "US", // Required if not send country_id or a3
//	"from_country_a3" => "USA", // Required if not send country_id or a2
	"from_postcode" => "13334-170",
	"from_state_id" => 20,
//	"from_state_code" => "FL, // Required if not send state_id
	"from_address_1" => "2100 NW 42nd Ave",
	"from_address_2" => "", // Neighborhood...
//	"from_address_complement" => "", // Complement
	"from_address_number" => "", // Can be empty
	"from_city" => "Miami",
	"from_phone" => "(11) 9 9999-0000",
	"from_email" => "exemple@tristarexpress.com",
//	"to_title" => "Jhon Doe House",
	"to_person_type" => 1, // 1 Individual | 2 Legal
	"to_name" => "Jhon Doe", // Required for from_person_type 1
//	"to_responsible" => "Jhon Doe", // Required for to_person_type 2
	"to_document_type" => 1, // TAX_ID = 1, PASSPORT = 2
	"to_document" => "000.000.000-00",
//	"to_trading_name" => "Compnay name", // Required for person_type_2
	"to_country_id" => 32, // Required if not send a2 or a3
//	"to_country_a2" => "BR", // Required if not send country_id or a3
//	"to_country_a3" => "BRL", // Required if not send country_id or a2
	"to_postcode" => "",
	"to_state_id" => 1,
//	"to_state_code" => "SP", // Required if not send state_id
	"to_address_1" => "Av. Pres. Juscelino Kubitschek",
	"to_address_2" => "SL 2 - Vila Olimpia", // Neighborhood...
//	"to_address_complement" => "", // Complement
	"to_address_number" => "2041",
	"to_city" => "São Paulo",
	"to_phone" => "(11) 9 9999-0000",
	"to_email" => "john.doe@tristarexpress.com",

	"package_width" => 25,
	"package_length" => 20,
	"package_height" => 15,
	"package_weight" => 1, // use "." (dot) for fractional value

	"total_freight" => 0, // charged and declared
	"with_insurance" => true, // Or false
	"total_tax" => 0,
	"total" => 0, // declared

	"items" => [
		[
			"shipment_item_type" => 40, // 10 for Products, 20 for Books, 30 for Medicine, 40 for CBD,  90 for other immune
			"hscode" => "151590801", // required for CDB
			"description" => "CBD Oil - Tristar", // Same title approved with Anvisa
			"quantity" => 1,
			"unity_price" => 100, // Price without separator ($ 10,00 = 1000)
//			"currency_id" => 1,// Optional, 1 - USD - 18 - BRL, Dollar is DEFAULT
//			"expiration_date" => "2021-12-12", // Otional for Medine and CBD
//			"lot" => "123", // Lot
//			"immune_code" => 1, // Required for shipment_item_type 90 (Other immune) get the code list with tristar staff (Same BR SISCOMEX)
		]
	]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
	'Accept: application/json',
//	'Accept-Language: pt-BR,pt;',
	'Content-Type: application/json',
	"Authorization: Bearer {$TOKEN}"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS,
	json_encode($SHIPMENT)
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
	["tracking_code"]=> string(13)
}

*/

/*
 * JSON EXPECTED FOR VALIDATION EXCEPTIONS
{
    "message": "Os dados fornecidos eram inv\u00e1lidos.",
    "errors": {
        "from_postcode": [
            "O campo from postcode \u00e9 obrigat\u00f3rio."
        ]
    }
}
 */

if($jsonResponse->errors ?? false) {
	$errors = array_map(function($fieldErrors){
		return "<li>".implode(',', $fieldErrors)."</li>";
	}, get_object_vars($jsonResponse->errors));

	$html = [
		"<h1>Erro de integração: {$jsonResponse->message}</h1>",
		"<ul>",
		implode('', $errors),
		"</ul>"
	];

	echo implode('', $html);
} else if($jsonResponse->message ?? false) {
	// Trata apenas monstrando a mensagen de retorno "Os dados fornecidos eram inválidos."
	echo "<h1>{$jsonResponse->message}</h1>";
} else {
	echo "<h1>API Tristar retornou um erro desconhecido (código: {$httpCode}).</h1>";
}