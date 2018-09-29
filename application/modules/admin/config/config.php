<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'admin',
        'boxes' => [
            'langswitch' => [
                'de_DE' => [
                    'name' => 'Sprachauswahl'
                ],
                'en_EN' => [
                    'name' => 'Language selection'
                ]
            ],
            'layoutswitch' => [
                'de_DE' => [
                    'name' => 'Layoutauswahl'
                ],
                'en_EN' => [
                    'name' => 'Layout selection'
                ]
            ]
        ]
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $date = new \Ilch\Date();
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('version', VERSION, 1)
            ->set('updateserver', 'https://ilch2.de/development/updateserver/stable/')
            ->set('locale', $this->getTranslator()->getLocale(), 1)
            ->set('date_cms_installed', $date->format('Y-m-d H:i:s'), 1)
            ->set('timezone', $_SESSION['install']['timezone'])
            ->set('default_layout', 'clan3columns')
            ->set('start_page', 'module_article')
            ->set('favicon', '')
            ->set('apple_icon', '')
            ->set('page_title', 'ilch - Content Management System')
            ->set('description', 'Das ilch CMS bietet dir ein einfach erweiterbares Grundsystem, welches keinerlei Kenntnisse in Programmiersprachen voraussetzt.')
            ->set('standardMail', $_SESSION['install']['adminEmail'])
            ->set('defaultPaginationObjects', 20)
            ->set('hideCaptchaFor', '1')
            ->set('admin_layout_hmenu', 'hmenu-fixed')
            ->set('maintenance_mode', '0')
            ->set('maintenance_status', '0')
            ->set('maintenance_date', $date->format('Y-m-d H:i:s'))
            ->set('maintenance_text', '<p>Die Seite befindet sich im Wartungsmodus</p>')
            ->set('custom_css', '')
            ->set('emailBlacklist', '');
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_config` (
                `key` VARCHAR(191) NOT NULL,
                `value` TEXT NOT NULL,
                `autoload` TINYINT(1) NOT NULL,
                UNIQUE KEY `key` (`key`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_emails` (
                `moduleKey` VARCHAR(255) NOT NULL,
                `type` VARCHAR(255) NOT NULL,
                `desc` VARCHAR(255) NOT NULL,
                `text` TEXT NOT NULL,
                `locale` VARCHAR(255) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_modules` (
                `key` VARCHAR(191) NOT NULL,
                `system` TINYINT(1) NOT NULL DEFAULT 0,
                `layout` TINYINT(1) NOT NULL DEFAULT 0,
                `hide_menu` TINYINT(1) NOT NULL DEFAULT 0,
                `author` VARCHAR(255) NULL DEFAULT NULL,
                `version` VARCHAR(255) NULL DEFAULT NULL,
                `link` VARCHAR(255) NULL DEFAULT NULL,
                `icon_small` VARCHAR(255) NOT NULL,
                UNIQUE KEY `key` (`key`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_modules_content` (
                `key` VARCHAR(255) NOT NULL,
                `locale` VARCHAR(255) NOT NULL,
                `description` VARCHAR(255) NOT NULL,
                `name` VARCHAR(255) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_modules_php_extensions` (
                `key` VARCHAR(255) NOT NULL,
                `extension` VARCHAR(255) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_modules_folderrights` (
                `key` VARCHAR(255) NOT NULL,
                `folder` VARCHAR(255) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_modules_boxes_content` (
                `key` VARCHAR(255) NOT NULL,
                `module` VARCHAR(255) NOT NULL,
                `locale` VARCHAR(255) NOT NULL,
                `name` VARCHAR(255) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_menu` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `title` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_menu_items` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `menu_id` INT(11) NOT NULL,
                `sort` INT(11) NOT NULL DEFAULT 0,
                `parent_id` INT(11) NOT NULL DEFAULT 0,
                `page_id` INT(11) NOT NULL DEFAULT 0,
                `box_id` INT(11) NOT NULL DEFAULT 0,
                `box_key` VARCHAR(255) NULL DEFAULT NULL,
                `type` TINYINT(1) NOT NULL,
                `title` VARCHAR(255) NOT NULL,
                `href` VARCHAR(255) NULL DEFAULT NULL,
                `module_key` VARCHAR(255) NULL DEFAULT NULL,
                `access` VARCHAR(255) NOT NULL DEFAULT "",
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_boxes` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `date_created` DATETIME NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_boxes_content` (
                `box_id` INT(11) NOT NULL,
                `content` MEDIUMTEXT NOT NULL,
                `locale` VARCHAR(255) NOT NULL,
                `title` VARCHAR(255) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_pages` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `date_created` DATETIME NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_pages_content` (
                `page_id` INT(11) NOT NULL,
                `content` MEDIUMTEXT NOT NULL,
                `description` MEDIUMTEXT NOT NULL,
                `keywords` MEDIUMTEXT NOT NULL,
                `locale` VARCHAR(255) NOT NULL,
                `title` VARCHAR(255) NOT NULL,
                `perma` VARCHAR(255) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_backup` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(255) NOT NULL,
                `date` DATETIME NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_logs` (
                `user_id` VARCHAR(255) NOT NULL,
                `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `info` VARCHAR(255) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_admin_notifications` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `module` VARCHAR(255) NOT NULL,
                `message` VARCHAR(255) NOT NULL,
                `url` VARCHAR(255) NOT NULL,
                `type` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_admin_notifications_permission` (
                `module` VARCHAR(255) NOT NULL,
                `granted` TINYINT(1) NOT NULL,
                `limit` TINYINT(1) UNSIGNED NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            
            CREATE TABLE IF NOT EXISTS `[prefix]_admin_updateservers` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `url` VARCHAR(255) NOT NULL,
                `operator` VARCHAR(255) NOT NULL,
                `country` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;
            
            INSERT INTO `[prefix]_admin_updateservers` (`id`, `url`, `operator`, `country`) VALUES (1, "https://ilch2.de/development/updateserver/stable/", "corian (ilch-Team)", "Germany");
            INSERT INTO `[prefix]_admin_updateservers` (`id`, `url`, `operator`, `country`) VALUES (2, "https://www.blackcoder.de/ilch-us/stable/", "blackcoder (ilch-Team)", "Germany");';
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "2.0.1":
                // Add new hide_menu column
                $this->db()->query('ALTER TABLE `[prefix]_modules` ADD COLUMN `hide_menu` TINYINT(1) NOT NULL DEFAULT 0;');
                $this->db()->query('UPDATE `[prefix]_modules` SET `hide_menu` = 1 WHERE `key` = "comment";');
                break;
            case "2.0.3":
                // Add new top column for the top article feature
                // Add new read_access column to restrict who can read an article
                $this->db()->query('ALTER TABLE `[prefix]_articles` ADD COLUMN `top` TINYINT(1) NOT NULL DEFAULT 0;');
                $this->db()->query('ALTER TABLE `[prefix]_articles` ADD COLUMN `read_access` VARCHAR(255) NOT NULL DEFAULT \'1,2,3\';');

                removeDir(ROOT_PATH.'/vendor');
                rename(ROOT_PATH.'/_vendor', ROOT_PATH.'/vendor');
                break;
            case "2.1.1":
                // Remove no longer needed gallery_id column.
                $this->db()->query('ALTER TABLE `[prefix]_users_gallery_items` DROP COLUMN `gallery_id`;');
                break;
            case "2.1.2":
                // Add new votes column for the article rating feature
                $this->db()->query('ALTER TABLE `[prefix]_articles_content` ADD COLUMN `votes` LONGTEXT NOT NULL;');

                removeDir(ROOT_PATH.'/vendor');
                rename(ROOT_PATH.'/_vendor', ROOT_PATH.'/vendor');
                break;
            case "2.1.3":
                $this->db()->query('ALTER TABLE `[prefix]_menu_items` MODIFY COLUMN `access` VARCHAR(255) NOT NULL DEFAULT "";');
                $this->db()->query('ALTER TABLE `[prefix]_users` MODIFY COLUMN `locale` VARCHAR(255) NOT NULL DEFAULT "";');
                break;
            case "2.1.4":
                // Add new columns for user profile
                $this->db()->query('ALTER TABLE `[prefix]_users` ADD COLUMN `steam` VARCHAR(255) NOT NULL;');
                $this->db()->query('ALTER TABLE `[prefix]_users` ADD COLUMN `twitch` VARCHAR(255) NOT NULL;');
                $this->db()->query('ALTER TABLE `[prefix]_users` ADD COLUMN `teamspeak` VARCHAR(255) NOT NULL;');
                $this->db()->query('ALTER TABLE `[prefix]_users` ADD COLUMN `discord` VARCHAR(255) NOT NULL;');
                break;
            case "2.1.5":
                removeDir(ROOT_PATH.'/vendor');
                rename(ROOT_PATH.'/_vendor', ROOT_PATH.'/vendor');
                break;
            case "2.1.7":
                // Create statistic_visibleStats and convert old settings into new format
                $databaseConfig = new \Ilch\Config\Database($this->db());
                $visibilitySettings = $databaseConfig->get('statistic_site');

                if ($databaseConfig->get('statistic_site')) {
                    $visibilitySettings .= ',1,1';
                } else {
                    $visibilitySettings .= ',0,0';
                }

                $visibilitySettings .= ','.$databaseConfig->get('statistic_visits');
                $visibilitySettings .= ','.$databaseConfig->get('statistic_browser');
                $visibilitySettings .= ','.$databaseConfig->get('statistic_os');
                $databaseConfig->set('statistic_visibleStats', $visibilitySettings, 0);

                // Remove the no longer needed settings of the statistic module
                $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'statistic_site';
                             DELETE FROM `[prefix]_config` WHERE `key` = 'statistic_visits';
                             DELETE FROM `[prefix]_config` WHERE `key` = 'statistic_browser';
                             DELETE FROM `[prefix]_config` WHERE `key` = 'statistic_os';");

                // Add new default rule to the beginning of the current rules (DSGVO)
                $databaseConfig->set('regist_rules', '<p>Mit der Registrierung auf dieser Webseite, akzeptieren Sie die Datenschutzbestimmungen und den Haftungsausschluss.</p>'
                    .$databaseConfig->get('regist_rules'));

                // Add default value for the captcha setting, which indicates that administrators should not need to solve captchas.
                $databaseConfig->set('hideCaptchaFor', '1');
                break;
            case "2.1.8":
                // Imprint module
                // Create new needed column "imprint"
                $this->db()->query('ALTER TABLE `[prefix]_imprint` ADD COLUMN `imprint` MEDIUMTEXT NULL DEFAULT NULL;');

                // Copy previous entered information to the new column "imprint"
                $content = $this->db()->select('*')
                    ->from('imprint')
                    ->execute()
                    ->fetchAssoc();
                $contentString = '<b>'.$content['paragraph'].'</b><br><br>';
                $contentString .= $content['company'].'<br>';
                $contentString .= $content['name'].'<br>';
                $contentString .= $content['address'].'<br>';
                $contentString .= $content['addressadd'].'<br><br>';
                $contentString .= $content['city'].'<br><br>';

                $contentString .= '<b>Kontakt</b><br>';
                $contentString .= 'Telefon: '.$content['phone'].'<br>';
                $contentString .= 'Telefax: '.$content['fax'].'<br>';
                $contentString .= 'E-Mail: '.$content['email'].'<br><br>';

                $contentString .= 'Registergericht: '.$content['registration'].'<br>';
                $contentString .= 'Handelsregisternummer: '.$content['commercialregister'].'<br>';
                $contentString .= 'Umsatzsteuer-ID-Nummer: '.$content['vatid'].'<br>';

                $contentString .= $content['other'].'<br><br>';
                $contentString .= $content['disclaimer'].'<br>';

                $this->db()->query('UPDATE `[prefix]_imprint` SET `imprint` = \''.$contentString.'\';');

                // Delete now unneeded old columns
                $this->db()->query('ALTER TABLE `[prefix]_imprint` DROP COLUMN `paragraph`;');
                $this->db()->query('ALTER TABLE `[prefix]_imprint` DROP COLUMN `company`;');
                $this->db()->query('ALTER TABLE `[prefix]_imprint` DROP COLUMN `name`;');
                $this->db()->query('ALTER TABLE `[prefix]_imprint` DROP COLUMN `address`;');
                $this->db()->query('ALTER TABLE `[prefix]_imprint` DROP COLUMN `addressadd`;');
                $this->db()->query('ALTER TABLE `[prefix]_imprint` DROP COLUMN `city`;');
                $this->db()->query('ALTER TABLE `[prefix]_imprint` DROP COLUMN `phone`;');
                $this->db()->query('ALTER TABLE `[prefix]_imprint` DROP COLUMN `fax`;');
                $this->db()->query('ALTER TABLE `[prefix]_imprint` DROP COLUMN `email`;');
                $this->db()->query('ALTER TABLE `[prefix]_imprint` DROP COLUMN `registration`;');
                $this->db()->query('ALTER TABLE `[prefix]_imprint` DROP COLUMN `commercialregister`;');
                $this->db()->query('ALTER TABLE `[prefix]_imprint` DROP COLUMN `vatid`;');
                $this->db()->query('ALTER TABLE `[prefix]_imprint` DROP COLUMN `other`;');
                $this->db()->query('ALTER TABLE `[prefix]_imprint` DROP COLUMN `disclaimer`;');

                // Delete unneeded files and folders
                unlink(ROOT_PATH.'/application/modules/imprint/controllers/admin/Settings.php');
                removeDir(ROOT_PATH.'/application/modules/imprint/views/admin/settings');
                
                // Privacy module
                // Insert new templates
                $this->db()->query('INSERT INTO `[prefix]_privacy` (`title`, `urltitle`, `url`, `text`, `show`) VALUES
                ("Datenschutz auf einen Blick", "eRecht24", "https://www.e-recht24.de", "<h3>Allgemeine Hinweise</h3> <p>Die folgenden Hinweise geben einen einfachen Überblick darüber, was mit Ihren personenbezogenen Daten passiert, wenn Sie unsere Website besuchen. Personenbezogene Daten sind alle Daten, mit denen Sie persönlich identifiziert werden können. Ausführliche Informationen zum Thema Datenschutz entnehmen Sie unserer unter diesem Text aufgeführten Datenschutzerklärung.</p> <h3>Datenerfassung auf unserer Website</h3> <p><strong>Wer ist verantwortlich für die Datenerfassung auf dieser Website?</strong></p> <p>Die Datenverarbeitung auf dieser Website erfolgt durch den Websitebetreiber. Dessen Kontaktdaten können Sie dem Impressum dieser Website entnehmen.</p> <p><strong>Wie erfassen wir Ihre Daten?</strong></p> <p>Ihre Daten werden zum einen dadurch erhoben, dass Sie uns diese mitteilen. Hierbei kann es sich z.B. um Daten handeln, die Sie in ein Kontaktformular eingeben.</p> <p>Andere Daten werden automatisch beim Besuch der Website durch unsere IT-Systeme erfasst. Das sind vor allem technische Daten (z.B. Internetbrowser, Betriebssystem oder Uhrzeit des Seitenaufrufs). Die Erfassung dieser Daten erfolgt automatisch, sobald Sie unsere Website betreten.</p> <p><strong>Wofür nutzen wir Ihre Daten?</strong></p> <p>Ein Teil der Daten wird erhoben, um eine fehlerfreie Bereitstellung der Website zu gewährleisten. Andere Daten können zur Analyse Ihres Nutzerverhaltens verwendet werden.</p> <p><strong>Welche Rechte haben Sie bezüglich Ihrer Daten?</strong></p> <p>Sie haben jederzeit das Recht unentgeltlich Auskunft über Herkunft, Empfänger und Zweck Ihrer gespeicherten personenbezogenen Daten zu erhalten. Sie haben außerdem ein Recht, die Berichtigung, Sperrung oder Löschung dieser Daten zu verlangen. Hierzu sowie zu weiteren Fragen zum Thema Datenschutz können Sie sich jederzeit unter der im Impressum angegebenen Adresse an uns wenden. Des Weiteren steht Ihnen ein Beschwerderecht bei der zuständigen Aufsichtsbehörde zu.</p> <h3>Analyse-Tools und Tools von Drittanbietern</h3> <p>Beim Besuch unserer Website kann Ihr Surf-Verhalten statistisch ausgewertet werden. Das geschieht vor allem mit Cookies und mit sogenannten Analyseprogrammen. Die Analyse Ihres Surf-Verhaltens erfolgt in der Regel anonym; das Surf-Verhalten kann nicht zu Ihnen zurückverfolgt werden. Sie können dieser Analyse widersprechen oder sie durch die Nichtbenutzung bestimmter Tools verhindern. Detaillierte Informationen dazu finden Sie in der folgenden Datenschutzerklärung.</p> <p>Sie können dieser Analyse widersprechen. Über die Widerspruchsmöglichkeiten werden wir Sie in dieser Datenschutzerklärung informieren.</p>", 0),
                ("Allgemeine Hinweise und Pflichtinformationen", "eRecht24", "https://www.e-recht24.de", "<h3>Datenschutz</h3> <p>Die Betreiber dieser Seiten nehmen den Schutz Ihrer persönlichen Daten sehr ernst. Wir behandeln Ihre personenbezogenen Daten vertraulich und entsprechend der gesetzlichen Datenschutzvorschriften sowie dieser Datenschutzerklärung.</p> <p>Wenn Sie diese Website benutzen, werden verschiedene personenbezogene Daten erhoben. Personenbezogene Daten sind Daten, mit denen Sie persönlich identifiziert werden können. Die vorliegende Datenschutzerklärung erläutert, welche Daten wir erheben und wofür wir sie nutzen. Sie erläutert auch, wie und zu welchem Zweck das geschieht.</p> <p>Wir weisen darauf hin, dass die Datenübertragung im Internet (z.B. bei der Kommunikation per E-Mail) Sicherheitslücken aufweisen kann. Ein lückenloser Schutz der Daten vor dem Zugriff durch Dritte ist nicht möglich.</p> <h3>Hinweis zur verantwortlichen Stelle</h3> <p>Die verantwortliche Stelle für die Datenverarbeitung auf dieser Website ist:</p> <p>Beispielfirma<br /> Musterweg 10<br /> 90210 Musterstadt</p>  <p>Telefon: +49 (0) 123 44 55 66<br /> E-Mail: info@beispielfirma.de</p>  <p>Verantwortliche Stelle ist die natürliche oder juristische Person, die allein oder gemeinsam mit anderen über die Zwecke und Mittel der Verarbeitung von personenbezogenen Daten (z.B. Namen, E-Mail-Adressen o. Ä.) entscheidet.</p> <h3>Widerruf Ihrer Einwilligung zur Datenverarbeitung</h3> <p>Viele Datenverarbeitungsvorgänge sind nur mit Ihrer ausdrücklichen Einwilligung möglich. Sie können eine bereits erteilte Einwilligung jederzeit widerrufen. Dazu reicht eine formlose Mitteilung per E-Mail an uns. Die Rechtmäßigkeit der bis zum Widerruf erfolgten Datenverarbeitung bleibt vom Widerruf unberührt.</p> <h3>Beschwerderecht bei der zuständigen Aufsichtsbehörde</h3> <p>Im Falle datenschutzrechtlicher Verstöße steht dem Betroffenen ein Beschwerderecht bei der zuständigen Aufsichtsbehörde zu. Zuständige Aufsichtsbehörde in datenschutzrechtlichen Fragen ist der Landesdatenschutzbeauftragte des Bundeslandes, in dem unser Unternehmen seinen Sitz hat. Eine Liste der Datenschutzbeauftragten sowie deren Kontaktdaten können folgendem Link entnommen werden: <a href=\"https://www.bfdi.bund.de/DE/Infothek/Anschriften_Links/anschriften_links-node.html\" target=\"_blank\">https://www.bfdi.bund.de/DE/Infothek/Anschriften_Links/anschriften_links-node.html</a>.</p> <h3>Recht auf Datenübertragbarkeit</h3> <p>Sie haben das Recht, Daten, die wir auf Grundlage Ihrer Einwilligung oder in Erfüllung eines Vertrags automatisiert verarbeiten, an sich oder an einen Dritten in einem gängigen, maschinenlesbaren Format aushändigen zu lassen. Sofern Sie die direkte Übertragung der Daten an einen anderen Verantwortlichen verlangen, erfolgt dies nur, soweit es technisch machbar ist.</p> <h3>SSL- bzw. TLS-Verschlüsselung</h3> <p>Diese Seite nutzt aus Sicherheitsgründen und zum Schutz der Übertragung vertraulicher Inhalte, wie zum Beispiel Bestellungen oder Anfragen, die Sie an uns als Seitenbetreiber senden, eine SSL-bzw. TLS-Verschlüsselung. Eine verschlüsselte Verbindung erkennen Sie daran, dass die Adresszeile des Browsers von “http://” auf “https://” wechselt und an dem Schloss-Symbol in Ihrer Browserzeile.</p> <p>Wenn die SSL- bzw. TLS-Verschlüsselung aktiviert ist, können die Daten, die Sie an uns übermitteln, nicht von Dritten mitgelesen werden.</p> <h3>Verschlüsselter Zahlungsverkehr auf dieser Website</h3> <p>Besteht nach dem Abschluss eines kostenpflichtigen Vertrags eine Verpflichtung, uns Ihre Zahlungsdaten (z.B. Kontonummer bei Einzugsermächtigung) zu übermitteln, werden diese Daten zur Zahlungsabwicklung benötigt.</p> <p>Der Zahlungsverkehr über die gängigen Zahlungsmittel (Visa/MasterCard, Lastschriftverfahren) erfolgt ausschließlich über eine verschlüsselte SSL- bzw. TLS-Verbindung. Eine verschlüsselte Verbindung erkennen Sie daran, dass die Adresszeile des Browsers von \"http://\" auf \"https://\" wechselt und an dem Schloss-Symbol in Ihrer Browserzeile.</p> <p>Bei verschlüsselter Kommunikation können Ihre Zahlungsdaten, die Sie an uns übermitteln, nicht von Dritten mitgelesen werden.</p> <h3>Auskunft, Sperrung, Löschung</h3> <p>Sie haben im Rahmen der geltenden gesetzlichen Bestimmungen jederzeit das Recht auf unentgeltliche Auskunft über Ihre gespeicherten personenbezogenen Daten, deren Herkunft und Empfänger und den Zweck der Datenverarbeitung und ggf. ein Recht auf Berichtigung, Sperrung oder Löschung dieser Daten. Hierzu sowie zu weiteren Fragen zum Thema personenbezogene Daten können Sie sich jederzeit unter der im Impressum angegebenen Adresse an uns wenden.</p> <h3>Widerspruch gegen Werbe-Mails</h3> <p>Der Nutzung von im Rahmen der Impressumspflicht veröffentlichten Kontaktdaten zur Übersendung von nicht ausdrücklich angeforderter Werbung und Informationsmaterialien wird hiermit widersprochen. Die Betreiber der Seiten behalten sich ausdrücklich rechtliche Schritte im Falle der unverlangten Zusendung von Werbeinformationen, etwa durch Spam-E-Mails, vor.</p>", 0),
                ("Datenschutzbeauftragter", "eRecht24", "https://www.e-recht24.de", "<h3>Gesetzlich vorgeschriebener Datenschutzbeauftragter</h3> <p>Wir haben für unser Unternehmen einen Datenschutzbeauftragten bestellt.</p> <p>Beispielfirma<br /> Musterweg 10<br /> 90210 Musterstadt</p>  <p>Telefon: +49 (0) 123 44 55 66<br /> E-Mail: info@beispielfirma.de</p>", 0),
                ("Datenerfassung auf unserer Website", "eRecht24", "https://www.e-recht24.de", "<h3>Cookies</h3> <p>Die Internetseiten verwenden teilweise so genannte Cookies. Cookies richten auf Ihrem Rechner keinen Schaden an und enthalten keine Viren. Cookies dienen dazu, unser Angebot nutzerfreundlicher, effektiver und sicherer zu machen. Cookies sind kleine Textdateien, die auf Ihrem Rechner abgelegt werden und die Ihr Browser speichert.</p> <p>Die meisten der von uns verwendeten Cookies sind so genannte “Session-Cookies”. Sie werden nach Ende Ihres Besuchs automatisch gelöscht. Andere Cookies bleiben auf Ihrem Endgerät gespeichert bis Sie diese löschen. Diese Cookies ermöglichen es uns, Ihren Browser beim nächsten Besuch wiederzuerkennen.</p> <p>Sie können Ihren Browser so einstellen, dass Sie über das Setzen von Cookies informiert werden und Cookies nur im Einzelfall erlauben, die Annahme von Cookies für bestimmte Fälle oder generell ausschließen sowie das automatische Löschen der Cookies beim Schließen des Browser aktivieren. Bei der Deaktivierung von Cookies kann die Funktionalität dieser Website eingeschränkt sein.</p> <p>Cookies, die zur Durchführung des elektronischen Kommunikationsvorgangs oder zur Bereitstellung bestimmter, von Ihnen erwünschter Funktionen (z.B. Warenkorbfunktion) erforderlich sind, werden auf Grundlage von Art. 6 Abs. 1 lit. f DSGVO gespeichert. Der Websitebetreiber hat ein berechtigtes Interesse an der Speicherung von Cookies zur technisch fehlerfreien und optimierten Bereitstellung seiner Dienste. Soweit andere Cookies (z.B. Cookies zur Analyse Ihres Surfverhaltens) gespeichert werden, werden diese in dieser Datenschutzerklärung gesondert behandelt.</p> <h3>Server-Log-Dateien</h3> <p>Der Provider der Seiten erhebt und speichert automatisch Informationen in so genannten Server-Log-Dateien, die Ihr Browser automatisch an uns übermittelt. Dies sind:</p> <ul> <li>Browsertyp und Browserversion</li> <li>verwendetes Betriebssystem</li> <li>Referrer URL</li> <li>Hostname des zugreifenden Rechners</li> <li>Uhrzeit der Serveranfrage</li> <li>IP-Adresse</li> </ul> <p>Eine Zusammenführung dieser Daten mit anderen Datenquellen wird nicht vorgenommen.</p> <p>Grundlage für die Datenverarbeitung ist Art. 6 Abs. 1 lit. b DSGVO, der die Verarbeitung von Daten zur Erfüllung eines Vertrags oder vorvertraglicher Maßnahmen gestattet.</p> <h3>Kontaktformular</h3> <p>Wenn Sie uns per Kontaktformular Anfragen zukommen lassen, werden Ihre Angaben aus dem Anfrageformular inklusive der von Ihnen dort angegebenen Kontaktdaten zwecks Bearbeitung der Anfrage und für den Fall von Anschlussfragen bei uns gespeichert. Diese Daten geben wir nicht ohne Ihre Einwilligung weiter.</p> <p>Die Verarbeitung der in das Kontaktformular eingegebenen Daten erfolgt somit ausschließlich auf Grundlage Ihrer Einwilligung (Art. 6 Abs. 1 lit. a DSGVO). Sie können diese Einwilligung jederzeit widerrufen. Dazu reicht eine formlose Mitteilung per E-Mail an uns. Die Rechtmäßigkeit der bis zum Widerruf erfolgten Datenverarbeitungsvorgänge bleibt vom Widerruf unberührt.</p> <p>Die von Ihnen im Kontaktformular eingegebenen Daten verbleiben bei uns, bis Sie uns zur Löschung auffordern, Ihre Einwilligung zur Speicherung widerrufen oder der Zweck für die Datenspeicherung entfällt (z.B. nach abgeschlossener Bearbeitung Ihrer Anfrage). Zwingende gesetzliche Bestimmungen – insbesondere Aufbewahrungsfristen – bleiben unberührt.</p> <h3>Registrierung auf dieser Website</h3> <p>Sie können sich auf unserer Website registrieren, um zusätzliche Funktionen auf der Seite zu nutzen. Die dazu eingegebenen Daten verwenden wir nur zum Zwecke der Nutzung des jeweiligen Angebotes oder Dienstes, für den Sie sich registriert haben. Die bei der Registrierung abgefragten Pflichtangaben müssen vollständig angegeben werden. Anderenfalls werden wir die Registrierung ablehnen.</p> <p>Für wichtige Änderungen etwa beim Angebotsumfang oder bei technisch notwendigen Änderungen nutzen wir die bei der Registrierung angegebene E-Mail-Adresse, um Sie auf diesem Wege zu informieren.</p> <p>Die Verarbeitung der bei der Registrierung eingegebenen Daten erfolgt auf Grundlage Ihrer Einwilligung (Art. 6 Abs. 1 lit. a DSGVO). Sie können eine von Ihnen erteilte Einwilligung jederzeit widerrufen. Dazu reicht eine formlose Mitteilung per E-Mail an uns. Die Rechtmäßigkeit der bereits erfolgten Datenverarbeitung bleibt vom Widerruf unberührt.</p> <p>Die bei der Registrierung erfassten Daten werden von uns gespeichert, solange Sie auf unserer Website registriert sind und werden anschließend gelöscht. Gesetzliche Aufbewahrungsfristen bleiben unberührt.</p> <h3>Registrierung mit Facebook Connect</h3> <p>Statt einer direkten Registrierung auf unserer Website können Sie sich mit Facebook Connect registrieren. Anbieter dieses Dienstes ist die Facebook Ireland Limited, 4 Grand Canal Square, Dublin 2, Irland.</p> <p>Wenn Sie sich für die Registrierung mit Facebook Connect entscheiden und auf den “Login with Facebook”- / “Connect with Facebook”-Button klicken, werden Sie automatisch auf die Plattform von Facebook weitergeleitet. Dort können Sie sich mit Ihren Nutzungsdaten anmelden. Dadurch wird Ihr Facebook-Profil mit unserer Website bzw. unseren Diensten verknüpft. Durch diese Verknüpfung erhalten wir Zugriff auf Ihre bei Facebook hinterlegten Daten. Dies sind vor allem:</p> <ul> <li>Facebook-Name</li> <li>Facebook-Profil- und Titelbild</li> <li>Facebook-Titelbild</li> <li>bei Facebook hinterlegte E-Mail-Adresse</li> <li>Facebook-ID</li> <li>Facebook-Freundeslisten</li> <li>Facebook Likes (“Gefällt-mir”-Angaben)</li> <li>Geburtstag</li> <li>Geschlecht</li> <li>Land</li> <li>Sprache</li> </ul> <p>Diese Daten werden zur Einrichtung, Bereitstellung und Personalisierung Ihres Accounts genutzt.</p> <p>Weitere Informationen finden Sie in den Facebook-Nutzungsbedingungen und den Facebook-Datenschutzbestimmungen. Diese finden Sie unter: <a href=\"https://de-de.facebook.com/about/privacy/\" target=\"_blank\">https://de-de.facebook.com/about/privacy/</a> und <a href=\"https://www.facebook.com/legal/terms/\" target=\"_blank\">https://www.facebook.com/legal/terms/</a>.</p> <h3>Kommentarfunktion auf dieser Website</h3> <p>Für die Kommentarfunktion auf dieser Seite werden neben Ihrem Kommentar auch Angaben zum Zeitpunkt der Erstellung des Kommentars, Ihre E-Mail-Adresse und, wenn Sie nicht anonym posten, der von Ihnen gewählte Nutzername gespeichert.</p> <p><strong>Speicherdauer der Kommentare</strong></p> <p>Die Kommentare und die damit verbundenen Daten (z.B. IP-Adresse) werden gespeichert und verbleiben auf unserer Website, bis der kommentierte Inhalt vollständig gelöscht wurde oder die Kommentare aus rechtlichen Gründen gelöscht werden müssen (z.B. beleidigende Kommentare).</p> <p><strong>Rechtsgrundlage</strong></p> <p>Die Speicherung der Kommentare erfolgt auf Grundlage Ihrer Einwilligung (Art. 6 Abs. 1 lit. a DSGVO). Sie können eine von Ihnen erteilte Einwilligung jederzeit widerrufen. Dazu reicht eine formlose Mitteilung per E-Mail an uns. Die Rechtmäßigkeit der bereits erfolgten Datenverarbeitungsvorgänge bleibt vom Widerruf unberührt.</p>", 0),
                ("Soziale Medien", "eRecht24", "https://www.e-recht24.de", "<h3>Inhalte teilen über Plugins (Facebook, Google+1, Twitter & Co.)</h3> <p>Die Inhalte auf unseren Seiten können datenschutzkonform in sozialen Netzwerken wie Facebook, Twitter oder Google+ geteilt werden. Diese Seite nutzt dafür das <a href=\"https://www.e-recht24.de/erecht24-safe-sharing.html#datenschutz\" target=\"_blank\">eRecht24 Safe Sharing Tool</a>. Dieses Tool stellt den direkten Kontakt zwischen den Netzwerken und Nutzern erst dann her, wenn der Nutzer aktiv auf einen dieser Button klickt.</p> <p>Eine automatische Übertragung von Nutzerdaten an die Betreiber dieser Plattformen erfolgt durch dieses Tool nicht. Ist der Nutzer bei einem der sozialen Netzwerke angemeldet, erscheint bei der Nutzung der Social-Buttons von Facebook, Google+1, Twitter & Co. ein Informations-Fenster, in dem der Nutzer den Text vor dem Absenden bestätigen kann.</p> <p>Unsere Nutzer können die Inhalte dieser Seite datenschutzkonform in sozialen Netzwerken teilen, ohne dass komplette Surf-Profile durch die Betreiber der Netzwerke erstellt werden.</p> <h3>Facebook-Plugins (Like & Share-Button)</h3> <p>Auf unseren Seiten sind Plugins des sozialen Netzwerks Facebook, Anbieter Facebook Inc., 1 Hacker Way, Menlo Park, California 94025, USA, integriert. Die Facebook-Plugins erkennen Sie an dem Facebook-Logo oder dem \"Like-Button\" (\"Gefällt mir\") auf unserer Seite. Eine Übersicht über die Facebook-Plugins finden Sie hier: <a href=\"https://developers.facebook.com/docs/plugins/\" target=\"_blank\">https://developers.facebook.com/docs/plugins/</a>.</p> <p>Wenn Sie unsere Seiten besuchen, wird über das Plugin eine direkte Verbindung zwischen Ihrem Browser und dem Facebook-Server hergestellt. Facebook erhält dadurch die Information, dass Sie mit Ihrer IP-Adresse unsere Seite besucht haben. Wenn Sie den Facebook \"Like-Button\" anklicken während Sie in Ihrem Facebook-Account eingeloggt sind, können Sie die Inhalte unserer Seiten auf Ihrem Facebook-Profil verlinken. Dadurch kann Facebook den Besuch unserer Seiten Ihrem Benutzerkonto zuordnen. Wir weisen darauf hin, dass wir als Anbieter der Seiten keine Kenntnis vom Inhalt der übermittelten Daten sowie deren Nutzung durch Facebook erhalten. Weitere Informationen hierzu finden Sie in der Datenschutzerklärung von Facebook unter: <a href=\"https://de-de.facebook.com/policy.php\" target=\"_blank\">https://de-de.facebook.com/policy.php</a>.</p> <p>Wenn Sie nicht wünschen, dass Facebook den Besuch unserer Seiten Ihrem Facebook-Nutzerkonto zuordnen kann, loggen Sie sich bitte aus Ihrem Facebook-Benutzerkonto aus.</p> <h3>Twitter Plugin</h3> <p>Auf unseren Seiten sind Funktionen des Dienstes Twitter eingebunden. Diese Funktionen werden angeboten durch die Twitter Inc., 1355 Market Street, Suite 900, San Francisco, CA 94103, USA. Durch das Benutzen von Twitter und der Funktion \"Re-Tweet\" werden die von Ihnen besuchten Websites mit Ihrem Twitter-Account verknüpft und anderen Nutzern bekannt gegeben. Dabei werden auch Daten an Twitter übertragen. Wir weisen darauf hin, dass wir als Anbieter der Seiten keine Kenntnis vom Inhalt der übermittelten Daten sowie deren Nutzung durch Twitter erhalten. Weitere Informationen hierzu finden Sie in der Datenschutzerklärung von Twitter unter: <a href=\"https://twitter.com/privacy\" target=\"_blank\">https://twitter.com/privacy</a>.</p> <p>Ihre Datenschutzeinstellungen bei Twitter können Sie in den Konto-Einstellungen unter <a href=\"https://twitter.com/account/settings\" target=\"_blank\">https://twitter.com/account/settings</a> ändern.</p> <h3>Google+ Plugin</h3> <p>Unsere Seiten nutzen Funktionen von Google+. Anbieter ist die Google Inc., 1600 Amphitheatre Parkway, Mountain View, CA 94043, USA.</p> <p>Erfassung und Weitergabe von Informationen: Mithilfe der Google+-Schaltfläche können Sie Informationen weltweit veröffentlichen. Über die Google+-Schaltfläche erhalten Sie und andere Nutzer personalisierte Inhalte von Google und unseren Partnern. Google speichert sowohl die Information, dass Sie für einen Inhalt +1 gegeben haben, als auch Informationen über die Seite, die Sie beim Klicken auf +1 angesehen haben. Ihre +1 können als Hinweise zusammen mit Ihrem Profilnamen und Ihrem Foto in Google-Diensten, wie etwa in Suchergebnissen oder in Ihrem Google-Profil, oder an anderen Stellen auf Websites und Anzeigen im Internet eingeblendet werden.</p> <p>Google zeichnet Informationen über Ihre +1-Aktivitäten auf, um die Google-Dienste für Sie und andere zu verbessern. Um die Google+-Schaltfläche verwenden zu können, benötigen Sie ein weltweit sichtbares, öffentliches Google-Profil, das zumindest den für das Profil gewählten Namen enthalten muss. Dieser Name wird in allen Google-Diensten verwendet. In manchen Fällen kann dieser Name auch einen anderen Namen ersetzen, den Sie beim Teilen von Inhalten über Ihr Google-Konto verwendet haben. Die Identität Ihres Google-Profils kann Nutzern angezeigt werden, die Ihre E-Mail-Adresse kennen oder über andere identifizierende Informationen von Ihnen verfügen.</p> <p>Verwendung der erfassten Informationen: Neben den oben erläuterten Verwendungszwecken werden die von Ihnen bereitgestellten Informationen gemäß den geltenden Google-Datenschutzbestimmungen genutzt. Google veröffentlicht möglicherweise zusammengefasste Statistiken über die +1-Aktivitäten der Nutzer bzw. gibt diese an Nutzer und Partner weiter, wie etwa Publisher, Inserenten oder verbundene Websites.</p> <h3>Instagram Plugin</h3> <p>Auf unseren Seiten sind Funktionen des Dienstes Instagram eingebunden. Diese Funktionen werden angeboten durch die Instagram Inc., 1601 Willow Road, Menlo Park, CA 94025, USA integriert.</p> <p>Wenn Sie in Ihrem Instagram-Account eingeloggt sind, können Sie durch Anklicken des Instagram-Buttons die Inhalte unserer Seiten mit Ihrem Instagram-Profil verlinken. Dadurch kann Instagram den Besuch unserer Seiten Ihrem Benutzerkonto zuordnen. Wir weisen darauf hin, dass wir als Anbieter der Seiten keine Kenntnis vom Inhalt der übermittelten Daten sowie deren Nutzung durch Instagram erhalten.</p> <p>Weitere Informationen hierzu finden Sie in der Datenschutzerklärung von Instagram: <a href=\"https://instagram.com/about/legal/privacy/\" target=\"_blank\">https://instagram.com/about/legal/privacy/</a>.</p> <h3>Tumblr Plugin</h3> <p>Unsere Seiten nutzen Schaltflächen des Dienstes Tumblr. Anbieter ist die Tumblr, Inc., 35 East 21st St, 10th Floor, New York, NY 10010, USA.</p> <p>Diese Schaltflächen ermöglichen es Ihnen, einen Beitrag oder eine Seite bei Tumblr zu teilen oder dem Anbieter bei Tumblr zu folgen. Wenn Sie eine unserer Websites mit Tumblr-Button aufrufen, baut der Browser eine direkte Verbindung mit den Servern von Tumblr auf. Wir haben keinen Einfluss auf den Umfang der Daten, die Tumblr mit Hilfe dieses Plugins erhebt und übermittelt. Nach aktuellem Stand werden die IP-Adresse des Nutzers sowie die URL der jeweiligen Website übermittelt.</p> <p>Weitere Informationen hierzu finden sich in der Datenschutzerklärung von Tumblr unter: <a href=\"https://www.tumblr.com/policy/de/privacy\" target=\"_blank\">https://www.tumblr.com/policy/de/privacy</a>.</p> <h3>LinkedIn Plugin</h3> <p>Unsere Website nutzt Funktionen des Netzwerks LinkedIn. Anbieter ist die LinkedIn Corporation, 2029 Stierlin Court, Mountain View, CA 94043, USA. </p> <p>Bei jedem Abruf einer unserer Seiten, die Funktionen von LinkedIn enthält, wird eine Verbindung zu Servern von LinkedIn aufgebaut. LinkedIn wird darüber informiert, dass Sie unsere Internetseiten mit Ihrer IP-Adresse besucht haben. Wenn Sie den \"Recommend-Button\" von LinkedIn anklicken und in Ihrem Account bei LinkedIn eingeloggt sind, ist es LinkedIn möglich, Ihren Besuch auf unserer Internetseite Ihnen und Ihrem Benutzerkonto zuzuordnen. Wir weisen darauf hin, dass wir als Anbieter der Seiten keine Kenntnis vom Inhalt der übermittelten Daten sowie deren Nutzung durch LinkedIn haben.</p> <p>Weitere Informationen hierzu finden Sie in der Datenschutzerklärung von LinkedIn unter: <a href=\"https://www.linkedin.com/legal/privacy-policy\" target=\"_blank\">https://www.linkedin.com/legal/privacy-policy</a>.</p> <h3>XING Plugin</h3> <p>Unsere Website nutzt Funktionen des Netzwerks XING. Anbieter ist die XING AG, Dammtorstraße 29-32, 20354 Hamburg, Deutschland.</p> <p>Bei jedem Abruf einer unserer Seiten, die Funktionen von XING enthält, wird eine Verbindung zu Servern von XING hergestellt. Eine Speicherung von personenbezogenen Daten erfolgt dabei nach unserer Kenntnis nicht. Insbesondere werden keine IP-Adressen gespeichert oder das Nutzungsverhalten ausgewertet.</p> <p>Weitere Information zum Datenschutz und dem XING Share-Button finden Sie in der Datenschutzerklärung von XING unter: <a href=\"https://www.xing.com/app/share?op=data_protection\" target=\"_blank\">https://www.xing.com/app/share?op=data_protection</a>.</p> <h3>Pinterest Plugin</h3> <p>Auf unserer Seite verwenden wir Social Plugins des sozialen Netzwerkes Pinterest, das von der Pinterest Inc., 808 Brannan Street, San Francisco, CA 94103-490, USA (\"Pinterest\") betrieben wird.</p> <p>Wenn Sie eine Seite aufrufen, die ein solches Plugin enthält, stellt Ihr Browser eine direkte Verbindung zu den Servern von Pinterest her. Das Plugin übermittelt dabei Protokolldaten an den Server von Pinterest in die USA. Diese Protokolldaten enthalten möglicherweise Ihre IP-Adresse, die Adresse der besuchten Websites, die ebenfalls Pinterest-Funktionen enthalten, Art und Einstellungen des Browsers, Datum und Zeitpunkt der Anfrage, Ihre Verwendungsweise von Pinterest sowie Cookies.</p> <p>Weitere Informationen zu Zweck, Umfang und weiterer Verarbeitung und Nutzung der Daten durch Pinterest sowie Ihre diesbezüglichen Rechte und Möglichkeiten zum Schutz Ihrer Privatsphäre finden Sie in den Datenschutzhinweisen von Pinterest: <a href=\"https://about.pinterest.com/de/privacy-policy\" target=\"_blank\">https://about.pinterest.com/de/privacy-policy</a>.</p>", 0),
                ("Analyse Tools und Werbung", "eRecht24", "https://www.e-recht24.de", "<h3>Google Analytics</h3> <p>Diese Website nutzt Funktionen des Webanalysedienstes Google Analytics. Anbieter ist die Google Inc., 1600 Amphitheatre Parkway, Mountain View, CA 94043, USA.</p> <p>Google Analytics verwendet so genannte \"Cookies\". Das sind Textdateien, die auf Ihrem Computer gespeichert werden und die eine Analyse der Benutzung der Website durch Sie ermöglichen. Die durch den Cookie erzeugten Informationen über Ihre Benutzung dieser Website werden in der Regel an einen Server von Google in den USA übertragen und dort gespeichert.</p> <p>Die Speicherung von Google-Analytics-Cookies erfolgt auf Grundlage von Art. 6 Abs. 1 lit. f DSGVO. Der Websitebetreiber hat ein berechtigtes Interesse an der Analyse des Nutzerverhaltens, um sowohl sein Webangebot als auch seine Werbung zu optimieren.</p> <p><strong>IP Anonymisierung</strong></p> <p>Wir haben auf dieser Website die Funktion IP-Anonymisierung aktiviert. Dadurch wird Ihre IP-Adresse von Google innerhalb von Mitgliedstaaten der Europäischen Union oder in anderen Vertragsstaaten des Abkommens über den Europäischen Wirtschaftsraum vor der Übermittlung in die USA gekürzt. Nur in Ausnahmefällen wird die volle IP-Adresse an einen Server von Google in den USA übertragen und dort gekürzt. Im Auftrag des Betreibers dieser Website wird Google diese Informationen benutzen, um Ihre Nutzung der Website auszuwerten, um Reports über die Websiteaktivitäten zusammenzustellen und um weitere mit der Websitenutzung und der Internetnutzung verbundene Dienstleistungen gegenüber dem Websitebetreiber zu erbringen. Die im Rahmen von Google Analytics von Ihrem Browser übermittelte IP-Adresse wird nicht mit anderen Daten von Google zusammengeführt.</p>  <p><strong>Browser Plugin</strong></p> <p>Sie können die Speicherung der Cookies durch eine entsprechende Einstellung Ihrer Browser-Software verhindern; wir weisen Sie jedoch darauf hin, dass Sie in diesem Fall gegebenenfalls nicht sämtliche Funktionen dieser Website vollumfänglich werden nutzen können. Sie können darüber hinaus die Erfassung der durch den Cookie erzeugten und auf Ihre Nutzung der Website bezogenen Daten (inkl. Ihrer IP-Adresse) an Google sowie die Verarbeitung dieser Daten durch Google verhindern, indem Sie das unter dem folgenden Link verfügbare Browser-Plugin herunterladen und installieren: <a href=\"https://tools.google.com/dlpage/gaoptout?hl=de\" target=\"_blank\">https://tools.google.com/dlpage/gaoptout?hl=de</a>.</p> <p><strong>Widerspruch gegen Datenerfassung</strong></p> <p>Sie können die Erfassung Ihrer Daten durch Google Analytics verhindern, indem Sie auf folgenden Link klicken. Es wird ein Opt-Out-Cookie gesetzt, der die Erfassung Ihrer Daten bei zukünftigen Besuchen dieser Website verhindert: <a href=\"javascript:gaOptout();\">Google Analytics deaktivieren</a>.</p> <p>Mehr Informationen zum Umgang mit Nutzerdaten bei Google Analytics finden Sie in der Datenschutzerklärung von Google: <a href=\"https://support.google.com/analytics/answer/6004245?hl=de\" target=\"_blank\">https://support.google.com/analytics/answer/6004245?hl=de</a>.</p><p><strong>Auftragsdatenverarbeitung</strong></p> <p>Wir haben mit Google einen Vertrag zur Auftragsdatenverarbeitung abgeschlossen und setzen die strengen Vorgaben der deutschen Datenschutzbehörden bei der Nutzung von Google Analytics vollständig um.</p> <p><strong>Demografische Merkmale bei Google Analytics</strong></p> <p>Diese Website nutzt die Funktion “demografische Merkmale” von Google Analytics. Dadurch können Berichte erstellt werden, die Aussagen zu Alter, Geschlecht und Interessen der Seitenbesucher enthalten. Diese Daten stammen aus interessenbezogener Werbung von Google sowie aus Besucherdaten von Drittanbietern. Diese Daten können keiner bestimmten Person zugeordnet werden. Sie können diese Funktion jederzeit über die Anzeigeneinstellungen in Ihrem Google-Konto deaktivieren oder die Erfassung Ihrer Daten durch Google Analytics wie im Punkt “Widerspruch gegen Datenerfassung” dargestellt generell untersagen.</p>  <h3>etracker</h3> <p>Unsere Website nutzt den Analysedienst etracker. Anbieter ist die etracker GmbH, Erste Brunnenstraße 1, 20459 Hamburg, Deutschland. Aus den Daten können unter einem Pseudonym Nutzungsprofile erstellt werden. Dazu können Cookies eingesetzt werden. Bei Cookies handelt es sich um kleine Textdateien, die lokal im Zwischenspeicher Ihres Internet-Browsers gespeichert werden. Die Cookies ermöglichen es, Ihren Browser wieder zu erkennen. Die mit den etracker-Technologien erhobenen Daten werden ohne die gesondert erteilte Zustimmung des Betroffenen nicht genutzt, Besucher unserer Website persönlich zu identifizieren und werden nicht mit personenbezogenen Daten über den Träger des Pseudonyms zusammengeführt.</p> <p>etracker-Cookies verbleiben auf Ihrem Endgerät, bis Sie sie löschen.</p> <p>Die Speicherung von etracker-Cookies erfolgt auf Grundlage von Art. 6 Abs. 1 lit. f DSGVO. Der Websitebetreiber hat ein berechtigtes Interesse an der anonymisierten Analyse des Nutzerverhaltens, um sowohl sein Webangebot als auch seine Werbung zu optimieren.</p> <p>Der Datenerhebung und -speicherung können Sie jederzeit mit Wirkung für die Zukunft widersprechen. Um einer Datenerhebung und -speicherung Ihrer Besucherdaten für die Zukunft zu widersprechen, können Sie unter nachfolgendem Link ein Opt-Out-Cookie von etracker beziehen, dieser bewirkt, dass zukünftig keine Besucherdaten Ihres Browsers bei etracker erhoben und gespeichert werden: <a href=\"https://www.etracker.de/privacy?et=V23Jbb\" target=\"_blank\">https://www.etracker.de/privacy?et=V23Jbb</a>.</p> <p>Dadurch wird ein Opt-Out-Cookie mit dem Namen \"cntcookie\" von etracker gesetzt. Bitte löschen Sie diesen Cookie nicht, solange Sie Ihren Widerspruch aufrecht erhalten möchten. Weitere Informationen finden Sie in den Datenschutzbestimmungen von etracker: <a href=\"https://www.etracker.com/de/datenschutz.html\" target=\"_blank\">https://www.etracker.com/de/datenschutz.html</a>.</p> <p><strong>Abschluss eines Vertrags über Auftragsdatenverarbeitung</strong></p> <p>Wir haben mit etracker einen Vertrag zur Auftragsdatenverarbeitung abgeschlossen und setzen die strengen Vorgaben der deutschen Datenschutzbehörden bei der Nutzung von etracker vollständig um.</p> <h3>Matomo (ehemals Piwik)</h3> <p>Diese Website benutzt den Open Source Webanalysedienst Matomo. Matomo verwendet so genannte \"Cookies\". Das sind Textdateien, die auf Ihrem Computer gespeichert werden und die eine Analyse der Benutzung der Website durch Sie ermöglichen. Dazu werden die durch den Cookie erzeugten Informationen über die Benutzung dieser Website auf unserem Server gespeichert. Die IP-Adresse wird vor der Speicherung anonymisiert.</p> <p>Matomo-Cookies verbleiben auf Ihrem Endgerät, bis Sie sie löschen.</p> <p>Die Speicherung von Matomo-Cookies erfolgt auf Grundlage von Art. 6 Abs. 1 lit. f DSGVO. Der Websitebetreiber hat ein berechtigtes Interesse an der anonymisierten Analyse des Nutzerverhaltens, um sowohl sein Webangebot als auch seine Werbung zu optimieren.</p> <p>Die durch den Cookie erzeugten Informationen über die Benutzung dieser Website werden nicht an Dritte weitergegeben. Sie können die Speicherung der Cookies durch eine entsprechende Einstellung Ihrer Browser-Software verhindern; wir weisen Sie jedoch darauf hin, dass Sie in diesem Fall gegebenenfalls nicht sämtliche Funktionen dieser Website vollumfänglich werden nutzen können.</p> <p>Wenn Sie mit der Speicherung und Nutzung Ihrer Daten nicht einverstanden sind, können Sie die Speicherung und Nutzung hier deaktivieren. In diesem Fall wird in Ihrem Browser ein Opt-Out-Cookie hinterlegt, der verhindert, dass Matomo Nutzungsdaten speichert. Wenn Sie Ihre Cookies löschen, hat dies zur Folge, dass auch das Matomo Opt-Out-Cookie gelöscht wird. Das Opt-Out muss bei einem erneuten Besuch unserer Seite wieder aktiviert werden.</p> <p><em><strong><a style=\"color:#F00;\" href=\"https://matomo.org/docs/privacy/\" rel=\"nofollow\" target=\"_blank\">[Hier Matomo iframe-Code einfügen] (Klick für die Anleitung)</a></strong></em></p> <h3>WordPress Stats</h3> <p>Diese Website nutzt das WordPress Tool Stats, um Besucherzugriffe statistisch auszuwerten. Anbieter ist die Automattic Inc., 60 29th Street #343, San Francisco, CA 94110-4929, USA.</p> <p>WordPress Stats verwendet Cookies, die auf Ihrem Computer gespeichert werden und die eine Analyse der Benutzung der Website erlauben. Die durch die Cookies generierten Informationen über die Benutzung unserer Website werden auf Servern in den USA gespeichert. Ihre IP-Adresse wird nach der Verarbeitung und vor der Speicherung anonymisiert.</p> <p>“WordPress-Stats”-Cookies verbleiben auf Ihrem Endgerät, bis Sie sie löschen. </p> <p>Die Speicherung von “WordPress Stats”-Cookies erfolgt auf Grundlage von Art. 6 Abs. 1 lit. f DSGVO. Der Websitebetreiber hat ein berechtigtes Interesse an der anonymisierten Analyse des Nutzerverhaltens, um sowohl sein Webangebot als auch seine Werbung zu optimieren.</p> <p>Sie können Ihren Browser so einstellen, dass Sie über das Setzen von Cookies informiert werden und Cookies nur im Einzelfall erlauben, die Annahme von Cookies für bestimmte Fälle oder generell ausschließen sowie das automatische Löschen der Cookies beim Schließen des Browser aktivieren. Bei der Deaktivierung von Cookies kann die Funktionalität unserer Website eingeschränkt sein. </p> <p>Sie können der Erhebung und Nutzung Ihrer Daten für die Zukunft widersprechen, indem Sie mit einem Klick auf diesen Link einen Opt-Out-Cookie in Ihrem Browser setzen: <a href=\"https://www.quantcast.com/opt-out/\" target=\"_blank\">https://www.quantcast.com/opt-out/</a>.</p> <p>Wenn Sie die Cookies auf Ihrem Rechner löschen, müssen Sie den Opt-Out-Cookie erneut setzen.</p> <h3>Google AdSense</h3> <p>Diese Website benutzt Google AdSense, einen Dienst zum Einbinden von Werbeanzeigen der Google Inc. (\"Google\"). Anbieter ist die Google Inc., 1600 Amphitheatre Parkway, Mountain View, CA 94043, USA.</p> <p>Google AdSense verwendet sogenannte \"Cookies\", Textdateien, die auf Ihrem Computer gespeichert werden und die eine Analyse der Benutzung der Website ermöglichen. Google AdSense verwendet auch so genannte Web Beacons (unsichtbare Grafiken). Durch diese Web Beacons können Informationen wie der Besucherverkehr auf diesen Seiten ausgewertet werden.</p> <p>Die durch Cookies und Web Beacons erzeugten Informationen über die Benutzung dieser Website (einschließlich Ihrer IP-Adresse) und Auslieferung von Werbeformaten werden an einen Server von Google in den USA übertragen und dort gespeichert. Diese Informationen können von Google an Vertragspartner von Google weiter gegeben werden. Google wird Ihre IP-Adresse jedoch nicht mit anderen von Ihnen gespeicherten Daten zusammenführen.</p> <p>Die Speicherung von AdSense-Cookies erfolgt auf Grundlage von Art. 6 Abs. 1 lit. f DSGVO. Der Websitebetreiber hat ein berechtigtes Interesse an der Analyse des Nutzerverhaltens, um sowohl sein Webangebot als auch seine Werbung zu optimieren.</p> <p>Sie können die Installation der Cookies durch eine entsprechende Einstellung Ihrer Browser Software verhindern; wir weisen Sie jedoch darauf hin, dass Sie in diesem Fall gegebenenfalls nicht sämtliche Funktionen dieser Website voll umfänglich nutzen können. Durch die Nutzung dieser Website erklären Sie sich mit der Bearbeitung der über Sie erhobenen Daten durch Google in der zuvor beschriebenen Art und Weise und zu dem zuvor benannten Zweck einverstanden.</p> <h3>Google Analytics Remarketing</h3> <p>Unsere Websites nutzen die Funktionen von Google Analytics Remarketing in Verbindung mit den geräteübergreifenden Funktionen von Google AdWords und Google DoubleClick. Anbieter ist die Google Inc., 1600 Amphitheatre Parkway, Mountain View, CA 94043, USA.</p> <p>Diese Funktion ermöglicht es die mit Google Analytics Remarketing erstellten Werbe-Zielgruppen mit den geräteübergreifenden Funktionen von Google AdWords und Google DoubleClick zu verknüpfen. Auf diese Weise können interessenbezogene, personalisierte Werbebotschaften, die in Abhängigkeit Ihres früheren Nutzungs- und Surfverhaltens auf einem Endgerät (z.B. Handy) an Sie angepasst wurden auch auf einem anderen Ihrer Endgeräte (z.B. Tablet oder PC) angezeigt werden.</p> <p>Haben Sie eine entsprechende Einwilligung erteilt, verknüpft Google zu diesem Zweck Ihren Web- und App-Browserverlauf mit Ihrem Google-Konto. Auf diese Weise können auf jedem Endgerät auf dem Sie sich mit Ihrem Google-Konto anmelden, dieselben personalisierten Werbebotschaften geschaltet werden.</p> <p>Zur Unterstützung dieser Funktion erfasst Google Analytics google-authentifizierte IDs der Nutzer, die vorübergehend mit unseren Google-Analytics-Daten verknüpft werden, um Zielgruppen für die geräteübergreifende Anzeigenwerbung zu definieren und zu erstellen.</p> <p>Sie können dem geräteübergreifenden Remarketing/Targeting dauerhaft widersprechen, indem Sie personalisierte Werbung in Ihrem Google-Konto deaktivieren; folgen Sie hierzu diesem Link: <a href=\"https://www.google.com/settings/ads/onweb/\" target=\"_blank\">https://www.google.com/settings/ads/onweb/</a>.</p> <p>Die Zusammenfassung der erfassten Daten in Ihrem Google-Konto erfolgt ausschließlich auf Grundlage Ihrer Einwilligung, die Sie bei Google abgeben oder widerrufen können (Art. 6 Abs. 1 lit. a DSGVO). Bei Datenerfassungsvorgängen, die nicht in Ihrem Google-Konto zusammengeführt werden (z.B. weil Sie kein Google-Konto haben oder der Zusammenführung widersprochen haben) beruht die Erfassung der Daten auf Art. 6 Abs. 1 lit. f DSGVO. Das berechtigte Interesse ergibt sich daraus, dass der Websitebetreiber ein Interesse an der anonymisierten Analyse der Websitebesucher zu Werbezwecken hat.</p> <p>Weitergehende Informationen und die Datenschutzbestimmungen finden Sie in der Datenschutzerklärung von Google unter: <a href=\"https://www.google.com/policies/technologies/ads/\" target=\"_blank\">https://www.google.com/policies/technologies/ads/</a>.</p> <h3>Google AdWords und Google Conversion-Tracking</h3> <p>Diese Website verwendet Google AdWords. AdWords ist ein Online-Werbeprogramm der Google Inc., 1600 Amphitheatre Parkway, Mountain View, CA 94043, United States (“Google”).</p> <p>Im Rahmen von Google AdWords nutzen wir das so genannte Conversion-Tracking. Wenn Sie auf eine von Google geschaltete Anzeige klicken wird ein Cookie für das Conversion-Tracking gesetzt. Bei Cookies handelt es sich um kleine Textdateien, die der Internet-Browser auf dem Computer des Nutzers ablegt. Diese Cookies verlieren nach 30 Tagen ihre Gültigkeit und dienen nicht der persönlichen Identifizierung der Nutzer. Besucht der Nutzer bestimmte Seiten dieser Website und das Cookie ist noch nicht abgelaufen, können Google und wir erkennen, dass der Nutzer auf die Anzeige geklickt hat und zu dieser Seite weitergeleitet wurde.</p> <p>Jeder Google AdWords-Kunde erhält ein anderes Cookie. Die Cookies können nicht über die Websites von AdWords-Kunden nachverfolgt werden. Die mithilfe des Conversion-Cookies eingeholten Informationen dienen dazu, Conversion-Statistiken für AdWords-Kunden zu erstellen, die sich für Conversion-Tracking entschieden haben. Die Kunden erfahren die Gesamtanzahl der Nutzer, die auf ihre Anzeige geklickt haben und zu einer mit einem Conversion-Tracking-Tag versehenen Seite weitergeleitet wurden. Sie erhalten jedoch keine Informationen, mit denen sich Nutzer persönlich identifizieren lassen. Wenn Sie nicht am Tracking teilnehmen möchten, können Sie dieser Nutzung widersprechen, indem Sie das Cookie des Google Conversion-Trackings über ihren Internet-Browser unter Nutzereinstellungen leicht deaktivieren. Sie werden sodann nicht in die Conversion-Tracking Statistiken aufgenommen.</p> <p>Die Speicherung von “Conversion-Cookies” erfolgt auf Grundlage von Art. 6 Abs. 1 lit. f DSGVO. Der Websitebetreiber hat ein berechtigtes Interesse an der Analyse des Nutzerverhaltens, um sowohl sein Webangebot als auch seine Werbung zu optimieren.</p> <p>Mehr Informationen zu Google AdWords und Google Conversion-Tracking finden Sie in den Datenschutzbestimmungen von Google: <a href=\"https://www.google.de/policies/privacy/\" target=\"_blank\">https://www.google.de/policies/privacy/</a>.</p> <p>Sie können Ihren Browser so einstellen, dass Sie über das Setzen von Cookies informiert werden und Cookies nur im Einzelfall erlauben, die Annahme von Cookies für bestimmte Fälle oder generell ausschließen sowie das automatische Löschen der Cookies beim Schließen des Browser aktivieren. Bei der Deaktivierung von Cookies kann die Funktionalität dieser Website eingeschränkt sein.</p> <h3>Google reCAPTCHA</h3> <p>Wir nutzen “Google reCAPTCHA” (im Folgenden “reCAPTCHA”) auf unseren Websites. Anbieter ist die Google Inc., 1600 Amphitheatre Parkway, Mountain View, CA 94043, USA (“Google”).</p> <p>Mit reCAPTCHA soll überprüft werden, ob die Dateneingabe auf unseren Websites (z.B. in einem Kontaktformular) durch einen Menschen oder durch ein automatisiertes Programm erfolgt. Hierzu analysiert reCAPTCHA das Verhalten des Websitebesuchers anhand verschiedener Merkmale. Diese Analyse beginnt automatisch, sobald der Websitebesucher die Website betritt. Zur Analyse wertet reCAPTCHA verschiedene Informationen aus (z.B. IP-Adresse, Verweildauer des Websitebesuchers auf der Website oder vom Nutzer getätigte Mausbewegungen). Die bei der Analyse erfassten Daten werden an Google weitergeleitet.</p> <p>Die reCAPTCHA-Analysen laufen vollständig im Hintergrund. Websitebesucher werden nicht darauf hingewiesen, dass eine Analyse stattfindet.</p> <p>Die Datenverarbeitung erfolgt auf Grundlage von Art. 6 Abs. 1 lit. f DSGVO. Der Websitebetreiber hat ein berechtigtes Interesse daran, seine Webangebote vor missbräuchlicher automatisierter Ausspähung und vor SPAM zu schützen.</p> <p>Weitere Informationen zu Google reCAPTCHA sowie die Datenschutzerklärung von Google entnehmen Sie folgenden Links: <a href=\"https://www.google.com/intl/de/policies/privacy/\" target=\"_blank\">https://www.google.com/intl/de/policies/privacy/</a> und <a href=\"https://www.google.com/recaptcha/intro/android.html\" target=\"_blank\">https://www.google.com/recaptcha/intro/android.html</a>.</p> <h3>Facebook Pixel</h3> <p>Unsere Website nutzt zur Konversionsmessung das Besucheraktions-Pixel von Facebook, Facebook Inc., 1601 S. California Ave, Palo Alto, CA 94304, USA (“Facebook”).</p> <p>So kann das Verhalten der Seitenbesucher nachverfolgt werden, nachdem diese durch Klick auf eine Facebook-Werbeanzeige auf die Website des Anbieters weitergeleitet wurden. Dadurch können die Wirksamkeit der Facebook-Werbeanzeigen für statistische und Marktforschungszwecke ausgewertet werden und zukünftige Werbemaßnahmen optimiert werden.</p> <p>Die erhobenen Daten sind für uns als Betreiber dieser Website anonym, wir können keine Rückschlüsse auf die Identität der Nutzer ziehen. Die Daten werden aber von Facebook gespeichert und verarbeitet, sodass eine Verbindung zum jeweiligen Nutzerprofil möglich ist und Facebook die Daten für eigene Werbezwecke, entsprechend der <a href=\"https://www.facebook.com/about/privacy/\" target=\"_blank\">Facebook-Datenverwendungsrichtlinie</a> verwenden kann. Dadurch kann Facebook das Schalten von Werbeanzeigen auf Seiten von Facebook sowie außerhalb von Facebook ermöglichen. Diese Verwendung der Daten kann von uns als Seitenbetreiber nicht beeinflusst werden.</p> <p>In den Datenschutzhinweisen von Facebook finden Sie weitere Hinweise zum Schutz Ihrer Privatsphäre: <a href=\"https://www.facebook.com/about/privacy/\" target=\"_blank\">https://www.facebook.com/about/privacy/</a>.</p> <p>Sie können außerdem die Remarketing-Funktion “Custom Audiences” im Bereich Einstellungen für Werbeanzeigen unter <a href=\"https://www.facebook.com/ads/preferences/?entry_product=ad_settings_screen\" target=\"_blank\">https://www.facebook.com/ads/preferences/?entry_product=ad_settings_screen</a> deaktivieren. Dazu müssen Sie bei Facebook angemeldet sein.</p> <p>Wenn Sie kein Facebook Konto besitzen, können Sie nutzungsbasierte Werbung von Facebook auf der Website der European Interactive Digital Advertising Alliance deaktivieren: <a href=\"http://www.youronlinechoices.com/de/praferenzmanagement/\" target=\"_blank\">http://www.youronlinechoices.com/de/praferenzmanagement/</a>.</p>", 0),
                ("Newsletter", "eRecht24", "https://www.e-recht24.de", "<h3>Newsletterdaten</h3> <p>Wenn Sie den auf der Website angebotenen Newsletter beziehen möchten, benötigen wir von Ihnen eine E-Mail-Adresse sowie Informationen, welche uns die Überprüfung gestatten, dass Sie der Inhaber der angegebenen E-Mail-Adresse sind und mit dem Empfang des Newsletters einverstanden sind. Weitere Daten werden nicht bzw. nur auf freiwilliger Basis erhoben. Diese Daten verwenden wir ausschließlich für den Versand der angeforderten Informationen und geben diese nicht an Dritte weiter.</p> <p>Die Verarbeitung der in das Newsletteranmeldeformular eingegebenen Daten erfolgt ausschließlich auf Grundlage Ihrer Einwilligung (Art. 6 Abs. 1 lit. a DSGVO). Die erteilte Einwilligung zur Speicherung der Daten, der E-Mail-Adresse sowie deren Nutzung zum Versand des Newsletters können Sie jederzeit widerrufen, etwa über den \"Austragen\"-Link im Newsletter. Die Rechtmäßigkeit der bereits erfolgten Datenverarbeitungsvorgänge bleibt vom Widerruf unberührt.</p> <p>Die von Ihnen zum Zwecke des Newsletter-Bezugs bei uns hinterlegten Daten werden von uns bis zu Ihrer Austragung aus dem Newsletter gespeichert und nach der Abbestellung des Newsletters gelöscht. Daten, die zu anderen Zwecken bei uns gespeichert wurden (z.B. E-Mail-Adressen für den Mitgliederbereich) bleiben hiervon unberührt.</p>", 0),
                ("Plugins und Tools", "eRecht24", "https://www.e-recht24.de", "<h3>YouTube</h3> <p>Unsere Website nutzt Plugins der von Google betriebenen Seite YouTube. Betreiber der Seiten ist die YouTube, LLC, 901 Cherry Ave., San Bruno, CA 94066, USA.</p> <p>Wenn Sie eine unserer mit einem YouTube-Plugin ausgestatteten Seiten besuchen, wird eine Verbindung zu den Servern von YouTube hergestellt. Dabei wird dem YouTube-Server mitgeteilt, welche unserer Seiten Sie besucht haben.</p> <p>Wenn Sie in Ihrem YouTube-Account eingeloggt sind, ermöglichen Sie YouTube, Ihr Surfverhalten direkt Ihrem persönlichen Profil zuzuordnen. Dies können Sie verhindern, indem Sie sich aus Ihrem YouTube-Account ausloggen.</p> <p>Die Nutzung von YouTube erfolgt im Interesse einer ansprechenden Darstellung unserer Online-Angebote. Dies stellt ein berechtigtes Interesse im Sinne von Art. 6 Abs. 1 lit. f DSGVO dar.</p> <p>Weitere Informationen zum Umgang mit Nutzerdaten finden Sie in der Datenschutzerklärung von YouTube unter: <a href=\"https://www.google.de/intl/de/policies/privacy\" target=\"_blank\">https://www.google.de/intl/de/policies/privacy</a>.</p> <h3>Vimeo</h3> <p>Unsere Website nutzt Plugins des Videoportals Vimeo. Anbieter ist die Vimeo Inc., 555 West 18th Street, New York, New York 10011, USA.</p> <p>Wenn Sie eine unserer mit einem Vimeo-Plugin ausgestatteten Seiten besuchen, wird eine Verbindung zu den Servern von Vimeo hergestellt. Dabei wird dem Vimeo-Server mitgeteilt, welche unserer Seiten Sie besucht haben. Zudem erlangt Vimeo Ihre IP-Adresse. Dies gilt auch dann, wenn Sie nicht bei Vimeo eingeloggt sind oder keinen Account bei Vimeo besitzen. Die von Vimeo erfassten Informationen werden an den Vimeo-Server in den USA übermittelt.</p> <p>Wenn Sie in Ihrem Vimeo-Account eingeloggt sind, ermöglichen Sie Vimeo, Ihr Surfverhalten direkt Ihrem persönlichen Profil zuzuordnen. Dies können Sie verhindern, indem Sie sich aus Ihrem Vimeo-Account ausloggen.</p> <p>Weitere Informationen zum Umgang mit Nutzerdaten finden Sie in der Datenschutzerklärung von Vimeo unter: <a href=\"https://vimeo.com/privacy\" target=\"_blank\">https://vimeo.com/privacy</a>.</p> <h3>Google Web Fonts</h3> <p>Diese Seite nutzt zur einheitlichen Darstellung von Schriftarten so genannte Web Fonts, die von Google bereitgestellt werden. Beim Aufruf einer Seite lädt Ihr Browser die benötigten Web Fonts in ihren Browsercache, um Texte und Schriftarten korrekt anzuzeigen.</p> <p>Zu diesem Zweck muss der von Ihnen verwendete Browser Verbindung zu den Servern von Google aufnehmen. Hierdurch erlangt Google Kenntnis darüber, dass über Ihre IP-Adresse unsere Website aufgerufen wurde. Die Nutzung von Google Web Fonts erfolgt im Interesse einer einheitlichen und ansprechenden Darstellung unserer Online-Angebote. Dies stellt ein berechtigtes Interesse im Sinne von Art. 6 Abs. 1 lit. f DSGVO dar.</p> <p>Wenn Ihr Browser Web Fonts nicht unterstützt, wird eine Standardschrift von Ihrem Computer genutzt.</p> <p>Weitere Informationen zu Google Web Fonts finden Sie unter <a href=\"https://developers.google.com/fonts/faq\" target=\"_blank\">https://developers.google.com/fonts/faq</a> und in der Datenschutzerklärung von Google: <a href=\"https://www.google.com/policies/privacy/\" target=\"_blank\">https://www.google.com/policies/privacy/</a>.</p> <h3>Google Maps</h3> <p>Diese Seite nutzt über eine API den Kartendienst Google Maps. Anbieter ist die Google Inc., 1600 Amphitheatre Parkway, Mountain View, CA 94043, USA.</p> <p>Zur Nutzung der Funktionen von Google Maps ist es notwendig, Ihre IP Adresse zu speichern. Diese Informationen werden in der Regel an einen Server von Google in den USA übertragen und dort gespeichert. Der Anbieter dieser Seite hat keinen Einfluss auf diese Datenübertragung.</p> <p>Die Nutzung von Google Maps erfolgt im Interesse einer ansprechenden Darstellung unserer Online-Angebote und an einer leichten Auffindbarkeit der von uns auf der Website angegebenen Orte. Dies stellt ein berechtigtes Interesse im Sinne von Art. 6 Abs. 1 lit. f DSGVO dar.</p> <p>Mehr Informationen zum Umgang mit Nutzerdaten finden Sie in der Datenschutzerklärung von Google: <a href=\"https://www.google.de/intl/de/policies/privacy/\" target=\"_blank\">https://www.google.de/intl/de/policies/privacy/</a>.</p> <h3>SoundCloud</h3> <p>Auf unseren Seiten können Plugins des sozialen Netzwerks SoundCloud (SoundCloud Limited, Berners House, 47-48 Berners Street, London W1T 3NF, Großbritannien.) integriert sein. Die SoundCloud-Plugins erkennen Sie an dem SoundCloud-Logo auf den betroffenen Seiten.</p> <p>Wenn Sie unsere Seiten besuchen, wird nach Aktivierung des Plugin eine direkte Verbindung zwischen Ihrem Browser und dem SoundCloud-Server hergestellt. SoundCloud erhält dadurch die Information, dass Sie mit Ihrer IP-Adresse unsere Seite besucht haben. Wenn Sie den “Like-Button” oder “Share-Button” anklicken während Sie in Ihrem SoundCloud- Benutzerkonto eingeloggt sind, können Sie die Inhalte unserer Seiten mit Ihrem SoundCloud-Profil verlinken und/oder teilen. Dadurch kann SoundCloud Ihrem Benutzerkonto den Besuch unserer Seiten zuordnen. Wir weisen darauf hin, dass wir als Anbieter der Seiten keine Kenntnis vom Inhalt der übermittelten Daten sowie deren Nutzung durch SoundCloud erhalten. Weitere Informationen hierzu finden Sie in der Datenschutzerklärung von SoundCloud unter: <a href=\"https://soundcloud.com/pages/privacy\" target=\"_blank\">https://soundcloud.com/pages/privacy</a>.</p> <p>Wenn Sie nicht wünschen, dass SoundCloud den Besuch unserer Seiten Ihrem SoundCloud- Benutzerkonto zuordnet, loggen Sie sich bitte aus Ihrem SoundCloud-Benutzerkonto aus bevor Sie Inhalte des SoundCloud-Plugins aktivieren.</p> <h3>Spotify</h3> <p>Auf unseren Seiten sind Funktionen des Musik-Dienstes Spotify eingebunden. Anbieter ist die Spotify AB, Birger Jarlsgatan 61, 113 56 Stockholm in Schweden. Die Spotify Plugins erkennen Sie an dem grünen Logo auf unserer Seite. Eine Übersicht über die Spotify-Plugins finden Sie unter: <a href=\"https://developer.spotify.com\" target=\"_blank\">https://developer.spotify.com</a>.</p> <p>Dadurch kann beim Besuch unserer Seiten über das Plugin eine direkte Verbindung zwischen Ihrem Browser und dem Spotify-Server hergestellt werden. Spotify erhält dadurch die Information, dass Sie mit Ihrer IP-Adresse unsere Seite besucht haben. Wenn Sie den Spotify Button anklicken während Sie in Ihrem Spotify-Account eingeloggt sind, können Sie die Inhalte unserer Seiten auf Ihrem Spotify Profil verlinken. Dadurch kann Spotify den Besuch unserer Seiten Ihrem Benutzerkonto zuordnen.</p> <p>Weitere Informationen hierzu finden Sie in der Datenschutzerklärung von Spotify: <a href=\"https://www.spotify.com/de/legal/privacy-policy/\" target=\"_blank\">https://www.spotify.com/de/legal/privacy-policy/</a>.</p> <p>Wenn Sie nicht wünschen, dass Spotify den Besuch unserer Seiten Ihrem Spotify-Nutzerkonto zuordnen kann, loggen Sie sich bitte aus Ihrem Spotify-Benutzerkonto aus.</p>", 0),
                ("Online Marketing und Partnerprogramme", "eRecht24", "https://www.e-recht24.de", "<h3>Amazon Partnerprogramm</h3> <p>Die Betreiber der Seiten nehmen am Amazon EU- Partnerprogramm teil. Auf unseren Seiten werden durch Amazon Werbeanzeigen und Links zur Seite von Amazon.de eingebunden, an denen wir über Werbekostenerstattung Geld verdienen können. Amazon setzt dazu Cookies ein, um die Herkunft der Bestellungen nachvollziehen zu können. Dadurch kann Amazon erkennen, dass Sie den Partnerlink auf unserer Website geklickt haben.</p> <p>Die Speicherung von “Amazon-Cookies” erfolgt auf Grundlage von Art. 6 lit. f DSGVO. Der Websitebetreiber hat hieran ein berechtigtes Interesse, da nur durch die Cookies die Höhe seiner Affiliate-Vergütung feststellbar ist.</p> <p>Weitere Informationen zur Datennutzung durch Amazon erhalten Sie in der Datenschutzerklärung von Amazon: <a href=\"https://www.amazon.de/gp/help/customer/display.html/ref=footer_privacy?ie=UTF8&nodeId=3312401\" target=\"_blank\">https://www.amazon.de/gp/help/customer/display.html/ref=footer_privacy?ie=UTF8&nodeId=3312401</a>.</p>", 0),
                ("Zahlungsanbieter", "eRecht24", "https://www.e-recht24.de", "<h3>PayPal</h3> <p>Auf unserer Website bieten wir u.a. die Bezahlung via PayPal an. Anbieter dieses Zahlungsdienstes ist die PayPal (Europe) S.à.r.l. et Cie, S.C.A., 22-24 Boulevard Royal, L-2449 Luxembourg (im Folgenden “PayPal”).</p> <p>Wenn Sie die Bezahlung via PayPal auswählen, werden die von Ihnen eingegebenen Zahlungsdaten an PayPal übermittelt.</p> <p>Die Übermittlung Ihrer Daten an PayPal erfolgt auf Grundlage von Art. 6 Abs. 1 lit. a DSGVO (Einwilligung) und Art. 6 Abs. 1 lit. b DSGVO (Verarbeitung zur Erfüllung eines Vertrags). Sie haben die Möglichkeit, Ihre Einwilligung zur Datenverarbeitung jederzeit zu widerrufen. Ein Widerruf wirkt sich auf die Wirksamkeit von in der Vergangenheit liegenden Datenverarbeitungsvorgängen nicht aus.</p> <h3>Klarna</h3> <p>Auf unserer Website bieten wir u.a. die Bezahlung mit den Diensten von Klarna an. Anbieter ist die Klarna AB, Sveavägen 46, 111 34 Stockholm, Schweden (im Folgenden “Klarna”).</p> <p>Klarna bietet verschiedene Zahlungsoptionen an (z.B. Ratenkauf). Wenn Sie sich für die Bezahlung mit Klarna entscheiden (Klarna-Checkout-Lösung), wird Klarna verschiedene personenbezogene Daten von Ihnen erheben. Details hierzu können Sie in der Datenschutzerklärung von Klarna unter folgendem Link nachlesen: <a href=\"https://www.klarna.com/de/datenschutz/\" target=\"_blank\">https://www.klarna.com/de/datenschutz/</a>.</p> <p>Klarna nutzt Cookies, um die Verwendung der Klarna-Checkout-Lösung zu optimieren. Die Optimierung der Checkout-Lösung stellt ein berechtigtes Interesse im Sinne von Art. 6 Abs. 1 lit. f DSGVO dar. Cookies sind kleine Textdateien, die auf Ihrem Endgerät gespeichert werden und keinen Schaden anrichten. Sie verbleiben auf Ihrem Endgerät bis Sie sie löschen. Details zum Einsatz von Klarna-Cookies entnehmen Sie folgendem Link: <a href=\"https://cdn.klarna.com/1.0/shared/content/policy/cookie/de_de/checkout.pdf\" target=\"_blank\">https://cdn.klarna.com/1.0/shared/content/policy/cookie/de_de/checkout.pdf</a>.</p> <p>Die Übermittlung Ihrer Daten an Klarna erfolgt auf Grundlage von Art. 6 Abs. 1 lit. a DSGVO (Einwilligung) und Art. 6 Abs. 1 lit. b DSGVO (Verarbeitung zur Erfüllung eines Vertrags). Sie haben die Möglichkeit, Ihre Einwilligung zur Datenverarbeitung jederzeit zu widerrufen. Ein Widerruf wirkt sich auf die Wirksamkeit von in der Vergangenheit liegenden Datenverarbeitungsvorgängen nicht aus.</p> <h3>Sofortüberweisung</h3> <p>Auf unserer Website bieten wir u.a. die Bezahlung mittels “Sofortüberweisung” an. Anbieter dieses Zahlungsdienstes ist die Sofort GmbH, Theresienhöhe 12, 80339 München (im Folgenden “Sofort GmbH”).</p> <p>Mit Hilfe des Verfahrens “Sofortüberweisung” erhalten wir in Echtzeit eine Zahlungsbestätigung von der Sofort GmbH und können unverzüglich mit der Erfüllung unserer Verbindlichkeiten beginnen.</p> <p>Wenn Sie sich für die Zahlungsart “Sofortüberweisung” entschieden haben, übermitteln Sie die PIN und eine gültige TAN an die Sofort GmbH, mit der diese sich in Ihr Online-Banking-Konto einloggen kann. Sofort GmbH überprüft nach dem Einloggen automatisch Ihren Kontostand und führt die Überweisung an uns mit Hilfe der von Ihnen übermittelten TAN durch. Anschließend übermittelt sie uns unverzüglich eine Transaktionsbestätigung. Nach dem Einloggen werden außerdem Ihre Umsätze, der Kreditrahmen des Dispokredits und das Vorhandensein anderer Konten sowie deren Bestände automatisiert geprüft.</p> <p>Neben der PIN und der TAN werden auch die von Ihnen eingegebenen Zahlungsdaten sowie Daten zu Ihrer Person an die Sofort GmbH übermittelt. Bei den Daten zu Ihrer Person handelt es sich um Vor- und Nachname, Adresse, Telefonnummer(n), Email-Adresse, IP-Adresse und ggf. weitere zur Zahlungsabwicklung erforderliche Daten. Die Übermittlung dieser Daten ist notwendig, um Ihre Identität zweifelsfrei zu festzustellen und Betrugsversuchen vorzubeugen.</p> <p>Die Übermittlung Ihrer Daten an die Sofort GmbH erfolgt auf Grundlage von Art. 6 Abs. 1 lit. a DSGVO (Einwilligung) und Art. 6 Abs. 1 lit. b DSGVO (Verarbeitung zur Erfüllung eines Vertrags). Sie haben die Möglichkeit, Ihre Einwilligung zur Datenverarbeitung jederzeit zu widerrufen. Ein Widerruf wirkt sich auf die Wirksamkeit von in der Vergangenheit liegenden Datenverarbeitungsvorgängen nicht aus.</p> <p>Details zur Zahlung mit Sofortüberweisung entnehmen Sie folgenden Links: <a href=\"https://www.sofort.de/datenschutz.html\" target=\"_blank\">https://www.sofort.de/datenschutz.html</a> und <a href=\"https://www.klarna.com/sofort/\" target=\"_blank\">https://www.klarna.com/sofort/</a>.</p>", 0);');
                break;
            case "2.1.9":
                // New installs of 2.1.8 and 2.1.9 still created the old statistic settings and not the new "statistic_visibleStats".
                // Check if new statistic setting is existing. If not create the new one and delete the old ones.
                $databaseConfig = new \Ilch\Config\Database($this->db());
                $visibilitySettings = $databaseConfig->get('statistic_visibleStats');
                if (empty($visibilitySettings)) {
                    $databaseConfig->set('statistic_visibleStats', '1,1,1,1,1,1', 0);
                }
                
                // Remove the no longer needed settings of the statistic module
                $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'statistic_site';
                             DELETE FROM `[prefix]_config` WHERE `key` = 'statistic_visits';
                             DELETE FROM `[prefix]_config` WHERE `key` = 'statistic_browser';
                             DELETE FROM `[prefix]_config` WHERE `key` = 'statistic_os';");

                // Add "locked" column
                $this->db()->query('ALTER TABLE `[prefix]_users` ADD COLUMN `locked` TINYINT(1) NOT NULL DEFAULT 0;');
                break;
            case "2.1.11":
                // restore noavatar.jpg if it is missing due to a previous bug.
                if (file_exists(ROOT_PATH.'/static/img/noavatar.jpg')) {
                    unlink(ROOT_PATH.'/_q2E9CeHhA5cTNKpa/noavatar.jpg');
                } else {
                    rename(ROOT_PATH.'/_q2E9CeHhA5cTNKpa/noavatar.jpg', ROOT_PATH.'/static/img/noavatar.jpg');
                }
                rmdir(ROOT_PATH.'/_q2E9CeHhA5cTNKpa');
                break;
            case "2.1.12":
                mkdir(ROOT_PATH.'/cache');
                break;
            case "2.1.13":
                // Add new needed column "type" for the notifications.
                $this->db()->query('ALTER TABLE `[prefix]_admin_notifications` ADD COLUMN `type` VARCHAR(255) NOT NULL;');

                // Change datatype of the column gender of the users table.
                $this->db()->query('ALTER TABLE `[prefix]_users` MODIFY COLUMN `gender` TINYINT(1) NOT NULL DEFAULT 0;');
                break;
            case "2.1.14":
                set_time_limit(300);
                // Change VARCHAR length for new table character.
                $this->db()->queryMulti('ALTER TABLE `[prefix]_config` MODIFY COLUMN `key` VARCHAR(191) NOT NULL;
                ALTER TABLE `[prefix]_modules` MODIFY COLUMN `key` VARCHAR(191) NOT NULL;
                ALTER TABLE `[prefix]_groups_access` MODIFY COLUMN `module_key` VARCHAR(191) DEFAULT 0;');

                // Convert all core and system module tables to new character and collate
                $this->db()->queryMulti('ALTER TABLE `[prefix]_admin_notifications` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_admin_notifications_permission` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_admin_updateservers` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_articles` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_articles_cats` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_articles_content` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_auth_providers` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_auth_providers_modules` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_auth_tokens` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_backup` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_boxes` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_boxes_content` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_comments` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_config` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_contact_receivers` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_cookie_stolen` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_emails` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_groups` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_groups_access` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_imprint` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_logs` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_media` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_media_cats` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_menu` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_menu_items` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_modules` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_modules_boxes_content` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_modules_content` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_modules_folderrights` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_modules_php_extensions` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_pages` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_pages_content` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_privacy` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_profile_content` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_profile_fields` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_profile_trans` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_users` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_users_auth_providers` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_users_dialog` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_users_dialog_reply` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_users_gallery_imgs` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_users_gallery_items` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_users_groups` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_users_media` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_user_menu` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_user_menu_settings_links` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_visits_online` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                ALTER TABLE `[prefix]_visits_stats` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                break;
        }

        return 'Update function executed.';
    }
}
