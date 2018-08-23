<?php
/**
 * Router Form
 */

$requestPath = explode('/',$_SERVER['PATH_INFO']);

// Splice useless params
array_splice($requestPath, 0, 1);

print_r($requestPath);


?>