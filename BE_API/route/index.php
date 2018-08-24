<?php
/**
 * Router Form
 */

$requestPath = explode('/',$_SERVER['PATH_INFO']);

// Splice useless params
array_splice($requestPath, 0, 1);

@$module = $requestPath[0];

@$function = $requestPath[1];

@$params = array_slice($requestPath, 2);



session_start();



switch ($module) {

}


?>