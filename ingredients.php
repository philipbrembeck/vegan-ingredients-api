<?php
header('Content-Type: application/json; charset=utf-8');
$json = file_get_contents('php://input');
if(empty($json)){
	http_response_code(400);
    echo '{"code":"bad_request","status":"400","message":"Missing parameter: text-input"}';
    die();
}
include_once ('src/isvegan.php');
$response = explode(', ', $json);
$result = array_uintersect($isvegan, $response, fn($a, $b) => strcasecmp($a, $b));
if(empty($result)){
	http_response_code(200);
   	echo '{"code":"OK","status":"200","message":"Success","data":{"vegan":"true"}}';
    die();
}
else {
	$flagged = '"' . implode ( '", "', $result ) . '"';
	http_response_code(200);
	echo '{"code":"OK","status":"200","message":"Success","data":{"vegan":"false", "flagged": ['.$flagged.']}}';
    die();
}
?>