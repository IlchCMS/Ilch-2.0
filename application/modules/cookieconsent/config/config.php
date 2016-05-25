<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Cookieconsent\Config;

class Config extends \Ilch\Config\Install
{
    public $config =
        [
        'key' => 'cookieconsent',
        'icon_small' => 'fa-paragraph',
        'system_module' => true,
        'languages' =>
            [
            'de_DE' =>
                [
                'name' => 'Cookie-Richtlinien',
                'description' => 'Hier können die Cookie-Richtlinien verwaltet werden.',
                ],
            'en_EN' =>
                [
                'name' => 'Cookie Consent',
                'description' => 'Here you can manage the cookie consent.',
                ],
            ]
        ];

    public function install()
    {
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('cookie_consent', '0');
        $databaseConfig->set('cookie_consent_style', 'light');
        $databaseConfig->set('cookie_consent_pos', 'top');
        $databaseConfig->set('cookie_consent_message', 'Diese Website nutzt Cookies, um bestmögliche Funktionalität bieten zu können.');
        $databaseConfig->set('cookie_consent_text', '<h3>Einsatz von Cookies</h3>
            <p>Wie viele Internetseiten nutzt auch diese Seite Cookies. An dieser Stelle wird erklärt was Cookies sind und wie sie genutzt werden.</p>
            <p>&nbsp;</p>
            <h4>Was sind Cookies</h4>
            <p>Cookies sind kleine Dateien, die beim Aufruf von Internetseiten durch den Internet Browser auf Ihrem lokalen Rechner gespeichert werden. In den Dateien speichern Internetseiten verschiedene Informationen, um die Nutzung von besuchten Internetseiten für Sie komfortabler zu gestalten. Oftmals wird in Cookies z.B. Ihr Login gespeichert, um Sie bei einem späteren Besuch der Internetseite automatisch anzumelden, ohne dass Sie Ihre Zugangsdaten noch einmal manuell eingeben müssen.</p>
            <p>&nbsp;</p>
            <h4>Wie wir Cookies nutzen</h4>
            <p>Wir setzen Cookies für folgende Zwecke ein:</p>
            <ul>
            <li>Anmeldung: Bei der Anmeldung werden Ihre Zugangsdaten in verschlüsselter Form als Cookies gespeichert, um Sie bei einem späteren Seitenaufruf automatisiert anzumelden. Im Anmeldefenster können Sie mit der Option „Dauerhaft angemeldet bleiben“ festlegen, ob diese Cookies angelegt werden sollen.</li>
            <li>Sitzung: Beim ersten Aufruf unserer Seite wird eine neue Sitzung gestartet, diese wird durch ein eindeutiges Cookies Ihrem Computer zugeordnet. Sitzungen erlauben es, Sie zwischen zwei Seitenaufrufen wieder zu erkennen und Ihnen alle Funktionalitäten bereitstellen zu können. Es handelt sich um ein temporäres Cookies, dass beim Beenden des Internet Browsers automatisch gelöscht wird.</li>
            <li>Drittanbieter-Dienste: Die Einblendung von Werbeanzeigen oder das Teilen von Inhalten auf sozialen Netzwerken oder vergleichbaren Internetseiten kann die Erzeugung eines Cookies zur Folge haben. Diese Cookies werden nicht direkt von unserer Seite erzeugt, sondern durch den Drittanbieter selbst.</li>
            </ul>
            <p>&nbsp;</p>
            <h4>Wie Sie Cookies deaktivieren und entfernen</h4>
            <p>Cookies können in den Einstellungen Ihres Internet Browsers verwaltet und entfernt werden. Darüber hinaus lässt sich in den Einstellungen das Speichern von Cookies zudem vollständig deaktivieren. Bitte entnehmen Sie der folgenden Auflistung die passende Anleitung für den Umgang mit Cookies zu dem von Ihnen genutzten Internet Browser.</p>
            <ul>
            <li><a href="https://support.google.com/chrome/answer/95647?hl=de" target="_blank">Google Chrome</a></li>
            <li><a href="https://support.mozilla.org/de/kb/cookies-informationen-websites-auf-ihrem-computer" target="_blank">Mozilla Firefox</a></li>
            <li><a href="http://help.opera.com/Windows/12.00/de/cookies.html" target="_blank">Opera</a></li>
            <li><a href="https://support.apple.com/kb/ph17191?locale=de_DE" target="_blank">Safari</a></li>
            <li><a href="http://windows.microsoft.com/de-DE/internet-explorer/delete-manage-cookies" target="_blank">Windows Internet Explorer</a></li>
            </ul>');
    }
}
