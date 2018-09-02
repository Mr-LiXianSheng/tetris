<?php
/**
 * Router Form
 */

$REQUEST_PATH = explode('/',$_SERVER['PATH_INFO']);

// Splice useless params
array_splice($REQUEST_PATH, 0, 1);

@$MODULE = $REQUEST_PATH[0];

@$COMMAND = $REQUEST_PATH[1];

@$PARAMS = array_slice($REQUEST_PATH, 2);



session_start();



const ROUTE_FILE_ROOT = FILE_ROOT . 'api/';


// MODULE ROUTER
switch ($MODULE) {

  case 'registration': require(ROUTE_FILE_ROOT . 'registration/index.php');
    break;

  case 'login'       : require(ROUTE_FILE_ROOT . 'login/index.php');
    break;

  case 'websocket'   : require(ROUTE_FILE_ROOT . 'websocket/index.php');
    break;

  default: require(FILE_ROOT . 'error/requestPathError.php');
    break;

}
?>