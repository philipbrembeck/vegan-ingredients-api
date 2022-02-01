<img width="80px" src="https://raw.githubusercontent.com/JokeNetwork/vegancheck.me/main/img/hero_icon.png" align="right" alt="VeganCheck Logo">

# VeganCheck.me Ingredients API
[![Deploy to IONOS](https://images.ionos.space/deploy-now-icons/deploy-to-ionos-btn.svg)](https://ionos.space/setup?repo=https://github.com/JokeNetwork/vegan-ingredients-api)

## Introduction
The VeganCheck.me Ingredients API is a fork of [is-vegan](https://github.com/hmontazeri/is-vegan) with some more languages for recognition added and for my own convenience converted into PHP.

## How to use
### JSON End-Point
The APIs base path is `https://ingredients.vegancheck.me/ingredients` and gives out a JSON response. (Thanks to IONOS Deploy Now!) 

### Parameters
The following parameters are available as of now:
| parameter | usage                                            | method                |
|-----------|--------------------------------------------------|-----------------------|
|text       | transmit the ingredients list                    | GET (as text)         |
|ingredients| transmit the ingredients list (url-encoded)      | GET (as URL parameter)|

Sample request:
  ````bash
  curl -X GET \
  'https://ingredients.vegancheck.me/ingredients' \
  --header 'Content-Type: text/plain' \
  -d 'glucose syrup (from wheat or corn), sugar, gelatin, dextrose (from wheat or corn), contains less than 2% of: citric acid, atrificial flavors, natural flavors, palm oil, palm kernel oil, carnabua wax, beeswax, yellow 5, red 40, blue 1.'
  ````
  or 
  ````bash
  curl -X GET \
  'https://ingredients.vegancheck.me/ingredients?ingredients=glucose%20syrup%20(from%20wheat%20or%20corn)%2C%20sugar%2C%20gelatin%2C%20dextrose%20(from%20wheat%20or%20corn)%2C%20contains%20less%20than%202%25%20of%3A%20citric%20acid%2C%20atrificial%20flavors%2C%20natural%20flavors%2C%20palm%20oil%2C%20palm%20kernel%20oil%2C%20carnabua%20wax%2C%20beeswax%2C%20yellow%205%2C%20red%2040%2C%20blue%201' \
  ````
The header `"text/plain"` as well as the plain-text input has to be sent with every request, otherwise an error will be thrown.

**Attention**: The ingredients have to be comma seperated!

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

curl_setopt($ch, CURLOPT_URL, 'https://ingredients.vegancheck.me/ingredients');
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

echo 'Are Haribo Gummibears vegan? '.$vegan;
````
or if you want to use the brilliant library [Requests for PHP]:
````php
include('vendor/rmccue/requests/library/Requests.php');
Requests::register_autoloader();
$headers = array(
    'Content-Type' => 'text/plain'
);
$data = 'glucose syrup (from wheat or corn), sugar, gelatin, dextrose (from wheat or corn), contains less than 2% of: citric acid, atrificial flavors, natural flavors, palm oil, palm kernel oil, carnabua wax, beeswax, yellow 5, red 40, blue 1.';
$result = Requests::get('https://ingredients.vegancheck.me', $headers, $data);
$data = json_decode($result);
$vegan = $data->data->vegan;

echo 'Are Haribo Gummibears vegan? '.$vegan;
````

### Python
````py
import requests

reqUrl = "https://ingredients.vegancheck.me"

headersList = {
 "Content-Type": "text/plain" 
}

payload = "glucose syrup (from wheat or corn), sugar, gelatin, dextrose (from wheat or corn), contains less than 2% of: citric acid, atrificial flavors, natural flavors, palm oil, palm kernel oil, carnabua wax, beeswax, yellow 5, red 40, blue 1."

response = requests.request("GET", reqUrl, data=payload,  headers=headersList)
vegan = response.json()['data']['vegan']

print("Are Haribo Gummibears vegan? {}".format(vegan))
````
