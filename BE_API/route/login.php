<?php
const REGISTRATION_FILE_ROOT = FILE_ROOT . 'api/login/';

switch ($COMMAND) {
  case 'login': require(REGISTRATION_FILE_ROOT . 'login.php');
    break;

  default: require(FILE_ROOT . 'error/requestPathError.php');
}
?>