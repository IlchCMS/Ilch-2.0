<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Imprint\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'imprint',
        'icon_small' => 'fa-paragraph',
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
        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('imprint_style', '0');
        
        $databaseImpressum = new \Modules\Imprint\Mappers\Imprint($this->db());
        $databaseImpressum->set('email', $_SESSION['install']['adminEmail'], 1);
        $databaseImpressum->set('disclaimer', '<h4><a name="1">Haftungsausschluss</a></h4>
                                <b>1. Inhalt des Onlineangebotes</b>
                                <br>
                                Der Autor übernimmt keinerlei Gewähr für die Aktualität, Korrektheit, Vollständigkeit oder Qualität der bereitgestellten Informationen. Haftungsansprüche
                                gegen den Autor, welche sich auf Schäden materieller oder ideeller Art beziehen, die durch die Nutzung oder Nichtnutzung der dargebotenen Informationen bzw. durch die Nutzung fehlerhafter und unvollständiger Informationen verursacht wurden, sind grundsätzlich ausgeschlossen, sofern seitens
                                des Autors kein nachweislich vorsätzliches oder grob fahrlässiges Verschulden vorliegt.
                                <br>Alle Angebote sind freibleibend und unverbindlich. Der Autor behält es sich ausdrücklich vor,
                                Teile der Seiten oder das gesamte Angebot ohne gesonderte Ankündigung zu verändern, zu ergänzen, zu löschen oder die Veröffentlichung zeitweise oder endgültig einzustellen.
                                <br><br>
                                <b>2. Verweise und Links</b>
                                <br>
                                Bei direkten oder indirekten Verweisen auf fremde Webseiten ("Hyperlinks"), die außerhalb des Verantwortungsbereiches
                                des Autors liegen, würde eine Haftungsverpflichtung ausschließlich in dem Fall
                                in Kraft treten, in dem der Autor von den Inhalten Kenntnis hat und es ihm technisch möglich und zumutbar wäre, die Nutzung im Falle rechtswidriger Inhalte zu verhindern.
                                <br>
                                Der Autor erklärt hiermit ausdrücklich, dass zum Zeitpunkt der Linksetzung keine illegalen Inhalte auf den zu verlinkenden Seiten erkennbar waren. Auf die aktuelle und zukünftige
                                Gestaltung, die Inhalte oder die Urheberschaft der verlinkten/verknüpften Seiten hat der Autor keinerlei Einfluss. Deshalb distanziert er sich hiermit ausdrücklich von allen Inhalten aller verlinkten
                                /verknüpften Seiten, die nach der Linksetzung verändert wurden. Diese Feststellung gilt für alle innerhalb des eigenen Internetangebotes gesetzten Links und Verweise sowie für Fremdeinträge in vom Autor eingerichteten Gästebüchern, Diskussionsforen, Linkverzeichnissen, Mailinglisten und in allen anderen Formen von Datenbanken, auf deren Inhalt externe Schreibzugriffe möglich sind. Für illegale, fehlerhafte oder unvollständige Inhalte und insbesondere für Schäden, die aus der Nutzung oder Nichtnutzung solcherart dargebotener Informationen entstehen, haftet allein der Anbieter der Seite, auf welche verwiesen wurde, nicht derjenige, der über Links auf die jeweilige Veröffentlichung lediglich verweist.
                                <br><br>
                                <b>3. Urheber- und Kennzeichenrecht</b>
                                <br>
                                Der Autor ist bestrebt, in allen Publikationen die Urheberrechte der verwendeten Bilder, Grafiken, Tondokumente, Videosequenzen und Texte
                                zu beachten, von ihm selbst erstellte Bilder, Grafiken, Tondokumente, Videosequenzen und Texte zu nutzen oder auf lizenzfreie Grafiken, Tondokumente, Videosequenzen und Texte zurückzugreifen.
                                <br>
                                Alle innerhalb des Internetangebotes genannten und ggf. durch Dritte geschützten Marken- und Warenzeichen unterliegen uneingeschränkt den Bestimmungen des jeweils gültigen Kennzeichenrechts und den Besitzrechten der jeweiligen eingetragenen Eigentümer. Allein aufgrund der bloßen Nennung ist nicht der Schluss zu ziehen, dass Markenzeichen nicht durch Rechte Dritter geschützt sind!
                                <br>
                                Das Copyright für veröffentlichte, vom Autor selbst erstellte Objekte bleibt allein beim Autor der Seiten.
                                Eine Vervielfältigung oder Verwendung solcher Grafiken, Tondokumente, Videosequenzen und Texte in anderen elektronischen oder gedruckten Publikationen ist ohne ausdrückliche Zustimmung des Autors nicht gestattet.
                                <br><br>
                                <b>5. Rechtswirksamkeit dieses Haftungsausschlusses </b>
                                <br>
                                Dieser Haftungsausschluss ist als Teil des Internetangebotes zu betrachten, von dem aus auf diese Seite verwiesen wurde. Sofern Teile oder einzelne Formulierungen dieses Textes der geltenden Rechtslage nicht, nicht mehr oder nicht vollständig entsprechen sollten, bleiben die übrigen Teile des Dokumentes in ihrem Inhalt und ihrer Gültigkeit davon unberührt.', 1);
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_imprint` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `paragraph` VARCHAR(100) NULL DEFAULT NULL,
                  `company` VARCHAR(100) NULL DEFAULT NULL,
                  `name` VARCHAR(100) NULL DEFAULT NULL,
                  `address` VARCHAR(255) NULL DEFAULT NULL,
                  `addressadd` VARCHAR(255) NULL DEFAULT NULL,
                  `city` VARCHAR(255) NULL DEFAULT NULL,
                  `phone` VARCHAR(255) NULL DEFAULT NULL,
                  `fax` VARCHAR(255) NULL DEFAULT NULL,
                  `email` VARCHAR(255) NULL DEFAULT NULL,
                  `registration` VARCHAR(255) NULL DEFAULT NULL,
                  `commercialregister` VARCHAR(255) NULL DEFAULT NULL,
                  `vatid` VARCHAR(255) NULL DEFAULT NULL,
                  `other` MEDIUMTEXT NULL DEFAULT NULL,
                  `disclaimer` MEDIUMTEXT NULL DEFAULT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

                INSERT INTO `[prefix]_imprint` (`paragraph`, `name`, `address`, `city`) VALUES
                ("Angaben gemäß § 5 TMG:", "Max Mustermann", "Musterstraße 111", "12345 Musterhausen");';
    }

    public function getUpdate($installedVersion)
    {

    }
}
