<?php
/**
 * File redirect to admin modul.
 * File is needed, to route "/admin" to default route.
 * Workaround for server with no mod_rewrite.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */
header("Location: ../index.php/admin/admin");
exit;
