<?php
if (!$COMMAND || $COMMAND !== 'start') {
  $RESPONSE->msg = 'No Command!';

  throwRequestResponse();
}

require(FILE_ROOT . 'websocket/index.php');
?>