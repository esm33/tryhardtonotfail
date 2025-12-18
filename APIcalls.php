
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
//should show a random drink 
	$results = shell_exec('GET www.thecocktaildb.com/api/json/v1/1/random.php');
	$arrayCode = json_decode($results);
	var_dump($arrayCode);
	*/
	$link = 'https://www.thecocktaildb.com/api/json/v1/1/random.php';
	return getAPI($link);
}

function getPages($drinkname){
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




?>
