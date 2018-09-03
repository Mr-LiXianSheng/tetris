<?php
Class LogicWS extends WebSocket {
  public $msg;

  public $leaderBoardData;

  public function onMessage ($socket) {
    $this->msg = new StdClass();

    $this->msg->status = true;
    $this->msg->type   = $messageType;
    $this->msg->data   = new StdClass();

    $activeType = $socket['activeType'];

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
    $message    = $socket['message'];

    $messageType = $message['type'];

    switch ($messageType) {
      case 'online': $this->dealOnline($socket);
        exit;
        break;
    }
  }

  public function dealOnline ($socket) {
    $uid   = $message['uid'];
    $token = $message['token'];

    $userInfo = query("SELECT * FROM `USER` WHERE `UID` = '${uid}'");

    if (!$userInfo->status || $userInfo->num === 0) {
      $this->disconnect($socket, false);

      return;
    }

    $userInfo = $userInfo->result[0];

    if ($token !== hash('md5', $userInfo['UID'] . $userInfo['PASSWORD'] . $userInfo['REGTIME'])) {
      $this->disconnect($socket, false);

      return;
    }

    $userName = $userInfo['USERNAME'];
    $msg->data->userName = $userName;
    $msg->data->userNum  = count($this->sockets) - 1;
    $msg->data->leaderBoard = $this->getLeaderBoardData();

    $this->sendMessage($msg, array_column($this->sockets, 'resource'));
  }

  public function getLeaderBoardData ($status = false) {
    if ($this->leaderBoardData && $status === false) return $this->leaderBoardData;

    $result = query("SELECT * FROM `LEADER_BOARD` ORDER BY `SCORE` DESC LIMIT 50");

    if (!$result->status || $result->num === 0) return [];

    $num = $result->num;

    foreach ($result->result as $index => $record) {
      $uid = $record['UID'];
      $name = query("SELECT `USERNAME` FROM `USER` WHERE `UID` = '${uid}'");

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