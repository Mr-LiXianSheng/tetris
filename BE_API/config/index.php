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

const DBA_USER = ENV === 'dev' ? DEV_DBA_USER : OLE_DBA_USER;

const DBA_PASS = ENV === 'dev' ? DEV_DBA_PASS : OLE_DBA_PASS;

$db = new mysqli(DBA_HOST,DBA_USER,DBA_PASS,DBA_NAME);



const FILE_ROOT = 'BE_API';



date_default_timezone_set('Asia/Shanghai');
?>