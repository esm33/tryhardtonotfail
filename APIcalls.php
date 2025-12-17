//file for the API calls
#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('communication.inc');

def getPages(drink){
	$results = shell_exec('GET www.thecocktaildb.com/api/json/v1/1/search.php?s={drink}');
	$arrayCode = json_decode($results);
	var_dump($arrayCode);
}


?>
