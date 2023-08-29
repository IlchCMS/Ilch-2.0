<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Imprint\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'imprint',
        'icon_small' => 'fa-solid fa-paragraph',
        'system_module' => true,
        'languages' => [
            'de_DE' => [
                'name' => 'Impressum',
                'description' => 'Hier kann das Impressum verwaltet werden.',
            ],
            'en_EN' => [
                'name' => 'Imprint',
                'description' => 'Here you can manage your imprint.',
            ],
        ]
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $databaseImpressum = new \Modules\Imprint\Mappers\Imprint();
        $databaseImpressum->set('<h2>Angaben gemäß § 5 TMG:</h2>
<p>Max Mustermann<br />
Musterstraße 111<br />
Gebäude 44<br />
90210 Musterstadt</p>
 
<h2>Kontakt:</h2>
<p>Telefon: +49 (0) 123 44 55 66<br />
Telefax: +49 (0) 123 44 55 99<br />
E-Mail: mustermann@musterfirma.de</p>

<h2>Streitschlichtung</h2>
<p>Die Europäische Kommission stellt eine Plattform zur Online-Streitbeilegung (OS) bereit: <a href="https://ec.europa.eu/consumers/odr" target="_blank" rel="noopener">https://ec.europa.eu/consumers/odr</a>.<br /> Unsere E-Mail-Adresse finden Sie oben im Impressum.</p>

<p>Wir sind nicht bereit oder verpflichtet, an Streitbeilegungsverfahren vor einer Verbraucherschlichtungsstelle teilzunehmen.</p>
 
<h3>Haftung für Inhalte</h3> <p>Als Diensteanbieter sind wir gemäß § 7 Abs.1 TMG für eigene Inhalte auf diesen Seiten nach den allgemeinen Gesetzen verantwortlich. Nach §§ 8 bis 10 TMG sind wir als Diensteanbieter jedoch nicht verpflichtet, übermittelte oder gespeicherte fremde Informationen zu überwachen oder nach Umständen zu forschen, die auf eine rechtswidrige Tätigkeit hinweisen.</p> <p>Verpflichtungen zur Entfernung oder Sperrung der Nutzung von Informationen nach den allgemeinen Gesetzen bleiben hiervon unberührt. Eine diesbezügliche Haftung ist jedoch erst ab dem Zeitpunkt der Kenntnis einer konkreten Rechtsverletzung möglich. Bei Bekanntwerden von entsprechenden Rechtsverletzungen werden wir diese Inhalte umgehend entfernen.</p> <h3>Haftung für Links</h3> <p>Unser Angebot enthält Links zu externen Websites Dritter, auf deren Inhalte wir keinen Einfluss haben. Deshalb können wir für diese fremden Inhalte auch keine Gewähr übernehmen. Für die Inhalte der verlinkten Seiten ist stets der jeweilige Anbieter oder Betreiber der Seiten verantwortlich. Die verlinkten Seiten wurden zum Zeitpunkt der Verlinkung auf mögliche Rechtsverstöße überprüft. Rechtswidrige Inhalte waren zum Zeitpunkt der Verlinkung nicht erkennbar.</p> <p>Eine permanente inhaltliche Kontrolle der verlinkten Seiten ist jedoch ohne konkrete Anhaltspunkte einer Rechtsverletzung nicht zumutbar. Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Links umgehend entfernen.</p> <h3>Urheberrecht</h3> <p>Die durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten unterliegen dem deutschen Urheberrecht. Die Vervielfältigung, Bearbeitung, Verbreitung und jede Art der Verwertung außerhalb der Grenzen des Urheberrechtes bedürfen der schriftlichen Zustimmung des jeweiligen Autors bzw. Erstellers. Downloads und Kopien dieser Seite sind nur für den privaten, nicht kommerziellen Gebrauch gestattet.</p> <p>Soweit die Inhalte auf dieser Seite nicht vom Betreiber erstellt wurden, werden die Urheberrechte Dritter beachtet. Insbesondere werden Inhalte Dritter als solche gekennzeichnet. Sollten Sie trotzdem auf eine Urheberrechtsverletzung aufmerksam werden, bitten wir um einen entsprechenden Hinweis. Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Inhalte umgehend entfernen.</p>
', 1);
    }

    public function getInstallSql(): string
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_imprint` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `imprint` MEDIUMTEXT NULL DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

        INSERT INTO `[prefix]_imprint` (`imprint`) VALUES ("");';
    }

    public function getUpdate(string $installedVersion): string
    {
        switch ($installedVersion) {
            case "2.1.52":
                $this->db()->update('modules', ['icon_small' => $this->config['icon_small']], ['key' => $this->config['key']])->execute();
                break;
        }

        return '"' . $this->config['key'] . '" Update-function executed.';
    }
}
