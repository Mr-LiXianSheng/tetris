<?php
Class LogicWS extends WebSocket {
  public $msg;

  public $leaderBoardData;

  public function onMessage ($socket) {
    $this->msg = new StdClass();

    $this->msg->status = true;
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
    $this->msg->type = $messageType = $socket['message']['type'];

    switch ($messageType) {
      case 'online': $this->dealOnline($socket);
        exit;
        break;
    }
  }

  public function dealOnline ($socket) {
    $message = $socket['message'];
    $uid   = $message['uid'];
    $token = $message['token'];

    $userInfo = $this->getUserInfoByUid($uid);

    $trueToken = $this->getUserTokenByUid($uid);

    if (!$userInfo || !$trueToken || $token !== $trueToken) {
      $this->disconnect($socket, false);

      return;
    }

    unset($userInfo['PASSWORD']);

    $socket['data']->userInfo = $userInfo;
    $socket['data']->status = 'online';

    $this->msg->data->userName = $userInfo['USERNAME'];
    $this->msg->data->userNum  = count($this->sockets) - 1;
    $this->msg->data->onlineBoard = $this->getOnlineBoardData();
    $this->msg->data->leaderBoardHistory = $this->getLeaderBoardHistoryData();

    $this->sendMessage($this->msg, array_column($this->sockets, 'resource'));
    $this->getUserTokenByUid($userInfo['UID']);
  }

  public function getUserTokenByUid ($uid = false) {
    if (!$uid) return false;

    $result = query("SELECT `PASSWORD`,`REGTIME` FROM `USER` WHERE `UID` = '${uid}'");
    
    if (!$result->status || $result->num === 0) return false;
    
    $result = $result->result[0];

    return hash('md5', "${uid}${result['PASSWORD']}${result['REGTIME']}");
  }

  public function getUserInfoByUid ($uid = false) {
    if (!$uid) return false;

    $result = query("SELECT * FROM `USER` WHERE `UID` = '${uid}'");

    if (!$result->status || $result->num === 0) return false;

    return $result->result[0];
  }

  public function getOnlineBoardData () {
    $onlineBoard = [];

    $sockets = array_column($this->sockets, 'resource');

    foreach ($sockets as $socket) {
      if ($socket === $this->master) continue;

      $socketData = $this->sockets[(int)$socket]['data'];
      $userInfo = $socketData->userInfo;

      $user = new StdClass();

      $user->status   = $socketData->status;
      $user->UID      = $userInfo['UID'];
      $user->USERNAME = $userInfo['USERNAME'];

      array_push($onlineBoard, $user);
    }

    return $onlineBoard;
  }

  public function getLeaderBoardHistoryData ($status = false) {
    if ($this->leaderBoardData && $status === false) return $this->leaderBoardData;

    $result = query("SELECT * FROM `LEADER_BOARD` ORDER BY CAST(`SCORE` as int) DESC LIMIT 50");

    if (!$result->status || $result->num === 0) return [];

    $num = $result->num;

    foreach ($result->result as $index => $record) {
      $uid = $record['UID'];
      $name = query("SELECT `USERNAME` FROM `USER` WHERE `UID` = '${uid}'");

      if (!$name->status || $name->num === 0) {
        $result->result[$index]['name'] = 'error';
        continue;
      }

      $result->result[$index]['USERNAME'] = $name->result[0]['USERNAME'];
    }

    $this->leaderBoardData = $result->result;

    return $result->result;
  }
}
?>