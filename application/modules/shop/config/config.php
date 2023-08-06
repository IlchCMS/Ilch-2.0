<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Config;

use Ilch\Config\Database;
use Ilch\Config\Install;

class Config extends Install
{
    public $config = [
        'key' => 'shop',
        'version' => '1.1.0',
        'icon_small' => 'fa-solid fa-cart-shopping',
        'author' => 'blackcoder & LordSchirmer',
        'link' => 'https://ilch.de',
        'official' => true,
        'languages' => [
            'de_DE' => [
                'name' => 'Shop',
                'description' => 'Hier kann ein kleiner Shop erstellt werden.',
            ],
            'en_EN' => [
                'name' => 'Shop',
                'description' => 'A small shop can be created here.',
            ],
        ],
        'ilchCore' => '2.1.48',
        'phpVersion' => '7.3'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());
        $databaseConfig = new Database($this->db());
        $databaseConfig->set('shop_currency', '1');
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DELETE FROM `[prefix]_config` WHERE `key` = "shop_currency"');

        $this->db()->queryMulti('DROP TABLE `[prefix]_shop_access`;
            DROP TABLE `[prefix]_shop_cats`;
            DROP TABLE `[prefix]_shop_currencies`;
            DROP TABLE `[prefix]_shop_items`;
            DROP TABLE `[prefix]_shop_addresses`;
            DROP TABLE `[prefix]_shop_orderdetails`;
            DROP TABLE `[prefix]_shop_orders`;
            DROP TABLE `[prefix]_shop_customers`;
            DROP TABLE `[prefix]_shop_settings`;');

        $this->db()->queryMulti("DELETE FROM `[prefix]_emails` WHERE `moduleKey` = 'shop';");

        require(ROOT_PATH . '/application/modules/shop/static/class/fpdf/fpdf.php');

        array_map('unlink', glob(ROOT_PATH . '/application/modules/shop/static/invoice/*.pdf'));
    }

    public function getInstallSql(): string
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_shop_cats` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `pos` INT(11) NOT NULL,
                    `title` VARCHAR(100) NOT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_shop_access` (
                    `cat_id` INT(11) NOT NULL,
                    `group_id` INT(11) NOT NULL,
                    PRIMARY KEY (`cat_id`, `group_id`) USING BTREE,
                    INDEX `FK_[prefix]_shop_access_[prefix]_groups` (`group_id`) USING BTREE,
                    CONSTRAINT `FK_[prefix]_shop_access_[prefix]_shop_cats` FOREIGN KEY (`cat_id`) REFERENCES `[prefix]_shop_cats` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE,
                    CONSTRAINT `FK_[prefix]_shop_access_[prefix]_groups` FOREIGN KEY (`group_id`) REFERENCES `[prefix]_groups` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

                CREATE TABLE IF NOT EXISTS `[prefix]_shop_currencies` (
                    `id` INT(14) NOT NULL AUTO_INCREMENT,
                    `name` VARCHAR(255) NOT NULL,
                    `code` CHAR(3) NOT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_shop_items` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `code` VARCHAR(100) NOT NULL,
                    `cat_id` INT(11) NULL DEFAULT 0,
                    `name` VARCHAR(255) NOT NULL,
                    `itemnumber` VARCHAR(255) NOT NULL,
                    `stock` INT(11) NOT NULL,
                    `unitName` VARCHAR(255) NOT NULL,
                    `cordon` INT(11) NULL DEFAULT 0,
                    `cordonText` VARCHAR(255) NOT NULL,
                    `cordonColor` VARCHAR(255) NOT NULL,
                    `price` DOUBLE(9,2) NOT NULL,
                    `tax` INT(11) NOT NULL,
                    `shippingCosts` DOUBLE(9,2) NOT NULL,
                    `shippingTime` INT(11) NOT NULL,
                    `image` VARCHAR(255) NOT NULL,
                    `image1` VARCHAR(255) NOT NULL,
                    `image2` VARCHAR(255) NOT NULL,
                    `image3` VARCHAR(255) NOT NULL,
                    `info` MEDIUMTEXT NOT NULL,
                    `desc` MEDIUMTEXT NOT NULL,
                    `status` INT(1) NULL DEFAULT 0,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_shop_customers` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `userId` INT(11) NOT NULL,
                    `email` VARCHAR(255) NOT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_shop_addresses` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `customerId` INT(11) NOT NULL,
                    `prename` VARCHAR(255) NOT NULL,
                    `lastname` VARCHAR(255) NOT NULL,
                    `street` VARCHAR(255) NOT NULL,
                    `postcode` VARCHAR(255) NOT NULL,
                    `city` VARCHAR(255) NOT NULL,
                    `country` VARCHAR(255) NOT NULL,
                    PRIMARY KEY (`id`) USING BTREE,
                    INDEX `FK_[prefix]_shop_addresses_[prefix]_shop_customers` (`customerId`) USING BTREE,
                    CONSTRAINT `FK_[prefix]_shop_addresses_[prefix]_shop_customers` FOREIGN KEY (`customerId`) REFERENCES `[prefix]_shop_customers` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_shop_orders` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `datetime` DATETIME NOT NULL,
                    `currencyId` INT(11) NOT NULL,
                    `customerId` INT(11) NOT NULL,
                    `invoiceAddressId` INT(11) NOT NULL,
                    `deliveryAddressId` INT(11) NOT NULL,
                    `invoicefilename` VARCHAR(255) NOT NULL,
                    `datetimeInvoiceSent` DATETIME NOT NULL,
                    `willCollect` TINYINT(1) NULL DEFAULT 0,
                    `selector` char(18),
                    `confirmCode` char(64),
                    `status` TINYINT(1) NULL DEFAULT 0,
                    PRIMARY KEY (`id`) USING BTREE,
                    INDEX `FK_[prefix]_shop_orders_[prefix]_shop_customers` (`customerId`) USING BTREE,
                    CONSTRAINT `FK_[prefix]_shop_orders_[prefix]_shop_customers` FOREIGN KEY (`customerId`) REFERENCES `[prefix]_shop_customers` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_shop_orderdetails` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `orderId` INT(11) NOT NULL,
                    `itemId` INT(11) NOT NULL,
                    `price` DOUBLE(9,2) NOT NULL,
                    `quantity` INT(11) NOT NULL,
                    `tax` INT(11) NOT NULL,
                    `shippingCosts` DOUBLE(9,2) NOT NULL,
                    PRIMARY KEY (`id`) USING BTREE,
                    INDEX `FK_[prefix]_shop_orderdetails_[prefix]_shop_orders` (`orderId`) USING BTREE,
                    CONSTRAINT `FK_[prefix]_shop_orderdetails_[prefix]_shop_orders` FOREIGN KEY (`orderId`) REFERENCES `[prefix]_shop_orders` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_shop_settings` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `shopName` VARCHAR(255) NOT NULL,
                    `shopLogo` VARCHAR(255) NOT NULL,
                    `shopStreet` VARCHAR(255) NOT NULL,
                    `shopPlz` VARCHAR(255) NOT NULL,
                    `shopCity` VARCHAR(255) NOT NULL,
                    `shopTel` VARCHAR(255) NOT NULL,
                    `shopFax` VARCHAR(255) NOT NULL,
                    `shopMail` VARCHAR(255) NOT NULL,
                    `shopWeb` VARCHAR(255) NOT NULL,
                    `shopStNr` VARCHAR(255) NOT NULL,
                    `bankName` VARCHAR(255) NOT NULL,
                    `bankOwner` VARCHAR(255) NOT NULL,
                    `bankIBAN` VARCHAR(255) NOT NULL,
                    `bankBIC` VARCHAR(255) NOT NULL,
                    `agb` MEDIUMTEXT NOT NULL,
                    `fixTax` INT(11) NOT NULL,
                    `fixShippingCosts` DOUBLE(9,2) NOT NULL,
                    `fixShippingTime` INT(11) NOT NULL,
                    `allowWillCollect` TINYINT(1) NOT NULL DEFAULT 0,
                    `deliveryTextTop` MEDIUMTEXT NOT NULL,
                    `invoiceTextTop` MEDIUMTEXT NOT NULL,
                    `invoiceTextBottom` MEDIUMTEXT NOT NULL,
                    `paymentClientID` VARCHAR(255) NOT NULL,
                    `paypalMe` VARCHAR(255) NOT NULL,
                    `paypalMePresetAmount` TINYINT NULL DEFAULT 1,
                    `ifSampleData` TINYINT NULL DEFAULT 0,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                INSERT INTO `[prefix]_emails` (`moduleKey`, `type`, `desc`, `text`, `locale`) VALUES
                    ("shop", "order_confirmed_mail", "Bestellung eingegangen", "<p>Sehr geehrte(r) <b>{name}</b>,</p>
                        <p>&nbsp;</p>
                        <p>Ihre Bestellung auf <i>{shopname}</i> ist eingegangen.</p>
                        <p>&nbsp;</p>
                        <p>Mit freundlichen Gr&uuml;&szlig;en</p>
                        <p>Administrator</p>", "de_DE"),
                    ("shop", "order_confirmed_mail", "Order received", "<p>Dear <b>{name}</b>,</p>
                        <p>&nbsp;</p>
                        <p>your order at <i>{shopname}</i> has been received.</p>
                        <p>&nbsp;</p>
                        <p>Best regards</p>
                        <p>Administrator</p>", "en_EN"),
                    ("shop", "send_invoice_mail", "Ihre Rechnung", "<p>Sehr geehrte(r) <b>{name}</b>,</p>
                        <p>&nbsp;</p>
                        <p>hiermit erhalten Sie die Rechnung für Ihre Bestellung auf <i>{shopname}</i>. Die Rechnung befindet sich im Anhang dieser E-Mail.</p>
                        <p>Sie können den folgenden Link nutzen um die Rechnung direkt bequem zu bezahlen:</p>
                        <p>{paymentLink}</p>
                        <p>&nbsp;</p>
                        <p>Mit freundlichen Gr&uuml;&szlig;en</p>
                        <p>Administrator</p>", "de_DE"),
                    ("shop", "send_invoice_mail", "Your invoice", "<p>Dear <b>{name}</b>,</p>
                        <p>&nbsp;</p>
                        <p>hereby you receive the invoice for your order at <i>{shopname}</i>. The invoice is in the attachment of this e-mail.</p>
                        <p>You can use the following link to comfortably pay the invoice:</p>
                        <p>{paymentLink}</p>
                        <p>&nbsp;</p>
                        <p>Best regards</p>
                        <p>Administrator</p>", "en_EN"),
                    ("shop", "send_invoice_mail_no_paymentlink", "Ihre Rechnung", "<p>Sehr geehrte(r) <b>{name}</b>,</p>
                        <p>&nbsp;</p>
                        <p>hiermit erhalten Sie die Rechnung für Ihre Bestellung auf <i>{shopname}</i>. Die Rechnung befindet sich im Anhang dieser E-Mail.</p>
                        <p>&nbsp;</p>
                        <p>Mit freundlichen Gr&uuml;&szlig;en</p>
                        <p>Administrator</p>", "de_DE"),
                    ("shop", "send_invoice_mail_no_paymentlink", "Your invoice", "<p>Dear <b>{name}</b>,</p>
                        <p>&nbsp;</p>
                        <p>hereby you receive the invoice for your order at <i>{shopname}</i>. The invoice is in the attachment of this e-mail.</p>
                        <p>&nbsp;</p>
                        <p>Best regards</p>
                        <p>Administrator</p>", "en_EN");

/***   example entries   ***/

                INSERT INTO `[prefix]_shop_cats`
                    (`id`, `pos`, `title`)
                VALUES
                    (1, 1, "T-Shirts"),
                    (2, 2, "Cappy"),
                    (3, 3, "Taschen");

                INSERT INTO `[prefix]_shop_access`
                    (`cat_id`, `group_id`)
                VALUES
                    (1,1), (1,2), (1,3),
                    (2,1), (2,2), (2,3),
                    (3,1), (3,2), (3,3);

                INSERT INTO `[prefix]_shop_currencies`
                    (`id`, `name`, `code`)
                VALUES
                    (1, "EUR (€)", "EUR"),
                    (2, "USD ($)", "USD"),
                    (3, "GBP (£)", "GBP"),
                    (4, "AUD ($)", "AUD"),
                    (5, "NZD ($)", "NZD"),
                    (6, "CHF", "CHF");

                INSERT INTO `[prefix]_shop_items`
                    (`id`, `code`, `cat_id`, `name`, `itemnumber`, `stock`, `unitname`, `cordon`, `cordonText`, `cordonColor`, `price`, `tax`, `shippingCosts`, `shippingTime`, `image`, `image1`, `image2`, `image3`, `info`, `desc`, `status`)
                VALUES
                    (1, "tshirttotenkopf_1587485020", 1, "T-Shirt Totenkopf", "0815nr1", 14, "St&uuml;ck", 1, "NEU", "green", 25.00, 19, 0.00, 5, "application/modules/media/static/upload/muster-t-shirttotenkopf1.jpg", "application/modules/media/static/upload/muster-t-shirttotenkopf2.jpg", "application/modules/media/static/upload/muster-t-shirttotenkopf3.jpg", "application/modules/media/static/upload/muster-t-shirttotenkopf4.jpg", "<p>Ich bin eine Produktinfo. Hier k&ouml;nnen Sie kurze Details zu Ihrem Produkt wie z. B. Gr&ouml;&szlig;en, Materialien und Lieferzeiten auff&uuml;hren.</p>\r\n", "<p>Ich bin eine Produktbeschreibung. Hier k&ouml;nnen Sie ausf&uuml;hrliche Details zu Ihrem Produkt wie z. B. Gr&ouml;&szlig;en, Materialien, Lieferzeiten und Anleitungen hinzuf&uuml;gen. Beschreiben Sie, was Ihr Produkt besonders macht.</p>\r\n", 1),
                    (2, "tshirttrkies_1587485055", 1, "T-Shirt Türkies", "0815nr2", 23, "St&uuml;ck", 0, "", "", 15.00, 19, 0.00, 5, "application/modules/media/static/upload/muster-t-shirtturkies1.jpg", "application/modules/media/static/upload/muster-t-shirtturkies2.jpg", "application/modules/media/static/upload/muster-t-shirtturkies3.jpg", "", "<p>Ich bin eine Produktinfo. Hier k&ouml;nnen Sie kurze Details zu Ihrem Produkt wie z. B. Gr&ouml;&szlig;en, Materialien und Lieferzeiten und auff&uuml;hren.</p>\r\n", "<p>Ich bin eine Produktbeschreibung. Hier k&ouml;nnen Sie ausf&uuml;hrliche Details zu Ihrem Produkt wie z. B. Gr&ouml;&szlig;en, Materialien, Lieferzeiten und Anleitungen hinzuf&uuml;gen. Beschreiben Sie, was Ihr Produkt besonders macht.</p>\r\n", 1),
                    (3, "tshirtbeach_1587485058", 1, "T-Shirt Beach", "0815nr3", 17, "St&uuml;ck", 0, "", "", 19.50, 19, 0.00, 5, "application/modules/media/static/upload/muster-t-shirtbeach1.jpg", "application/modules/media/static/upload/muster-t-shirtbeach2.jpg", "", "", "<p>Ich bin eine Produktinfo. Hier k&ouml;nnen Sie kurze Details zu Ihrem Produkt wie z. B. Gr&ouml;&szlig;en, Materialien und Lieferzeiten auff&uuml;hren.</p>\r\n", "<p>Ich bin eine Produktbeschreibung. Hier k&ouml;nnen Sie ausf&uuml;hrliche Details zu Ihrem Produkt wie z. B. Gr&ouml;&szlig;en, Materialien, Lieferzeiten und Anleitungen hinzuf&uuml;gen. Beschreiben Sie, was Ihr Produkt besonders macht.</p>\r\n", 1),
                    (4, "rawcaporange_1587485061", 2, "RAW Cap Orange", "0815nr4", 6, "St&uuml;ck", 0, "", "", 24.00, 19, 0.00, 5, "application/modules/media/static/upload/muster_caporange1.jpg", "application/modules/media/static/upload/muster_caporange2.jpg", "", "", "<p>Ich bin eine Produktinfo. Hier k&ouml;nnen Sie kurze Details zu Ihrem Produkt wie z. B. Gr&ouml;&szlig;en, Materialien und Lieferzeiten auff&uuml;hren.</p>\r\n", "<p>Ich bin eine Produktbeschreibung. Hier k&ouml;nnen Sie ausf&uuml;hrliche Details zu Ihrem Produkt wie z. B. Gr&ouml;&szlig;en, Materialien, Lieferzeiten und Anleitungen hinzuf&uuml;gen. Beschreiben Sie, was Ihr Produkt besonders macht.</p>\r\n", 0),
                    (5, "rawcapblack_1587485064", 2, "RAW Cap Black", "0815nr5", 19, "St&uuml;ck", 0, "", "", 25.00, 19, 0.00, 5, "application/modules/media/static/upload/muster_capblack1.jpg", "application/modules/media/static/upload/muster_capblack2.jpg", "", "", "<p>Ich bin eine Produktinfo. Hier k&ouml;nnen Sie kurze Details zu Ihrem Produkt wie z. B. Gr&ouml;&szlig;en, Materialien und Lieferzeiten auff&uuml;hren.</p>\r\n", "<p>Ich bin eine Produktbeschreibung. Hier k&ouml;nnen Sie ausf&uuml;hrliche Details zu Ihrem Produkt wie z. B. Gr&ouml;&szlig;en, Materialien, Lieferzeiten und Anleitungen hinzuf&uuml;gen. Beschreiben Sie, was Ihr Produkt besonders macht.</p>\r\n", 1),
                    (6, "rawcapblau_1587485067", 2, "RAW Cap Blau", "0815nr6", 6, "St&uuml;ck", 0, "", "", 21.00, 19, 0.00, 5, "application/modules/media/static/upload/muster_capblau1.jpg", "application/modules/media/static/upload/muster_capblau2.jpg", "", "", "<p>Ich bin eine Produktinfo. Hier k&ouml;nnen Sie kurze Details zu Ihrem Produkt wie z. B. Gr&ouml;&szlig;en, Materialien und Lieferzeiten auff&uuml;hren.</p>\r\n", "<p>Ich bin eine Produktbeschreibung. Hier k&ouml;nnen Sie ausf&uuml;hrliche Details zu Ihrem Produkt wie z. B. Gr&ouml;&szlig;en, Materialien, Lieferzeiten und Anleitungen hinzuf&uuml;gen. Beschreiben Sie, was Ihr Produkt besonders macht.</p>\r\n", 1),
                    (7, "sporttasche_1587485069", 3, "Sporttasche", "0815nr7", 12, "St&uuml;ck", 1, "TOP", "red", 65.90, 19, 0.00, 5, "application/modules/media/static/upload/muster_tasche1.jpg", "application/modules/media/static/upload/muster_tasche2.jpg", "application/modules/media/static/upload/muster_tasche3.jpg", "", "<p>Ich bin eine Produktinfo. Hier k&ouml;nnen Sie kurze Details zu Ihrem Produkt wie z. B. Gr&ouml;&szlig;en, Materialien und Lieferzeiten auff&uuml;hren.</p>\r\n", "<p>Ich bin eine Produktbeschreibung. Hier k&ouml;nnen Sie ausf&uuml;hrliche Details zu Ihrem Produkt wie z. B. Gr&ouml;&szlig;en, Materialien, Lieferzeiten und Anleitungen hinzuf&uuml;gen. Beschreiben Sie, was Ihr Produkt besonders macht.</p>\r\n", 1);

                INSERT INTO `[prefix]_shop_settings`
                    (`id`, `shopName`, `shopLogo`, `shopStreet`, `shopPlz`, `shopCity`, `shopTel`, `shopFax`, `shopMail`, `shopWeb`, `shopStNr`, `bankName`, `bankOwner`, `bankIBAN`, `bankBIC`, `deliveryTextTop`, `invoiceTextTop`, `invoiceTextBottom`, `agb`, `fixTax`, `fixShippingCosts`, `fixShippingTime`, `paymentClientID`, `ifSampleData`)
                VALUES
                    (1, 
                    "ILCH Shop",
                    "application/modules/media/static/upload/ilchShop_logo.jpg",
                    "Shoppingallee 12",
                    "12345",
                    "Shophausen",
                    "+49 (0) 1234 56789",
                    "+49 (0) 1234 98765",
                    "ilch@shop.de",
                    "www.ilch.de",
                    "DE1234567890",
                    "Bankinstitut Shophausen",
                    "Max Mustermann",
                    "DE12123456780000012345",
                    "GENODE99ABC",
                    "Wir bedanken uns für das Vertrauen und liefern Ihnen vereinbarungsgemäß folgende Waren:",
                    "Wir bedanken uns für Ihre Bestellung und stellen Ihnen wunschgemäß folgende Produkte in Rechnung:",
                    "Wir bitten um Zahlung der Gesamtsumme, innerhalb von 14 Tagen ab Rechnungseingang, ohne Abzüge an die unten angegebene Bankverbindung. Vielen Dank für ihr Vertrauen.",
                    "<div class=\"alert alert-warning text-justify\" role=\"alert\">Es handelt sich bei der hier aufgef&uuml;hrten AGB lediglich um ein unverbindliches Muster, das nicht Ihren individuellen Bed&uuml;rfnissen angepasst wurde. Es wird keinerlei Haftung f&uuml;r die Verwendung der Allgemeinen Gesch&auml;ftsbedingungen &uuml;bernommen. Shopbetreiber m&uuml;ssen sich unbedingt um zahlreiche rechtliche Fragen wie AGB, Impressum, Widerruf und einer passenden Datenschutzerkl&auml;rung den eigenen Online Shop k&uuml;mmern.</div><p><strong>&sect; 1 Grundlegende Bestimmungen</strong></p><ol><li>Die nachstehenden Gesch&auml;ftsbedingungen gelten f&uuml;r Vertr&auml;ge die &uuml;ber DOMAINNAME geschlossen werden. Soweit nicht anders vereinbart, wird eigens von Ihnen verwendeten Bedingungen widersprochen.</li><li>Verbraucher im Sinne dieser Regelung ist jede nat&uuml;rliche Person, die ein Rechtsgesch&auml;ft mit privaten Anliegen abschlie&szlig;t. Unternehmer ist jede nat&uuml;rliche oder juristische Person, die bei Abschluss des Rechtsgesch&auml;fts im Sinne ihrer beruflichen oder gewerblichen Interessen handelt.</li></ol><p>&nbsp;</p><p><strong>&sect; 2 Zustandekommen des Vertrages</strong></p><ol><li>Vertragsgegenstand ist der Verkauf von Waren.</li><li>Mit der Bereitstellung eines Produktes in unserem Shop unterbreiten wir unseren Kunden ein verbindliches Angebot zum Abschluss eines Kaufvertrages.</li><li>Alle zum Kauf beabsichtigten Produkte werden vom Kunden im Warenkorb abgelegt. Nach Eingabe der pers&ouml;nlichen Daten und Zahlungsinformationen hat der Kunde die M&ouml;glichkeit alle eingegebenen Informationen zu &uuml;berpr&uuml;fen. Mit dem Absenden der Bestellung durch Klick auf die daf&uuml;r vorgesehene Schaltfl&auml;che erkl&auml;rt der Kunde rechtsverbindlich die Annahme des Angebotes. Damit ist der Kaufvertrag zustande gekommen. Bei der Zahlungs-Option &uuml;ber Payment-Dienstleister wie PayPal oder Sofort&uuml;berweisung, wird der Kunde von unserem unserem Onlineshop auf die Webseite des Anbieters weitergeleitet. Nach Eingabe aller erforderlichen Daten wird der Kunde abschlie&szlig;end zur&uuml;ck in unseren Shop geleitet.</li><li>Die &Uuml;bermittlung aller Informationen im Zusammenhang mit dem Vertragsschluss erfolgt automatisiert per E-Mail. Der Kunde hat daher sicherzustellen, dass die bei uns hinterlegte E-Mail-Adresse erreichbar ist.</li></ol><p>&nbsp;</p><p><strong>&sect; 3 Eigentumsvorbehalt und Zur&uuml;ckbehaltungsrecht</strong></p><ol><li>Ein Zur&uuml;ckbehaltungsrecht kann vom Kunden nur dann ausge&uuml;bt werden, sofern es nicht Forderungen aus selbigem Vertragsverh&auml;ltnis sind.</li><li>Bis zur vollst&auml;ndigen Zahlung des Kaufpreises bleibt die Ware Eigentum des Shop-Betreibers.</li></ol><p>&nbsp;</p><p><strong>&sect; 4 Bestimmungen zur Haftung</strong></p><ol><li>F&uuml;r Sch&auml;den an K&ouml;rper oder der Gesundheit haften wir uneingeschr&auml;nkt, sowie in F&auml;llen des Vorsatzes und grober Fahrl&auml;ssigkeit. Weiterhin bei arglistigem Verschweigen eines Mangels und in allen anderen gesetzlich geregelten F&auml;llen. Die Haftung f&uuml;r M&auml;ngel im Rahmen der gesetzlichen Gew&auml;hrleistung ist der entsprechenden Regelung in unseren Kundeninformationen zu entnehmen.</li><li>Sofern wesentliche Vertragspflichten nicht erf&uuml;llt werden, ist die Haftung des Onlineshops bei leichter Fahrl&auml;ssigkeit auf den vorhersehbaren, vertragstypischen Schaden beschr&auml;nkt.</li><li>Bei der Verletzung unwesentlicher Pflichten die aus dem Vertrag hervorgehen, ist die Haftung bei leicht fahrl&auml;ssigen Pflichtverletzungen ausgeschlossen.</li><li>Es erfolgt keine Haftung f&uuml;r die stetige Verf&uuml;gbarkeit dieser Website und der darauf angebotenen Waren.</li></ol><p>&nbsp;</p><p><strong>&sect; 5 Rechtswahl</strong></p><ol><li>Es gilt deutsches Recht. Die Bestimmungen des UN-Kaufrechts finden ausdr&uuml;cklich keine Anwendung.</li></ol><p>&nbsp;</p><p><strong>&sect; 6 Streitbeilegung</strong></p><ol><li>Die Europ&auml;ische Kommission stellt f&uuml;r die au&szlig;ergerichtliche Online-Streitbeilegung eine Plattform bereit(OS-Plattform), die unter <a href=\"https://ec.europa.eu/odr\" target=\"_blank\">https://ec.europa.eu/odr</a> abrufbar ist.</li></ol><p>&nbsp;</p><p><strong>&sect; 7 Vertragssprache, Vertragstextspeicherung</strong></p><ol><li>Vertragssprache ist deutsch.</li><li>Der vollst&auml;ndige Vertragstext wird von uns nicht gespeichert. Kunden k&ouml;nnen dies vor Absenden der Bestellung &uuml;ber die Druckfunktion des Browsers elektronisch sichern.</li></ol><p>&nbsp;</p><p><strong>&sect; 8 Preise und Zahlungsmodalit&auml;ten Merkmale der Waren</strong></p><ol><li>Die ausgewiesenen Preise sowie die Versandkosten stellen Brutto-Preise dar.</li><li>Versandkosten sind nicht im Kaufpreis enthalten. Sie sind explizit gekennzeichnet oder werden im Laufe des Bestellvorganges gesondert ausgewiesen und sind vom Kunden zus&auml;tzlich zu tragen, soweit nicht eine kostenfreie Lieferung zugesagt ist.</li><li>Die zur Verf&uuml;gung stehenden Zahlungsmethoden sind auf unserer Webseite oder in der jeweiligen Artikelbeschreibung ausgewiesen, sp&auml;testens aber im abschlie&szlig;enden Bestellprozess an der &quot;Kasse&quot; genannt. Soweit nicht anders angegeben, sind die Zahlungsanspr&uuml;che aus dem Vertrag unmittelbar zur Zahlung f&auml;llig.</li><li>Die wesentlichen Merkmale der Ware und/oder Dienstleistung finden sich in der Artikelbeschreibung und den erg&auml;nzenden Angaben auf unserer Internetseite.</li></ol><p>&nbsp;</p><p><strong>&sect; 9 Lieferbedingungen</strong></p><ol><li>Lieferbedingungen,Lieferzeit sowie ggf. bestehende Beschr&auml;nkungen zur Lieferung finden sich unter dem entsprechend bezeichneten Link in unserem Onlineshop oder in der jeweiligen Artikelbeschreibung.</li><li>F&uuml;r Verbraucher gilt, dass die Gefahr des zuf&auml;lligen Untergangs oder der Verschlechterung der verkauften Ware w&auml;hrend der Versendung erst mit der &Uuml;bergabe der Ware an den Kunden &uuml;bergeht. Die Regelung gilt unabh&auml;ngig davon,ob die Versendung versichert oder unversichert erfolgt.</li></ol><p>&nbsp;</p><p><strong>&sect; 10 Gesetzliches M&auml;ngelhaftungsrecht</strong></p><ol><li>Die gesetzlichen M&auml;ngelhaftungsrechte haben bestand.</li><li>Verbraucher werden gebeten, die Ware bei Lieferung auf Vollst&auml;ndigkeit, offensichtliche M&auml;ngel und Transportsch&auml;den zu &uuml;berpr&uuml;fen und dem Shop-Betreiber schnellstm&ouml;glich mitzuteilen. Wird dem nicht vom Kunden nachgekommen hat dies keine Auswirkung auf seine gesetzlichen Gew&auml;hrleistungsanspr&uuml;che.</li></ol><p>&nbsp;</p><p><b>Quelle:</b> Diese AGB und Kundeninformationen f&uuml;r Onlineshops wurden mit der Vorlage von <a href=\"https://website-tutor.com/agb-muster/\" rel=\"nofollow\" target=\"_blank\">Website-Tutor.com</a> erstellt.</p>", 19, 0.00, 7, "", 1);

                INSERT INTO `[prefix]_shop_customers` (`id`, `userId`, `email`) VALUES
                    (1, 1, "max@mustermann.de"),
                    (2, 2, "eva@musterfrau.de"),
                    (3, 3, "bernd@mustermann.de"),
                    (4, 4, "ingrid@musterfrau.de");

                INSERT INTO `[prefix]_shop_addresses` (`id`, `customerId`, `prename`, `lastname`, `street`, `postcode`, `city`, `country`) VALUES
                    (1, 1, "Max", "Mustermann", "Musterstr. 1", 12345, "Musterstadt", "Deutschland"),
                    (2, 2, "Eva", "Musterfrau", "Musterstr. 7", 98765, "Musterhausen", "Deutschland"),
                    (3, 3, "Bernd", "Mustermann", "Musterstr. 13", 56789, "Musterdorf", "Deutschland"),
                    (4, 4, "Ingrid", "Musterfrau", "Musterstr. 7", 34567, "Musterort", "Deutschland");

                INSERT INTO `[prefix]_shop_orders` (`id`, `currencyId`, `customerId`, `invoiceAddressId`, `deliveryAddressId`, `datetime`, `invoicefilename`, `datetimeInvoiceSent`, `selector`, `confirmCode`, `status`) VALUES
                    (1, 1, 1, 1, 1, "2020-04-22 11:47:27", "", "", "", "", 3),
                    (2, 1, 2, 2, 2, "2020-04-25 05:39:12", "", "", "", "", 1),
                    (3, 1, 3, 3, 3, "2020-04-25 11:51:36", "", "", "", "", 1),
                    (4, 1, 4, 4, 4, "2020-04-26 09:54:38", "", "", "", "", 0);

                INSERT INTO `[prefix]_shop_orderdetails` (`id`, `orderId`, `itemId`, `price`, `quantity`, `tax`, `shippingCosts`) VALUES
                    (1, 1, 1, 25.00, 1, 19, 0.00),
                    (2, 1, 5, 25.00, 2, 19, 0.00),
                    (3, 2, 7, 65.90, 2, 19, 0.00),
                    (4, 2, 3, 19.50, 1, 19, 0.00),
                    (5, 3, 4, 24.00, 1, 19, 0.00),
                    (6, 3, 5, 25.00, 2, 19, 0.00),
                    (7, 3, 6, 21.00, 1, 19, 0.00),
                    (8, 4, 7, 65.90, 1, 19, 0.00);

/***   example entries   ***/';
    }

    public function getUpdate(string $installedVersion)
    {
        switch ($installedVersion) {
            case '1.0.0':
                // no break
            case '1.0.1':
                // Add column for "will collect". This means the customer picks up his order and therefore no shipping costs are applied to the invoice.
                $this->db()->query('ALTER TABLE `[prefix]_shop_orders` ADD COLUMN `willCollect` TINYINT(1) NOT NULL DEFAULT 0 AFTER `datetimeInvoiceSent`;');

                // Add column "allowWillCollect" to shop_settings for the setting to enable or disable this feature.
                $this->db()->query('ALTER TABLE `[prefix]_shop_settings` ADD COLUMN `allowWillCollect` TINYINT(1) NOT NULL DEFAULT 0 AFTER `fixShippingTime`;');

                // Change datatype for the status column as it doesn't need to be of type INT for the expected values 0, 1, 2 and 3.
                $this->db()->query('ALTER TABLE `[prefix]_shop_orders` MODIFY COLUMN `status` TINYINT(1) NULL DEFAULT 0;');
                // no break
        }
    }
}
