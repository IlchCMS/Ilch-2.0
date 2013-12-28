INSERT INTO `[prefix]_articles_content` (`article_id`, `content`, `locale`, `title`, `perma`) VALUES
(1, 'Guten Tag und willkommen auf meiner Internetseite! Auf dieser Seite möchte ich mich als Person vorstellen.', '', 'Startseite', 'startseite.html');

INSERT INTO `[prefix]_articles` (`id`, `date_created`) VALUES
(1, 'CURRENT_TIMESTAMP');

INSERT INTO `[prefix]_config` (`key`, `value`, `autoload`) VALUES
('start_page', 'module_article', 0);

INSERT INTO `[prefix]_menu` (`id`, `title`) VALUES
(1, 'Hauptmenü');

INSERT INTO `[prefix]_menu_items` (`id`, `menu_id`, `sort`, `parent_id`, `page_id`, `box_id`, `type`, `title`, `href`, `module_key`) VALUES
(1, 1, 10, 0, 0, 0, 2, 'Neuigkeiten', '', 'article'),
(2, 1, 20, 0, 0, 0, 2, 'Gästebuch', '', 'guestbook'),
(3, 1, 30, 0, 0, 0, 2, 'Kontakt', '', 'contact');