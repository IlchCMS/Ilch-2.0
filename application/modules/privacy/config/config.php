<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Privacy\Config;

class Config extends \Ilch\Config\Install
{
    public $config = array
    (
        'key' => 'privacy',
        'icon_small' => 'privacy.png',
        'system_module' => true,
        'languages' => array
        (
            'de_DE' => array
            (
                'name' => 'Datenschutz',
                'description' => 'Hier können die Datenschutzerklärung verwaltet werden.',
            ),
            'en_EN' => array
            (
                'name' => 'Privacy Policy',
                'description' => 'Here you can manage your Privacy Policy.',
            ),
        )
    );

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_privacy` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `title` varchar(255) NOT NULL,
                  `urltitle` varchar(255) NOT NULL,
                  `url` varchar(255) NOT NULL,
                  `text` mediumtext NOT NULL,
                  `show` int(11) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
        
                INSERT INTO `[prefix]_privacy` (`title`, `urltitle`, `url`, `text`, `show`) VALUES
                ("Datenschutz", "e-Recht24", "http://www.e-recht24.de/muster-datenschutzerklaerung.html", "<p>Die Nutzung unserer Webseite ist in der Regel ohne Angabe personenbezogener Daten möglich. Soweit auf unseren Seiten personenbezogene Daten (beispielsweise Name, Anschrift oder E-Mail-Adressen) erhoben werden, erfolgt dies, soweit möglich, stets auf freiwilliger Basis. Diese Daten werden ohne Ihre ausdrückliche Zustimmung nicht an Dritte weitergegeben.</p><p>Wir weisen darauf hin, dass die Datenübertragung im Internet (z.B. bei der Kommunikation per E-Mail) Sicherheitslücken aufweisen kann. Ein lückenloser Schutz der Daten vor dem Zugriff durch Dritte ist nicht möglich.</p><p>Der Nutzung von im Rahmen der Impressumspflicht veröffentlichten Kontaktdaten durch Dritte zur Übersendung von nicht ausdrücklich angeforderter Werbung und Informationsmaterialien wird hiermit ausdrücklich widersprochen. Die Betreiber der Seiten behalten sich ausdrücklich rechtliche Schritte im Falle der unverlangten Zusendung von Werbeinformationen, etwa durch Spam-Mails, vor.</p>", 1);
        
                INSERT INTO `[prefix]_privacy` (`title`, `urltitle`, `url`, `text`, `show`) VALUES
                ("Datenschutzerklärung für die Nutzung von Facebook-Plugins (Like-Button)", "eRecht24 Facebook Datenschutzerklärung", "http://www.e-recht24.de/artikel/datenschutz/6590-facebook-like-button-datenschutz-disclaimer.html", "<p>Auf unseren Seiten sind Plugins des sozialen Netzwerks Facebook, 1601 South California Avenue, Palo Alto, CA 94304, USA integriert. Die Facebook-Plugins erkennen Sie an dem Facebook-Logo oder dem \"Like-Button\" (\"Gefällt mir\") auf unserer Seite. Eine Übersicht über die Facebook-Plugins finden Sie hier: <a href=\"http://developers.facebook.com/docs/plugins/\" target=\"_blank\">http://developers.facebook.com/docs/plugins/</a>.</p><p>Wenn Sie unsere Seiten besuchen, wird über das Plugin eine direkte Verbindung zwischen Ihrem Browser und dem Facebook-Server hergestellt. Facebook erhält dadurch die Information, dass Sie mit Ihrer IP-Adresse unsere Seite besucht haben. Wenn Sie den Facebook \"Like-Button\" anklicken während Sie in Ihrem Facebook-Account eingeloggt sind, können Sie die Inhalte unserer Seiten auf Ihrem Facebook-Profil verlinken. Dadurch kann Facebook den Besuch unserer Seiten Ihrem Benutzerkonto zuordnen.</p><p>Wir weisen darauf hin, dass wir als Anbieter der Seiten keine Kenntnis vom Inhalt der übermittelten Daten sowie deren Nutzung durch Facebook erhalten.<br />Weitere Informationen hierzu finden Sie in der Datenschutzerklärung von facebook unter <a href=\"http://de-de.facebook.com/policy.php\" target=\"_blank\">http://de-de.facebook.com/policy.php</a>.</p><p>Wenn Sie nicht wünschen, dass Facebook den Besuch unserer Seiten Ihrem Facebook-Nutzerkonto zuordnen kann, loggen Sie sich bitte aus Ihrem Facebook-Benutzerkonto aus.</p>", 1);
        
                INSERT INTO `[prefix]_privacy` (`title`, `urltitle`, `url`, `text`, `show`) VALUES
                ("Datenschutzerklärung für die Nutzung von Google +1", "Datenschutzerklärung Google +1", "https://developers.google.com/+/web/buttons-policy", "<b>Erfassung und Weitergabe von Informationen:</b><p>Mithilfe der Google +1-Schaltfläche können Sie Informationen weltweit veröffentlichen. über die Google +1-Schaltfläche erhalten Sie und andere Nutzer personalisierte Inhalte von Google und unseren Partnern. Google speichert sowohl die Information, dass Sie für einen Inhalt +1 gegeben haben, als auch Informationen über die Seite, die Sie beim Klicken auf +1 angesehen haben. Ihre +1 können als Hinweise zusammen mit Ihrem Profilnamen und Ihrem Foto in Google-Diensten, wie etwa in Suchergebnissen oder in Ihrem Google-Profil, oder an anderen Stellen auf Websites und Anzeigen im Internet eingeblendet werden.</p><p>Google zeichnet Informationen über Ihre +1-Aktivitäten auf, um die Google-Dienste für Sie und andere zu verbessern. Um die Google +1-Schaltfläche verwenden zu können, benötigen Sie ein weltweit sichtbares, öffentliches Google-Profil, das zumindest den für das Profil gewählten Namen enthalten muss. Dieser Name wird in allen Google-Diensten verwendet. In manchen Fällen kann dieser Name auch einen anderen Namen ersetzen, den Sie beim Teilen von Inhalten über Ihr Google-Konto verwendet haben. Die Identität Ihres Google-Profils kann Nutzern angezeigt werden, die Ihre E-Mail-Adresse kennen oder über andere identifizierende Informationen von Ihnen verfügen.</p><b>Verwendung der erfassten Informationen:</b><p>Neben den oben erläuterten Verwendungszwecken werden die von Ihnen bereitgestellten Informationen gemäß den geltenden Google-Datenschutzbestimmungen genutzt. Google veröffentlicht möglicherweise zusammengefasste Statistiken über die +1-Aktivitäten der Nutzer bzw. gibt diese an Nutzer und Partner weiter, wie etwa Publisher, Inserenten oder verbundene Websites.</p>", 1);
        
                INSERT INTO `[prefix]_privacy` (`title`, `urltitle`, `url`, `text`, `show`) VALUES
                ("Datenschutzerklärung für die Nutzung von Google Analytics", "Datenschutzerklärung für Google Analytics", "https://support.google.com/analytics/answer/6004245?hl=de", "<p>Diese Website benutzt Google Analytics, einen Webanalysedienst der Google Inc. (\"Google\"). Google Analytics verwendet sog. \"Cookies\", Textdateien, die auf Ihrem Computer gespeichert werden und die eine Analyse der Benutzung der Website durch Sie ermöglichen. Die durch den Cookie erzeugten Informationen über Ihre Benutzung dieser Website werden in der Regel an einen Server von Google in den USA übertragen und dort gespeichert. Im Falle der Aktivierung der IP-Anonymisierung auf dieser Webseite wird Ihre IP-Adresse von Google jedoch innerhalb von Mitgliedstaaten der Europäischen Union oder in anderen Vertragsstaaten des Abkommens über den Europäischen Wirtschaftsraum zuvor gekürzt.</p><p>Nur in Ausnahmefällen wird die volle IP-Adresse an einen Server von Google in den USA übertragen und dort gekürzt. Im Auftrag des Betreibers dieser Website wird Google diese Informationen benutzen, um Ihre Nutzung der Website auszuwerten, um Reports über die Websiteaktivitäten zusammenzustellen und um weitere mit der Websitenutzung und der Internetnutzung verbundene Dienstleistungen gegenüber dem Websitebetreiber zu erbringen. Die im Rahmen von Google Analytics von Ihrem Browser übermittelte IP-Adresse wird nicht mit anderen Daten von Google zusammengeführt.<p><p>Sie können die Speicherung der Cookies durch eine entsprechende Einstellung Ihrer Browser-Software verhindern; wir weisen Sie jedoch darauf hin, dass Sie in diesem Fall gegebenenfalls nicht sämtliche Funktionen dieser Website vollumfänglich werden nutzen können. Sie können darüber hinaus die Erfassung der durch das Cookie erzeugten und auf Ihre Nutzung der Website bezogenen Daten (inkl. Ihrer IP-Adresse) an Google sowie die Verarbeitung dieser Daten durch Google verhindern, indem sie das unter dem folgenden Link verfügbare Browser-Plugin herunterladen und installieren: <a href=\"http://tools.google.com/dlpage/gaoptout?hl=de\" target=\"_blank\">http://tools.google.com/dlpage/gaoptout?hl=de</a>.</p>", 0);
        
                INSERT INTO `[prefix]_privacy` (`title`, `urltitle`, `url`, `text`, `show`) VALUES
                ("Datenschutzerklärung für die Nutzung von Google Adsense", "Datenschutzerklärung für Google Adsense", "http://www.e-recht24.de/artikel/datenschutz/6635-datenschutz-rechtliche-risiken-bei-der-nutzung-von-google-analytics-und-googleadsense.html", "<p>Diese Website benutzt Google AdSense, einen Dienst zum Einbinden von Werbeanzeigen der Google Inc. (\"Google\"). Google AdSense verwendet sog. \"Cookies\", Textdateien, die auf Ihrem Computer gespeichert werden und die eine Analyse der Benutzung der Website ermöglicht. Google AdSense verwendet auch so genannte Web Beacons (unsichtbare Grafiken). Durch diese Web Beacons können Informationen wie der Besucherverkehr auf diesen Seiten ausgewertet werden.</p><p>Die durch Cookies und Web Beacons erzeugten Informationen über die Benutzung dieser Website (einschließlich Ihrer IP-Adresse) und Auslieferung von Werbeformaten werden an einen Server von Google in den USA übertragen und dort gespeichert. Diese Informationen können von Google an Vertragspartner von Google weiter gegeben werden. Google wird Ihre IP-Adresse jedoch nicht mit anderen von Ihnen gespeicherten Daten zusammenführen.</p><p>Sie können die Installation der Cookies durch eine entsprechende Einstellung Ihrer Browser Software verhindern; wir weisen Sie jedoch darauf hin, dass Sie in diesem Fall gegebenenfalls nicht sämtliche Funktionen dieser Website voll umfänglich nutzen können. Durch die Nutzung dieser Website erklären Sie sich mit der Bearbeitung der über Sie erhobenen Daten durch Google in der zuvor beschriebenen Art und Weise und zu dem zuvor benannten Zweck einverstanden.</p>", 1);
        
                INSERT INTO `[prefix]_privacy` (`title`, `urltitle`, `url`, `text`, `show`) VALUES
                ("Datenschutzerklärung für die Nutzung von Twitter", "Datenschutzerklärung für Twitter", "https://twitter.com/privacy?lang=de", "<p>Auf unseren Seiten sind Funktionen des Dienstes Twitter eingebunden. Diese Funktionen werden angeboten durch die Twitter Inc., Twitter, Inc. 1355 Market St, Suite 900, San Francisco, CA 94103, USA. Durch das Benutzen von Twitter und der Funktion \"Re-Tweet\" werden die von Ihnen besuchten Webseiten mit Ihrem Twitter-Account verknüpft und anderen Nutzern bekannt gegeben. Dabei werden auch Daten an Twitter übertragen.</p><p>Wir weisen darauf hin, dass wir als Anbieter der Seiten keine Kenntnis vom Inhalt der übermittelten Daten sowie deren Nutzung durch Twitter erhalten. Weitere Informationen hierzu finden Sie in der Datenschutzerklärung von Twitter unter <a href=\"http://twitter.com/privacy\" target=\"_blank\">http://twitter.com/privacy</a>.</p><p>Ihre Datenschutzeinstellungen bei Twitter können Sie in den Konto-Einstellungen unter <a href=\"http://twitter.com/account/settings\" target=\"_blank\">http://twitter.com/account/settings</a> ändern.</p>", 1);
        
                INSERT INTO `[prefix]_privacy` (`title`, `urltitle`, `url`, `text`, `show`) VALUES
                ("Datenschutzerklärung für die Nutzung von Piwik", "", "http://www.e-recht24.de/muster-datenschutzerklaerung.html", "<p>Diese Webseite nutzt den Open-Source-Webanalysedienst Piwik. Piwik verwendet sog. \"Cookies\", Textdateien, die auf Ihrem Computer gespeichert werden und die eine Analyse der Benutzung der Website durch Sie ermöglicht.</p><p>Auf dieser Webseite  werden die IP-Adressen anonymisiert, so dass kein Rückschluss auf eine Person möglich ist. Die von Piwik erfassten Daten werden nicht und niemals auf andere Server übertragen oder an Dritte weitergegeben, sondern in anonymisierter Form dazu verwendet, unser Angebot zu verbessern. Sie können die Installation der Cookies durch eine entsprechende Einstellung Ihrer Browser Software unterbinden; Sofern Ihr Browser die \"Do-Not-Track\"-Technik unterstützt und Sie diese aktiviert haben, wird ihr Besuch automatisch ignoriert.</p><p>Durch die Nutzung dieser Website erklären Sie sich mit der Verarbeitung der über Sie erhobenen Daten durch Piwik in der zuvor beschriebenen Art und Weise und zu dem zuvor benannten Zweck einverstanden.</p><p>Weitere Informationen zu Piwik finden Sie unter <a href=\"http://piwik.org\" target=\"_blank\">http://piwik.org</a></p>", 0);
        
                INSERT INTO `[prefix]_privacy` (`title`, `urltitle`, `url`, `text`, `show`) VALUES
                ("Auskunft, Löschung, Sperrung", "", "http://www.e-recht24.de/muster-datenschutzerklaerung.html", "<p>Sie haben jederzeit das Recht auf unentgeltliche Auskunft über Ihre gespeicherten personenbezogenen Daten, deren Herkunft und Empfänger und den Zweck der Datenverarbeitung sowie ein Recht auf Berichtigung, Sperrung oder Löschung dieser Daten. Hierzu sowie zu weiteren Fragen zum Thema personenbezogene Daten können Sie sich jederzeit über die im Impressum angegeben Adresse des Webseitenbetreibers an uns wenden.</p>", 0);';
    }
}
