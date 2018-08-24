<?php
$RESPONSE->msg = 'Request method ' . $_SERVER['REQUEST_METHOD'] . ' not allow!';

throwRequestResponse();
?>