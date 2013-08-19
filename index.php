<?php
/**
 * Load and initialize all needed classes.
 *
 * @author Dominik Meyer <kinimodmeyer@gmail.com>
 * @copyright Ilch 2.0
 * @package ilch
 */

define('ACCESS', 1);
define('APPLICATION_PATH', '/');

require_once APPLICATION_PATH.'/libraries/ilch/Loader.php';

$page = new Ilch_Page();
$page->generatePage();