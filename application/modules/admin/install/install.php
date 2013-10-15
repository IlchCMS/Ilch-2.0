<?php
$date = new DateTime();
$databaseConfig = new \Ilch\Config\Database($db);
$databaseConfig->set('version', VERSION, 1);
$databaseConfig->set('locale', $this->getTranslator()->getLocale(), 1);
$databaseConfig->set('date_cms_installed', $date->format('Y-m-d H:i:s'), 1);
$databaseConfig->set('timezone', $_SESSION['install']['timezone']);