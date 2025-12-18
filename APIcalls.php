//file for the API calls
#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('communication.inc');

def getRandom{
//should show a random drink 
	$results = shell_exec('GET  www.thecocktaildb.com/api/json/v1/1/random.php');
	$arrayCode = json_decode($results);
	var_dump($arrayCode);
}

def getPages(drink){
//searching for a specific drink by name
	$results = shell_exec('GET www.thecocktaildb.com/api/json/v1/1/search.php?s={drink}');
	$arrayCode = json_decode($results);
	var_dump($arrayCode);
	
}

def getAlphabetically{
//should show a list of drinks starting with a
	$results = shell_exec('GET  wwww.thecocktaildb.com/api/json/v1/1/search.php?f=a');
	$arrayCode = json_decode($results);
	var_dump($arrayCode);
}




?>
