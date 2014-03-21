<?php
/**
 * @copyright Balthazar3k 2014
 * @package Eventplaner 2.0
 */

$config = $this->get('config');
?>

<?=$config->get('event_status');?>
<?=$config->get('event_close_time');?>
<?=$config->get('event_start_time');?>
<?=$config->get('event_ends_time');?>
<?=$config->get('event_time_steps');?>
<?=$config->get('index_eventplaner_rowperpage');?>
<?=$config->get('admin_eventplaner_rowperpage');?>