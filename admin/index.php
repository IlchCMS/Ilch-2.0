<?php
/**
 * File redirect to admin modul.
 * File is needed, to route "/admin" to default route.
 * Workaround for server with no mod_rewrite.
 *
 * @author Meyer Dominik
 * @copyright Ilch Pluto
 * @package ilch
 */
header("Location: ../index.php/admin/admin");
exit;
?>