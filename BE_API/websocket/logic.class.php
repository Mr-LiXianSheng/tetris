<?php
Class LogicWS extends WebSocket {
  public $leaderBoardData;

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
        $msg->data->userNum  = count($this->sockets) - 1;
        $msg->data->leaderBoard = $this->getLeaderBoardData();

        $this->sendMessage($msg, array_column($this->sockets, 'resource'));
        exit;
        break;
    }
  }

  public function getLeaderBoardData ($status = false) {
    if ($this->leaderBoardData && $status === false) return $this->leaderBoardData;

    $result = query("SELECT * FROM `LEADER_BOARD` ORDER BY `SCORE` DESC LIMIT 50");

    if (!$result->status || $result->num === 0) return [];

    $num = $result->num;

    foreach ($result->result as $index => $record) {
      $uid = $record['UID'];
      $name = query("SELECT `USERNAME` FROM `USER` WHERE `UID` = '${uid}'");

      print_r("SELECT `USERNAME` FROM `USER` WHERE `UID` = '${uid}'");

      if (!$name->status || $name->num === 0) {
        $result->result[$index]['name'] = 'error';
        continue;
      }

      $result->result[$index]['name'] = $name->result[0]['USERNAME'];
    }

    $this->leaderBoardData = $result->result;

    return $result->result;
  }
}
?>