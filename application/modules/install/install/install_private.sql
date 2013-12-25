INSERT INTO `[prefix]_pages_content` (`page_id`, `content`, `locale`, `title`, `perma`) VALUES
(1, '<p><strong>Guten Tag und willkommen auf meiner Internetseite!</strong></p>\r\n\r\n<p>Auf dieser Seite möchte ich mich als Person vorstellen.</p>', 'de_DE', 'Startseite', 'startseite.html');

INSERT INTO `[prefix]_pages_content` (`page_id`, `content`, `locale`, `title`, `perma`) VALUES
(1, '<p><strong>Welcome on my Page!</strong></p>\r\n\r\n<p>On this site i want to present me as a person.</p>', 'en_EN', 'Startpage', 'startpage.html');

INSERT INTO `[prefix]_pages` (`id`, `date_created`) VALUES
(1, 'CURRENT_TIMESTAMP');

INSERT INTO `[prefix]_config` (`key`, `value`, `autoload`) VALUES
('start_page', 'page_1', 0);

INSERT INTO `[prefix]_menu` (`id`, `title`) VALUES
(1, 'Hauptmenü');

INSERT INTO `[prefix]_menu_items` (`id`, `menu_id`, `sort`, `parent_id`, `page_id`, `box_id`, `type`, `title`, `href`, `module_key`) VALUES
(1, 1, 10, 0, 1, 0, 1, 'Startseite', 'undefined', ''),
(2, 1, 20, 0, 0, 0, 2, 'Gästebuch', 'undefined', 'guestbook');