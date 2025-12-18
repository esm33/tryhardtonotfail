/file for the API calls
#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
//require_once('communication.inc');

//All from Google Search
$curl_session = curl_init();
curl_setopt($curl_session, CURLOPT_URL, 'https://www.thecocktaildb.com/api/json/v1/1/random.php');

curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);

$response_json = curl_exec($curl_session);

if (curl_errno($curl_session)) {
    echo 'cURL Error: ' . curl_error($curl_session);
}

$data = json_decode($response_json, true);

// Use the data
if ($data !== null) {
    echo "Data fetched successfully:\n";
    print_r($data);
} else {
    echo "Failed to decode JSON or retrieve data.\n";
}


function getRandom() {
//should show a random drink 
	$results = shell_exec('GET www.thecocktaildb.com/api/json/v1/1/random.php');
	$arrayCode = json_decode($results);
	var_dump($arrayCode);
}

function getPages($drink){
//searching for a specific drink by name
	$results = shell_exec('GET www.thecocktaildb.com/api/json/v1/1/search.php?s={drink}');
	$arrayCode = json_decode($results);
	var_dump($arrayCode);
	
}




?>
