<<<<<<< HEAD
//file for the API calls
#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('communication.inc');

def getRandom{
=======

<?php
//USED TO BE BEFORE <?php
//file for the API calls
//#!/usr/bin/php
//require_once('path.inc');
//require_once('get_host_info.inc');
//require_once('communication.inc');


function getAPI($link) 
{
//script found from google query: commands to get something from api using php → then turned into a function to be called by homecatalog.php
	$curl_session = curl_init(); //start curl session
	curl_setopt($curl_session, CURLOPT_URL, $link); //https://www.thecocktaildb.com/api/json/v1/1/random.php'
	curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);
	$response_json = curl_exec($curl_session);

	if (curl_errno($curl_session)) {
	    echo 'cURL Error: ' . curl_error($curl_session);
	    curl_close($curl_session);
	    return;
	}
	curl_close($curl_session);
	$data = json_decode($response_json, true);

	// Use the data
	if ($data !== null) {
	    echo "Data fetched successfully:\n";
	    //print_r($data);
	    return $data;
	} else {
	    echo "Failed to decode JSON or retrieve data.\n";
	    return;
	}
}

function getRandom() {
/*
>>>>>>> 8b76e5fc24d065f2e9358f90ad33065f541a193e
//should show a random drink 
	$results = shell_exec('GET  www.thecocktaildb.com/api/json/v1/1/random.php');
	$arrayCode = json_decode($results);
	var_dump($arrayCode);
	*/
	$link = 'https://www.thecocktaildb.com/api/json/v1/1/random.php';
	return getAPI($link);
}

<<<<<<< HEAD
def getPages(drink){
=======
function getPages($drinkname){
>>>>>>> 8b76e5fc24d065f2e9358f90ad33065f541a193e
//searching for a specific drink by name
/*
	$results = shell_exec('GET www.thecocktaildb.com/api/json/v1/1/search.php?s={drink}');
	$arrayCode = json_decode($results);
	var_dump($arrayCode);
	*/
	//https://www.thecocktaildb.com/api/json/v1/1/search.php?s={drink}   URL wrong, tested in search & returns null..... look at link below for proper url call
	//https://www.thecocktaildb.com/api/json/v1/1/search.php?s=drinkname
	//drinkname affects what drink is called
	
	$link = 'https://www.thecocktaildb.com/api/json/v1/1/search.php?s='.urlencode($drinkname); //PHP textbook section 4, chapter 22, pg 755
	return getAPI($link);
	
	
}

def getAlphabetically{
//should show a list of drinks starting with a
	$results = shell_exec('GET  wwww.thecocktaildb.com/api/json/v1/1/search.php?f=a');
	$arrayCode = json_decode($results);
	$data = var_dump($arrayCode);
	if (empty ($data) {
	echo { ["status"=>"Failure","message"=>"api data not found"]; }
	}
	else echo { ["status"=>"success","message"=>"data called successfully"]; }
}

def recSystem{
}




?>
