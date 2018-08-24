<?php
const REGISTRATION_FILE_ROOT = FILE_ROOT . 'api/registration/';

switch ($COMMAND) {
  case 'register': require(REGISTRATION_FILE_ROOT . 'register.php');
    break;

  default: require(FILE_ROOT . 'error/requestPathError.php');
}
?>