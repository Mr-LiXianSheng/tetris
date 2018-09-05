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
    $userInfo = $socket['data']->userInfo;

    $allOthers = $this->sockets;

    $this->msg->type = 'offline';
    $this->msg->data->USERNAME = $userInfo['USERNAME'];
    $this->msg->data->onlineBoard = $this->getOnlineBoardData();

    $this->sendMessage($this->msg, array_column($allOthers, 'resource'));
  }

  public function dealUserMessage ($socket) {
    $this->msg->type = $messageType = $socket['message']['type'];

    switch ($messageType) {
      case 'connect': $this->dealConnect($socket);
        break;
      
      case 'chat'   : $this->dealChat($socket);
        break;

      case 'heart' : ;
        break;
    }
  }

  public function dealConnect ($socket) {
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

    $this->msg->data->onlineBoard = $this->getOnlineBoardData();
    $this->msg->data->leaderBoardHistory = $this->getLeaderBoardHistoryData();

    $this->sendMessage($this->msg, [$socket['resource']]);

    $allOthers = $this->sockets;
    unset($allOthers[(int)$socket['resource']]);

    $this->msg->type = 'online';
    $this->msg->data = new StdCLass();
    $this->msg->data->USERNAME = $userInfo['USERNAME'];
    $this->msg->data->onlineBoard = $this->getOnlineBoardData();

    $this->sendMessage($this->msg, array_column($allOthers, 'resource'));
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

  public function dealChat ($socket) {
    $message = $socket['message'];

    unset($message['type']);

    $message['time'] = date('Y-m-d H:i:s');

    $this->msg->data = $message;

    $this->sendMessage($this->msg, array_column($this->sockets, 'resource'));
  }
}
?>