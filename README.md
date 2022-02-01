<img width="80px" src="https://raw.githubusercontent.com/JokeNetwork/vegancheck.me/main/img/hero_icon.png" align="right" alt="VeganCheck Logo">

# VeganCheck.me Ingredients API

## Introduction
The VeganCheck.me Ingredients API is a fork of [is-vegan](https://github.com/hmontazeri/is-vegan) with some more languages for recognition added and for my own convenience converted into PHP.

## How to use
### JSON End-Point
The APIs base path is `https://vegancheck.me/api/v0/ingredients` and gives out a JSON response. 

### Parameters
The following parameters are available as of now:
| parameter | usage                               | method              |
|-----------|-------------------------------------|---------------------|
|text       | transmit the ingredients list       | GET (as text)       |

Sample request:
  ````bash
  curl -X GET \
  'https://vegancheck.me/api/v0/ingredients' \
  --header 'Content-Type: text/plain' \
  -d 'glucose syrup (from wheat or corn), sugar, gelatin, dextrose (from wheat or corn), contains less than 2% of: citric acid, atrificial flavors, natural flavors, palm oil, palm kernel oil, carnabua wax, beeswax, yellow 5, red 40, blue 1.'
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

curl_setopt($ch, CURLOPT_URL, 'https://vegancheck.me/api/v0/ingredients');
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

### Python
````py
import requests

reqUrl = "https://vegancheck.me/api/v0/ingredients"

headersList = {
 "Content-Type": "text/plain" 
}

payload = "glucose syrup (from wheat or corn), sugar, gelatin, dextrose (from wheat or corn), contains less than 2% of: citric acid, atrificial flavors, natural flavors, palm oil, palm kernel oil, carnabua wax, beeswax, yellow 5, red 40, blue 1."

response = requests.request("GET", reqUrl, data=payload,  headers=headersList)
vegan = response.json()['data']['vegan']

print("Are Haribo Gummibears vegan? {}".format(vegan))
````
