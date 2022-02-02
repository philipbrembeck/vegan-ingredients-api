<?php
header('Content-Type: application/json; charset=utf-8');
$textinput = file_get_contents('php://input');
$param = $_GET['ingredients'];
if(empty($textinput) && empty($param)){
  http_response_code(400);
  echo '{"code":"bad_request","status":"400","message":"Missing parameter: text-input"}';
  die();
}
elseif(empty($param)){
  $param = $textinput;
}

include_once ('src/isvegan.php');
$response = explode(', ', $param);
$result = array_uintersect($isvegan, $response, fn($a, $b) => strcasecmp($a, $b));

if(empty($result)) {
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
