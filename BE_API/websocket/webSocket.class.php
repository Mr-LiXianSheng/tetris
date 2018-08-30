<?php
// report all error
error_reporting(E_ALL);
// ignore user abort
ignore_user_abort();
// set no time limit
set_time_limit(0);
// set time zone
date_default_timezone_set('Asia/shanghai');

// CLASS
class WebSocket {

	// Set MAX Concurrent Request
	const LISTEN_SOCKET_NUM = 99;

	// Init socket connect pond
	private $sockets = [];

	// Statement Main Socket
    private $master;

    private $LogicServe;

    public function register ($object) {
        $LogicServe = $object;
    }

    /**
     * @description  Constructer
     * @param String Listening Address
	   * @param String Listening Port
     */
    public function __construct($host, $port) {

        try {
        	// Instantiation Main Socket
            $this->master = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

            // Set IP and Port Reuse, when reLaunch Service can use it also
            socket_set_option($this->master, SOL_SOCKET, SO_REUSEADDR, 1);

            // Bind Address and Port to Main Socket
            socket_bind($this->master, $host, $port);

            /*
             * Listen function make socket from initiative to passive connect 
             * this socket could accpet other process' request, become an Service process
             * In TCP Service Programming Listen could make a socket become Service
             * make socket from initiative to passive connect, store some socket
 			*/
            socket_listen($this->master, self::LISTEN_SOCKET_NUM);

			//catch Error info
        } catch (\Exception $e) {

            $err_code = socket_last_error();
            $err_msg = socket_strerror($err_code);

        }

        // make Main Socket as first in connect pond
        $this->sockets[0] = ['resource' => $this->master];

        // debug
        // $pid = function_exists('posix_getpwuid') ? posix_getpid() : get_current_user();

        // $this->debug(["server: {$this->master} started,pid: {$pid}"]);

        // try get new connect request or data
        while (true) {

            try {

                $this->doServer();

            } catch (\Exception $e) {

            	$err_code = $e->getCode();
            	$err_msg = $e->getMessage();

            }

        }

    }


    /**
	 * get new connect request or data
     */
    private function doServer() {

    	// init param
        $write = $except = NULL;

        // get all socket resource
        $sockets = array_column($this->sockets, 'resource');

        /**
         * this function could delete unavailable sockets from origin array
         * 
         * @param Array Could Read sockets 
         * @param Array Could Write sockets 
         * @param Array except sockets 
         * @param Int   Timeout Null is choke
         * 
         * @return Int  Get available socket num in all sockets When Error return false
        */
        $read_num = socket_select($sockets, $write, $except, NULL);

        if (false === $read_num) {

        	$err_code = socket_last_error();
        	$err_info = socket_strerror($err_code);

            return;
        }

        // Traversing all available sockets
        foreach ($sockets as $socket) {

            /**
             * If active socket is Main socket
             * This indicate have connnet request from other socket
             * do hand shake
             */
            if ($socket == $this->master) {

                /**
                 * After create bind listening
                 * Accept fun will accept socket's connect request
                 * If connect success, it will return a new scoket resource for interactive
                 * If exist more than one sockets which wait connect, Only first will be deal
                 * If no connect request, process will be choked until be connected
				 * If use set_socket_blocking() or socket_set_noblock() set choke, it will return false
                 * After return resource, wait connect continue
                 */
                $client = socket_accept($this->master);

                // Error
                if (false === $client) {

                	$err_code = socket_last_error();
                	$err_info = socket_strerror($err_code);

                    continue;

                } else {

                	// connect socket
                    self::connect($client);

                    continue;

                }

            } else {

                /**
                 * IF active socket is not Main socket, read it's data and do some action
                 * 
                 * get data from socket
				 * @param  socket be read socket resource
                 * @param  buffer store data's variable
                 * @param  Int    data's length when read
                 * @param  Int    flag
                 * 
				 * @return Int    data's bits num
                 */
                $bytes = @socket_recv($socket, $buffer, 2048, 0);

                // if data's bit no more than 9, this indicate socket be disconnected
                if ($bytes < 9) {

                    $recv_msg = $this->disconnect($socket);

                } else {

                    // if do not handshake yet , do hand shake
                    if (!$this->sockets[(int)$socket]['handshake']) {

                        self::handShake($socket, $buffer);
                        continue;

                    } else {

                    	// analysis data
                        $recv_msg = self::parse($buffer);

                    }
                }

                $msg = self::dealMsg($socket, $recv_msg);

                $this->broadcast($msg);
            }
        }
    }

    /**
     * init socket resource
     * add socket to connect socket pond
     * handshake -> hand shake status
     * @param socket
     */
    public function connect($socket) {

    	// get remote socket's ip and port
        socket_getpeername($socket, $ip, $port);

        $socket_info = [
            'resource'  => $socket,
            'handshake' => false,
            'ip'        => $ip,
            'port'      => $port,
        ];
        $this->sockets[(int)$socket] = $socket_info;
    }

    /**
     * disconnect socket
     *
     * @param socket
     *
     * @return Array
     */
    private function disconnect($socket) {
    	$recv_msg = new stdClass();

    	$recv_msg->status = true;
    	$recv_msg->msg = 'someone logout';
    	$recv_msg->time = date('Y-m-d,H:i:s');

        unset($this->sockets[(int)$socket]);

        return $recv_msg;
    }

    /**
     * Do hand shake
     *
     * @param socket
     * @param buffer
     *
     * @return bool
     */
    public function handShake($socket, $buffer) {

        // get client upgrade key
        $line_with_key = substr($buffer, strpos($buffer, 'Sec-WebSocket-Key:') + 18);
        $key = trim(substr($line_with_key, 0, strpos($line_with_key, "\r\n")));

        /**
         * upgrade algorithm
         * create upgrade key and stitching websocket upgrade header
         */
        $upgrade_key = base64_encode(sha1($key . "258EAFA5-E914-47DA-95CA-C5AB0DC85B11", true));
        $upgrade_message = "HTTP/1.1 101 Switching Protocols\r\n";
        $upgrade_message .= "Upgrade: websocket\r\n";
        $upgrade_message .= "Sec-WebSocket-Version: 13\r\n";
        $upgrade_message .= "Connection: Upgrade\r\n";
        $upgrade_message .= "Sec-WebSocket-Accept:" . $upgrade_key . "\r\n\r\n";

        // write upgrade info to socket
        socket_write($socket, $upgrade_message, strlen($upgrade_message));

        // change hand shake status
        $this->sockets[(int)$socket]['handshake'] = true;

        // socket_getpeername($socket, $ip, $port);

        // send msg to client
        $msg = [
            'type' => 'handshake',
            'content' => 'done',
        ];

        $msg = $this->build(json_encode($msg));

        socket_write($socket, $msg, strlen($msg));

        return true;
    }

    /**
     * Analysis data
     *
     * @param buffer
     *
     * @return bool|string
     */
    private function parse($buffer) {
        $decoded = '';

        $len = ord($buffer[1]) & 127;
        if ($len === 126) {
            $masks = substr($buffer, 4, 4);
            $data = substr($buffer, 8);
        } else if ($len === 127) {
            $masks = substr($buffer, 10, 4);
            $data = substr($buffer, 14);
        } else {
            $masks = substr($buffer, 2, 4);
            $data = substr($buffer, 6);
        }
        for ($index = 0; $index < strlen($data); $index++) {
            $decoded .= $data[$index] ^ $masks[$index % 4];
        }

        return json_decode($decoded, true);
    }

    /**
     * assembly data to websocket data frame
     *
     * @param data
     *
     * @return string
     */
    private function build($msg) {

        $frame = [];
        $frame[0] = '81';
        $len = strlen($msg);

        if ($len < 126) {
            $frame[1] = $len < 16 ? '0' . dechex($len) : dechex($len);
        } else if ($len < 65025) {
            $s = dechex($len);
            $frame[1] = '7e' . str_repeat('0', 4 - strlen($s)) . $s;
        } else {
            $s = dechex($len);
            $frame[1] = '7f' . str_repeat('0', 16 - strlen($s)) . $s;
        }

        $data = '';
        $l = strlen($msg);

        for ($i = 0; $i < $l; $i++) {
            $data .= dechex(ord($msg{$i}));
        }

        $frame[2] = $data;

        $data = implode('', $frame);

        return pack("H*", $data);
    }

    /**
     * deal msg
     *
     * @param $socket
     * @param $recv_msg
     *
     * @return JSON string
     */
    private function dealMsg($socket, $recv_msg) {

    	if (!isset($recv_msg->status)) {
    		// 离线处理
    	}

    	return $this->build(json_encode($recv_msg));
    }

    /**
     * 广播消息
     *
     * @param $data
     */
    private function broadcast($data) {

        foreach ($this->sockets as $socket) {

            if ($socket['resource'] == $this->master) {

                continue;

            }

            socket_write($socket['resource'], $data, strlen($data));
        }
    }

}
?>