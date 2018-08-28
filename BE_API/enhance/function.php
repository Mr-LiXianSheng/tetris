<?php
/**
 * Throw Request Data & exit
 * 
 * @global $DATA
 * 
 * @return JSONSTRING
 */
function throwRequestResponse () {
	Global $RESPONSE;
	Global $DB;

	// Close database
	$DB->close();

	// Exit & output data
	exit(json_encode($RESPONSE));
}

/**
 * check Request method get | post
 * 
 * @param String get | post
 */
function checkRequestMethod ($type = false) {
	GLOBAL $RESPONSE;

	if (!$type) return;

	if (strtolower($type) === strtolower($_SERVER['REQUEST_METHOD'])) return;

	require(FILE_ROOT . 'error/requestMethodError.php');
}

/**
 * check Request params is complete
 */
function checkRequestParams () {
	GLOBAL $RESPONSE;

	$length = func_num_args();

	$missingParam = false;

	for ($i = 0; $i < $length; $i++) {
		if (@!$_REQUEST[func_get_arg($i)]) {
			$missingParam = func_get_arg($i);

			break;
		}
	}

	if (!$missingParam) return;

	require(FILE_ROOT . 'error/requestParamsMiss.php');
}

/**
 * Query DBA data
 * 
 * @param  String $sql
 *         
 * 
 * @global Object $db
 * 
 * @return {status, num, result}
 * 				 {boolean, int, array}
 * 			   {query result status, result num, result}
 */
function query ($sql) {
	Global $DB;

	$result = $DB->query($sql);

	$data = new StdClass();

	// init return data attribute
	$data->status = false;
	$data->num = 0;
	$data->result = [];

	// no sql
	if (!$sql) return $data;

	$result = $DB->query($sql);

	// query fail. for example sql is wrong
	if (!$result) return $data;

	// query success
	$data->status = true;

	// set query result num
	@$num = $result->num_rows;
	$data->num = $num ? $num : 0;

	// if result num equal zero, return directly
	if ($num === 0) return $data;

	// push result to data->result, change result data type to Array
	for ($i = 0; $i < $num; $i++) {
		array_push($data->result, $result->fetch_assoc());
	}

	return $data;
}

/**
 * Get two times minus of seconds
 *
 * Return value > 0 IF the reduced time more than the now time
 * 
 * @param string time required Reduced time
 * 
 * @param string now non-required Current time. Default is call time
 * 
 * @return int minus of seconds
 */
function getDateDiffSeconds () {
	$paramNum = func_num_args();

	if($paramNum === 0)return 0;

	$time = func_get_arg(0);

	$now  = $paramNum > 1 ? func_get_arg(1) : date('Y-m-d H:i:s');

	return floor((strtotime($now)-strtotime($time)));
}

/**
 * Get random number
 * 
 * @param int   The number's length you want
 * 
 * @return int  result you want
 */
function randomNum ($n) {
    for ($num = '', $i = 0; $i < $n; $i++) {

				$num .= rand(0,9);

		}

    return (int)$num;
}

/**
 * Get unique tag 
 * 
 * @param string
 * 
 * @return string  Length === 32
 */
function uniqtag ($str) {

	return hash('md4', $str . uniqid());

}

/**
 * Get user IP address
 * 
 * @return string
 */
function getIp() {
	$ip = $_SERVER["REMOTE_ADDR"];

	if($ip=='::1')$ip = '127.0.0.1';

	return $ip;
}

/**
 * Splice date
 * 
 * @param string date for example 19990909
 * 
 * @return string date for example 1999-09-09
 */
function tranDay ($date) {

	return substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, 6, 2);

}//tranday

/**
 * Splice time
 * 
 * @param time for example 191919
 * 
 * @return time for example 19:19:19
 */
function tranTime ($time) {

	return substr($time, 0, 2) . ':' . substr($time, 2, 2) . ':' . substr($time, 4, 2);

}//trantime

/**
 * Get json file to object
 * 
 * @param string url JSON file url
 * 
 * @param boolean assoc Assoc === true return array | Assoc === false return object
 * 
 * @return object | array
 */
function getJson ($url, $assoc = false) {

	return json_decode(file_get_contents($url), $assoc);

}

/**
 * Write data into Json file
 * 
 * @param string url Json file url
 * 
 * @param string data
 * 
 * @return boolean
 */
function setJson ($url, $data) {

	return file_put_contents($url, $data);

}
?>