INSERT INTO `ilch_pages_content` (`page_id`, `content`, `locale`, `title`, `perma`) VALUES
(1, '<p><strong>Guten Tag und willkommen auf meiner Internetseite!</strong></p>\r\n<p>&nbsp;</p>\r\n<p>Auf dieser Seite m&ouml;chte ich mich als Person vorstellen sowie einen einen Blog f&uuml;hren.</p>', 'de_DE', 'Startseite', 'startseite.html');

INSERT INTO `ilch_pages` (`id`, `date_created`) VALUES
(1, 'CURRENT_TIMESTAMP');

INSERT INTO `ilch_config` (`key`, `value`, `autoload`) VALUES
('start_page', 'page_1', 0);
