<?php
checkRequestMethod('post');

checkRequestParams('userName', 'passWord', 'mail');

$userName = $_REQUEST['userName'];
$passWord = $_REQUEST['passWord'];
$mail = $_REQUEST['mail'];

$result = query("SELECT `USERNAME` FROM `USER` WHERE `USERNAME` = '${userName}'");

if (!$result->status) {
  $RESPONSE->msg = 'Abnormal Data!';

  throwRequestResponse();
}

if ($result->num > 0) {
  $RESPONSE->msg = 'UserName already exists!';

  throwRequestResponse();
}

$uid = uniqtag($userName);

$time = time();

$result = query("INSERT INTO `USER`(`UID`, `USERNAME`, `PASSWORD`, `MAIL`, `LASTLOGINTIME`, `REGTIME`)
  VALUES ('${uid}','${userName}','${passWord}','${mail}','${time}','${time}')");

if (!$result->status) {
  $RESPONSE->msg = 'Register Fail, please try later!';

  throwRequestResponse();
}

$RESPONSE->code = 'success';
$RESPONSE->msg = 'Register Success!';

throwRequestResponse();
?>