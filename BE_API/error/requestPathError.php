<?php
$RESPONSE->msg = 'Request interface ' . $_SERVER['PATH_INFO'] . ' does not exist!';

throwRequestResponse();
?>