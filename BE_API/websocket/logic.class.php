<?php
Class LogicWS extends WebSocket {
  public function onMessage ($socket) {
    $activeType = $socket['activeType'];
    $activeTime = $socket['activeTime'];
    $message    = $socket['message'];

    switch ($activeType) {
      case 'online': ;
        break;

      case 'offline': $this->offLine($socket);
        break;

      case 'message': $this->dealUserMessage($socket);
        break;

      default       : $this->disconnect($socket['resource']);
        break;
    }
  }

  public function offLine ($socket) {
    $msg = new StdClass();

    $msg->status = true;
    $msg->type   = 'offline';
    $msg->data   = new StdClass();

    $msg->data->onlineNum = count($this->sockets) - 1;
    $msg->date->userName = $socket['data']->userName;

    $this->sendMessage($msg, array_column($this->sockets, 'resource'));
  }

  public function dealUserMessage ($socket) {
    $activeType = $socket['activeType'];
    $activeTime = $socket['activeTime'];
    $message    = $socket['message'];

    $messageType = $message['type'];

    $msg = new StdClass();

    $msg->status = true;
    $msg->type   = $messageType;
    $msg->data   = new StdClass();

    switch ($messageType) {
      case 'online':
        $userName = $message['userName'];
        $msg->data->userName = $userName;
        $msg->data->userNum = count($this->sockets) - 1;

        $this->sendMessage($msg, array_column($this->sockets, 'resource'));
        exit;
        break;
    }
  }
}
?>