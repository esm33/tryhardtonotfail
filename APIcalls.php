<<<<<<< HEAD
//file for the API calls
=======
/file for the API calls
>>>>>>> e605c3d1eae4ddc2f9544da482c87f5b2f1e07c2
#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
<<<<<<< HEAD
require_once('communication.inc');

def getRandom{
//should show a random drink 
	$results = shell_exec('GET  www.thecocktaildb.com/api/json/v1/1/random.php');
=======
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
>>>>>>> e605c3d1eae4ddc2f9544da482c87f5b2f1e07c2
	$arrayCode = json_decode($results);
	var_dump($arrayCode);
}

<<<<<<< HEAD
def getPages(drink){
=======
function getPages($drink){
>>>>>>> e605c3d1eae4ddc2f9544da482c87f5b2f1e07c2
//searching for a specific drink by name
	$results = shell_exec('GET www.thecocktaildb.com/api/json/v1/1/search.php?s={drink}');
	$arrayCode = json_decode($results);
	var_dump($arrayCode);
	
}




?>
