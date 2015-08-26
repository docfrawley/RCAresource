<?php

// Define the core paths
// Define them as absolute paths to make sure that require_once works as expected

// DIRECTORY_SEPARATOR is a PHP pre-defined constant
// (\ for Windows, / for Unix)
//defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

//defined('SITE_ROOT') ? null : 
//	define('SITE_ROOT', DS.'demo');

//defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'includes');

// load config file first
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

defined('SITE_ROOT') ? null : 
	define('SITE_ROOT', DS.'home'.DS.'matheyhp'.DS.'public_html'.DS.'demo');

defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'includes');

// load config file first
require_once(LIB_PATH.DS.'config.php');

// load basic functions next so that everything after can use them
require_once(LIB_PATH.DS.'functions.php');

// load core objects
require_once(LIB_PATH.DS.'database.php');
include_once(LIB_PATH.DS.'rcaobject.php');
include_once(LIB_PATH.DS.'coregroup.php');
include_once(LIB_PATH.DS.'announce.php');
include_once(LIB_PATH.DS.'calendar.php');
include_once(LIB_PATH.DS.'oncallobject.php');
include_once(LIB_PATH.DS.'inserviceobject.php');
include_once(LIB_PATH.DS.'rcaexpobject.php');
include_once(LIB_PATH.DS.'logobject.php');
include_once(LIB_PATH.DS.'oncallday.php');
include_once(LIB_PATH.DS.'expenseobject.php');
include_once(LIB_PATH.DS.'coreexpense.php');
include_once(LIB_PATH.DS.'inservics.php');
include_once(LIB_PATH.DS.'allinservices.php');
include_once('rcalogin.php');
?>