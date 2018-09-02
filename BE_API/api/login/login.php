<?php
checkRequestMethod('post');

checkRequestParams('userName', 'passWord');

$userName = $_REQUEST['userName'];
$passWord = $_REQUEST['passWord'];

$result = query("SELECT * FROM `USER` WHERE `USERNAME` = '${userName}'");

if (!$result->status) {
  $RESPONSE->msg = 'Abnormal Data, Please try later!';

  throwRequestResponse();
}

if ($result->num === 0) {
  $RESPONSE->msg = 'This account is nog exist!';

  throwRequestResponse();
}

$md5PassWord = $result->result[0]['PASSWORD'];

$md5PassWord = hash('md5', $md5PassWord);

if ($passWord !== $md5PassWord) {
  $RESPONSE->msg = 'Your passWord is wrong!';

  throwRequestResponse();
}

$userInfo = $result->result[0];

$result->result[0]['TOKEN'] = hash('md5', $userInfo['UID'] . $userInfo['PASSWORD'] . $userInfo['REGTIME']);

$RESPONSE->code = 'success';
$RESPONSE->msg = 'Login Success!';
$RESPONSE->data = $result->result[0];
unset($RESPONSE->data['PASSWORD']);
unset($RESPONSE->data['REGTIME']);

$_SESSION['USER'] = $result->result[0];

throwRequestResponse();
?>