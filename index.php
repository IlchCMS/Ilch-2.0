<?php
/**
 * @author Dominik Meyer <kinimodmeyer@gmail.com>
 * @copyright Ilch 2.0
 * @package ilch
 */

define('ACCESS', 1);
define('APPLICATION_PATH', __DIR__.'/application');
define('CONFIG_PATH', '/');

require_once APPLICATION_PATH.'/libraries/ilch/Loader.php';


Ilch_Registry::set('startTime', microtime(true));

$page = new Ilch_Page();
$page->loadConfig();
$page->loadCms();