<?php
require('../config/index.php');
require('../enhance/function.php');

require('webSocket.class.php');
require('logic.class.php');

$ws = new LogicWS(WS_HOST, WS_PORT);
?>

