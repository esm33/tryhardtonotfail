<?php
$results = shell_exec('GET www.thecocktaildb.com/api/json/v1/1/search.php?s=margarita');
$arrayCode = json_decode($results);
var_dump($arrayCode);
?>
