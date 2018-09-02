<?php
/**
 * Current environment
 * 
 * @param dev development
 * 
 * @param ole online
 * 
*/

const ENV = 'dev';



const DEV_INDEX_URL = 'http://localhost:5678';

const OLE_INDEX_URL = 'http://www.jiaminghi.com';

const SPA_INDEX_URL = ENV === 'dev' ? DEV_INDEX_URL : OLE_INDEX_URL;



const DBA_NAME = 'tetris';

const DBA_HOST = 'localhost';

const DEV_DBA_USER = 'root';

const OLE_DBA_USER = 'tetris';

const DEV_DBA_PASS = 'jiaming';

const DEV_OLE_PASS = 'jiamingTetris';

const DEV_WS_HOST = '192.168.10.150';

const OLE_WS_HOST = '192.168.10.150';

const DEV_WS_PORT = '5921';

const OLE_WS_PORT = '5921';

const DBA_USER = ENV === 'dev' ? DEV_DBA_USER : OLE_DBA_USER;

const DBA_PASS = ENV === 'dev' ? DEV_DBA_PASS : OLE_DBA_PASS;

const WS_HOST = ENV === 'dev' ? DEV_WS_HOST : OLE_WS_HOST;

const WS_PORT = ENV === 'dev' ? DEV_WS_PORT : OLE_WS_PORT;



const FILE_ROOT = './';



$DB = new mysqli(DBA_HOST, DBA_USER, DBA_PASS, DBA_NAME);



date_default_timezone_set('Asia/Shanghai');



$RESPONSE = new StdClass();



// INIT RESPONSE OBJECT
$RESPONSE->code = 'error';
$RESPONSE->msg  = 'request fail';
$RESPONSE->data = '';
$RESPONSE->page = '';
?>