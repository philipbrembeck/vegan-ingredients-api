<img width="80px" src="https://raw.githubusercontent.com/JokeNetwork/vegancheck.me/main/img/hero_icon.png" align="right" alt="VeganCheck Logo">

# VeganCheck.me Ingredients API

## Introduction
The VeganCheck.me Ingredients API is a fork of [is-vegan](https://github.com/hmontazeri/is-vegan) with some more languages for recognition added and for my own convenience converted into PHP.

Please refer to [VeganCheck.me Ingredients API Documentation](https://jokenetwork.de/vegancheck-ingredients-api) for a full and up to date documentation. 

## Table of contents
- [Introduction](#introduction)
- [How to use](#how-to-use)
  - [JSON End-Point](#json-end-point)
  - [Parameters](#parameters)
  - [Repsponses](#responses)
    - [Positive response](#positive-response)
    - [Error responses](#error-responses)
- [Code examples](#code-examples)
  - [PHP](#php)
  - [Python](#python)
  - [Javascript](#javascript)

## How to use
### JSON End-Point
The APIs base path is `https://api.vegancheck.me/v0/ingredients` and gives out a JSON response.

You can find the [monitioring status page here](https://stats.uptimerobot.com/LY1gRuP5j6).

### Parameters
The following parameters are available as of now:
| parameter | usage                                            | method                |
|-----------|--------------------------------------------------|-----------------------|
|text       | transmit the ingredients list                    | GET (as text)         |
|ingredients| transmit the ingredients list (url-encoded)      | GET (as URL parameter)|

Sample request:
  ````bash
  curl -X GET \
  'https://api.vegancheck.me/v0/ingredients' \
  --header 'Content-Type: text/plain' \
  -d 'glucose syrup (from wheat or corn), sugar, gelatin, dextrose (from wheat or corn), contains less than 2% of: citric acid, atrificial flavors, natural flavors, palm oil, palm kernel oil, carnabua wax, beeswax, yellow 5, red 40, blue 1.'
  ````
  or 
  ````bash
  curl -X GET \
  'https://api.vegancheck.me/v0/ingredients?ingredients=glucose%20syrup%20(from%20wheat%20or%20corn)%2C%20sugar%2C%20gelatin%2C%20dextrose%20(from%20wheat%20or%20corn)%2C%20contains%20less%20than%202%25%20of%3A%20citric%20acid%2C%20atrificial%20flavors%2C%20natural%20flavors%2C%20palm%20oil%2C%20palm%20kernel%20oil%2C%20carnabua%20wax%2C%20beeswax%2C%20yellow%205%2C%20red%2040%2C%20blue%201' \
  ````
The header `"text/plain"` as well as the plain-text input has to be sent with every request, otherwise an error will be thrown.

**Attention**: The ingredients have to be comma and space seperated and URL-encoded if posted through the `ingredients`-parameter. Otherwise, the results will be erroneous.

### Responses
We use standardized HTTP status codes as responses. 
Depending on which language you want to use to implement the API in, you may have to disable error-handling or ignore errors.

#### Positive Response
A successful request will throw a result like this:
````json
{
  "code": "OK",
  "status": "200",
  "message": "Success",
  "data": {
    "vegan": "false",
    "flagged": [
      "beeswax",
      "gelatin"
    ]
  }
}
````
Please note:
* The field `data→flagged` will only be present if `vegan` is `false`

#### Error responses
The following error responses can be expected:

* `400` - Missing required parameter, please make sure you sent at least one of the parameters mentioned in [Parameters](#Parameters):
  ````json
  {
    "code": "bad_request",
    "status": "400",
    "message": "Missing parameter: text-input"
  }
  ````

## Code examples
### PHP
````php
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://api.vegancheck.me/v0/ingredients');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

curl_setopt($ch, CURLOPT_POSTFIELDS, "glucose syrup (from wheat or corn), sugar, gelatin, dextrose (from wheat or corn), contains less than 2% of: citric acid, atrificial flavors, natural flavors, palm oil, palm kernel oil, carnabua wax, beeswax, yellow 5, red 40, blue 1.");

$headers = array();
$headers[] = 'Content-Type: text/plain';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
curl_close($ch);

$data = json_decode($result);
$vegan = $data->data->vegan;

echo 'Are Gummibears vegan? '.$vegan;
````
or if you want to use the brilliant library [Requests for PHP](https://github.com/WordPress/Requests):
````php
include('vendor/rmccue/requests/library/Requests.php');
Requests::register_autoloader();
$headers = array(
    'Content-Type' => 'text/plain'
);
$data = 'glucose syrup (from wheat or corn), sugar, gelatin, dextrose (from wheat or corn), contains less than 2% of: citric acid, atrificial flavors, natural flavors, palm oil, palm kernel oil, carnabua wax, beeswax, yellow 5, red 40, blue 1.';
$result = Requests::get('https://api.vegancheck.me/v0/ingredients', $headers, $data);
$data = json_decode($result);
$vegan = $data->data->vegan;

echo 'Are Gummibears vegan? '.$vegan;
````

### Python
````py
import requests

reqUrl = "https://api.vegancheck.me/v0/ingredients"

headersList = {
 "Content-Type": "text/plain" 
}

payload = "glucose syrup (from wheat or corn), sugar, gelatin, dextrose (from wheat or corn), contains less than 2% of: citric acid, atrificial flavors, natural flavors, palm oil, palm kernel oil, carnabua wax, beeswax, yellow 5, red 40, blue 1."

response = requests.request("GET", reqUrl, data=payload,  headers=headersList)
vegan = response.json()['data']['vegan']

print("Are Gummibears vegan? {}".format(vegan))
````

### Javascript
````js
<script>
let headersList = {
 "Content-Type": "text/plain"
}

let bodyContent = "glucose syrup (from wheat or corn), sugar, gelatin, dextrose (from wheat or corn), contains less than 2% of: citric acid, atrificial flavors, natural flavors, palm oil, palm kernel oil, carnabua wax, beeswax, yellow 5, red 40, blue 1.";

fetch("https://api.vegancheck.me/v0/ingredients", { 
  method: "POST",
  body: bodyContent,
  headers: headersList
}).then(function(response) {
  return response.text();
}).then(function(data) {
  console.log(data);
  document.getElementById("result").innerHTML = "Are Gummibears vegan?" + obj.data.vegan;
})
</script>

<p id="result"></p>
````
