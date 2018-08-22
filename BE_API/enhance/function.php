<?php
/**
 * Throw Request Data & exit
 * 
 * @global $DATA
 * 
 * @return JSONSTRING
 */
function throw_request_data () {
	Global $data;
	Global $db;

	// Close database
	$db->close();

	// Exit & output data
	exit(json_encode($data));
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
function get_date_dif_seconds () {
	$paramNum = func_num_args();

	if($paramNum === 0)return 0;

	$time = func_get_arg(0);

	$now  = $paramNum > 1 ? func_get_arg(1) : date('Y-m-d H:i:s');

	return floor((strtotime($now)-strtotime($time)));
}

/**
 * Get random number
 * 
 * @param int n The number's length you want
 * 
 * @return int
 */
function random_num ($n) {
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
 * @return string Length === 32
 */
function uniqtag ($str) {

	return hash('md4', $str . uniqid());

}

/**
 * Get user IP address
 * 
 * @return string
 */
function getip() {
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
function tranday ($date) {

	return substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, 6, 2);

}//tranday

/**
 * Splice time
 * 
 * @param time for example 191919
 * 
 * @return time for example 19:19:19
 */
function trantime ($time) {

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
function getjson ($url, $assoc = false) {

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
function setjson ($url, $data) {

	return file_put_contents($url, $data);

}
?>