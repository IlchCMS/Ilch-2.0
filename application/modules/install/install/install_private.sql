INSERT INTO `ilch_pages_content` (`page_id`, `content`, `locale`, `title`, `perma`) VALUES
(1, '<p><strong>Guten Tag und willkommen auf meiner Internetseite!</strong></p>\r\n\r\n<p>Auf dieser Seite möchte ich mich als Person vorstellen.</p>', 'de_DE', 'Startseite', 'startseite.html');

INSERT INTO `ilch_pages_content` (`page_id`, `content`, `locale`, `title`, `perma`) VALUES
(1, '<p><strong>Welcome on my Page!</strong></p>\r\n\r\n<p>On this site i want to present me as a person.</p>', 'en_EN', 'Startpage', 'startpage.html');

INSERT INTO `ilch_pages` (`id`, `date_created`) VALUES
(1, 'CURRENT_TIMESTAMP');

INSERT INTO `ilch_config` (`key`, `value`, `autoload`) VALUES
('start_page', 'page_1', 0);