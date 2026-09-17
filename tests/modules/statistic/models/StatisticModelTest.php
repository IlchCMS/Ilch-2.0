<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Statistic\Models;

use PHPUnit\Framework\TestCase;

class StatisticModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new Statistic();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new Statistic();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setUserId() sets and returns the userId.
     */
    public function testSetUserId()
    {
        $model = new Statistic();
        $model->setUserId(7);

        self::assertSame(7, $model->getUserId());
    }

    /**
     * Tests that setUserId() casts to int.
     */
    public function testSetUserIdCastsToInt()
    {
        $model = new Statistic();
        $model->setUserId('99');

        self::assertSame(99, $model->getUserId());
        self::assertIsInt($model->getUserId());
    }

    /**
     * Tests that setSessionId() sets and returns the sessionId.
     */
    public function testSetSessionId()
    {
        $model = new Statistic();
        $model->setSessionId('abc123xyz');

        self::assertSame('abc123xyz', $model->getSessionId());
    }

    /**
     * Tests that setSessionId() casts to string.
     */
    public function testSetSessionIdCastsToString()
    {
        $model = new Statistic();
        $model->setSessionId(12345);

        self::assertSame('12345', $model->getSessionId());
        self::assertIsString($model->getSessionId());
    }

    /**
     * Tests that setVisits() sets and returns the visits.
     */
    public function testSetVisits()
    {
        $model = new Statistic();
        $model->setVisits(150);

        self::assertSame(150, $model->getVisits());
    }

    /**
     * Tests that setVisits() casts to int.
     */
    public function testSetVisitsCastsToInt()
    {
        $model = new Statistic();
        $model->setVisits('200');

        self::assertSame(200, $model->getVisits());
        self::assertIsInt($model->getVisits());
    }

    /**
     * Tests that setSite() sets and returns the site.
     */
    public function testSetSite()
    {
        $model = new Statistic();
        $model->setSite('example.com');

        self::assertSame('example.com', $model->getSite());
    }

    /**
     * Tests that setSite() casts to string.
     */
    public function testSetSiteCastsToString()
    {
        $model = new Statistic();
        $model->setSite(456);

        self::assertSame('456', $model->getSite());
        self::assertIsString($model->getSite());
    }

    /**
     * Tests that setReferer() sets and returns the referer.
     */
    public function testSetReferer()
    {
        $model = new Statistic();
        $model->setReferer('https://google.com/search?q=ilch');

        self::assertSame('https://google.com/search?q=ilch', $model->getReferer());
    }

    /**
     * Tests that setReferer() casts to string.
     */
    public function testSetRefererCastsToString()
    {
        $model = new Statistic();
        $model->setReferer(789);

        self::assertSame('789', $model->getReferer());
        self::assertIsString($model->getReferer());
    }

    /**
     * Tests that setIPAdress() sets and returns the ip address.
     */
    public function testSetIPAdress()
    {
        $model = new Statistic();
        $model->setIPAdress('192.168.1.1');

        self::assertSame('192.168.1.1', $model->getIPAdress());
    }

    /**
     * Tests that setIPAdress() casts to string.
     */
    public function testSetIPAdressCastsToString()
    {
        $model = new Statistic();
        $model->setIPAdress(123456);

        self::assertSame('123456', $model->getIPAdress());
        self::assertIsString($model->getIPAdress());
    }

    /**
     * Tests that setOS() sets and returns the os.
     */
    public function testSetOS()
    {
        $model = new Statistic();
        $model->setOS('Windows');

        self::assertSame('Windows', $model->getOS());
    }

    /**
     * Tests that setOS() casts to string.
     */
    public function testSetOSCastsToString()
    {
        $model = new Statistic();
        $model->setOS(789);

        self::assertSame('789', $model->getOS());
        self::assertIsString($model->getOS());
    }

    /**
     * Tests that setOSVersion() sets and returns the os version.
     */
    public function testSetOSVersion()
    {
        $model = new Statistic();
        $model->setOSVersion('11');

        self::assertSame('11', $model->getOSVersion());
    }

    /**
     * Tests that setOSVersion() casts to string.
     */
    public function testSetOSVersionCastsToString()
    {
        $model = new Statistic();
        $model->setOSVersion(10);

        self::assertSame('10', $model->getOSVersion());
        self::assertIsString($model->getOSVersion());
    }

    /**
     * Tests that setBrowser() sets and returns the browser.
     */
    public function testSetBrowser()
    {
        $model = new Statistic();
        $model->setBrowser('Chrome');

        self::assertSame('Chrome', $model->getBrowser());
    }

    /**
     * Tests that setBrowser() casts to string.
     */
    public function testSetBrowserCastsToString()
    {
        $model = new Statistic();
        $model->setBrowser(123);

        self::assertSame('123', $model->getBrowser());
        self::assertIsString($model->getBrowser());
    }

    /**
     * Tests that setBrowserVersion() sets and returns the browser version.
     */
    public function testSetBrowserVersion()
    {
        $model = new Statistic();
        $model->setBrowserVersion('120.0');

        self::assertSame('120.0', $model->getBrowserVersion());
    }

    /**
     * Tests that setBrowserVersion() casts to string.
     */
    public function testSetBrowserVersionCastsToString()
    {
        $model = new Statistic();
        $model->setBrowserVersion(120);

        self::assertSame('120', $model->getBrowserVersion());
        self::assertIsString($model->getBrowserVersion());
    }

    /**
     * Tests that setLang() sets and returns the lang.
     */
    public function testSetLang()
    {
        $model = new Statistic();
        $model->setLang('en_EN');

        self::assertSame('en_EN', $model->getLang());
    }

    /**
     * Tests that setLang() casts to string.
     */
    public function testSetLangCastsToString()
    {
        $model = new Statistic();
        $model->setLang(456);

        self::assertSame('456', $model->getLang());
        self::assertIsString($model->getLang());
    }

    /**
     * Tests that setDateLastActivity() sets and returns the date last activity.
     */
    public function testSetDateLastActivity()
    {
        $model = new Statistic();
        $model->setDateLastActivity('2024-01-15 10:30:00');

        self::assertSame('2024-01-15 10:30:00', $model->getDateLastActivity());
    }

    /**
     * Tests that setDateLastActivity() casts to string.
     */
    public function testSetDateLastActivityCastsToString()
    {
        $model = new Statistic();
        $model->setDateLastActivity(1705281000);

        self::assertSame('1705281000', $model->getDateLastActivity());
        self::assertIsString($model->getDateLastActivity());
    }

    /**
     * Tests that setDate() sets and returns the date.
     */
    public function testSetDate()
    {
        $model = new Statistic();
        $model->setDate('2024-01-15 14:00:00');

        self::assertSame('2024-01-15 14:00:00', $model->getDate());
    }

    /**
     * Tests that setDate() casts to string.
     */
    public function testSetDateCastsToString()
    {
        $model = new Statistic();
        $model->setDate(1705310400);

        self::assertSame('1705310400', $model->getDate());
        self::assertIsString($model->getDate());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new Statistic();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setUserId(2));
        self::assertSame($model, $model->setSessionId('sess'));
        self::assertSame($model, $model->setVisits(10));
        self::assertSame($model, $model->setSite('site'));
        self::assertSame($model, $model->setReferer('ref'));
        self::assertSame($model, $model->setIPAdress('1.2.3.4'));
        self::assertSame($model, $model->setOS('Linux'));
        self::assertSame($model, $model->setOSVersion('5.15'));
        self::assertSame($model, $model->setBrowser('Firefox'));
        self::assertSame($model, $model->setBrowserVersion('121.0'));
        self::assertSame($model, $model->setLang('de_DE'));
        self::assertSame($model, $model->setDateLastActivity('2024-01-01 00:00:00'));
        self::assertSame($model, $model->setDate('2024-01-01 00:00:00'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new Statistic())
            ->setId(1)
            ->setUserId(5)
            ->setSessionId('session_abc')
            ->setVisits(42)
            ->setSite('mywebsite.com')
            ->setReferer('https://google.com')
            ->setIPAdress('10.0.0.1')
            ->setOS('macOS')
            ->setOSVersion('14.2')
            ->setBrowser('Safari')
            ->setBrowserVersion('17.2')
            ->setLang('en_EN')
            ->setDateLastActivity('2024-06-01 08:00:00')
            ->setDate('2024-06-01 08:00:00');

        self::assertSame(1, $model->getId());
        self::assertSame(5, $model->getUserId());
        self::assertSame('session_abc', $model->getSessionId());
        self::assertSame(42, $model->getVisits());
        self::assertSame('mywebsite.com', $model->getSite());
        self::assertSame('https://google.com', $model->getReferer());
        self::assertSame('10.0.0.1', $model->getIPAdress());
        self::assertSame('macOS', $model->getOS());
        self::assertSame('14.2', $model->getOSVersion());
        self::assertSame('Safari', $model->getBrowser());
        self::assertSame('17.2', $model->getBrowserVersion());
        self::assertSame('en_EN', $model->getLang());
        self::assertSame('2024-06-01 08:00:00', $model->getDateLastActivity());
        self::assertSame('2024-06-01 08:00:00', $model->getDate());
    }

    /**
     * Tests that default values are 0 for int fields and empty string for string fields.
     */
    public function testDefaultValues()
    {
        $model = new Statistic();

        self::assertSame(0, $model->getId());
        self::assertSame(0, $model->getUserId());
        self::assertSame('', $model->getSessionId());
        self::assertSame(0, $model->getVisits());
        self::assertSame('', $model->getSite());
        self::assertSame('', $model->getReferer());
        self::assertSame('', $model->getIPAdress());
        self::assertSame('', $model->getOS());
        self::assertSame('', $model->getOSVersion());
        self::assertSame('', $model->getBrowser());
        self::assertSame('', $model->getBrowserVersion());
        self::assertSame('', $model->getLang());
        self::assertSame('', $model->getDateLastActivity());
        self::assertSame('', $model->getDate());
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new Statistic();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new Statistic();
        $model->setId(1)
            ->setOS('Windows')
            ->setBrowser('Chrome')
            ->setSite('old.com');

        $model->setId(2)
            ->setOS('Linux')
            ->setBrowser('Firefox')
            ->setSite('new.com');

        self::assertSame(2, $model->getId());
        self::assertSame('Linux', $model->getOS());
        self::assertSame('Firefox', $model->getBrowser());
        self::assertSame('new.com', $model->getSite());
    }

    /**
     * Tests setByArray() populates all fields from a database row.
     */
    public function testSetByArray()
    {
        $model = new Statistic();
        $model->setByArray([
            'id'                => 10,
            'user_id'           => 3,
            'session_id'        => 'sess_99',
            'site'              => 'ilch.de',
            'os'                => 'Windows',
            'os_version'        => '11',
            'browser'           => 'Edge',
            'browser_version'   => '125.0',
            'ip_address'        => '172.16.0.1',
            'lang'              => 'de_DE',
            'date_last_activity' => '2024-03-10 12:00:00',
            'referer'           => 'https://bing.com',
            'date'              => '2024-03-10 12:00:00',
        ]);

        self::assertSame(10, $model->getId());
        self::assertSame(3, $model->getUserId());
        self::assertSame('sess_99', $model->getSessionId());
        self::assertSame('ilch.de', $model->getSite());
        self::assertSame('Windows', $model->getOS());
        self::assertSame('11', $model->getOSVersion());
        self::assertSame('Edge', $model->getBrowser());
        self::assertSame('125.0', $model->getBrowserVersion());
        self::assertSame('172.16.0.1', $model->getIPAdress());
        self::assertSame('de_DE', $model->getLang());
        self::assertSame('2024-03-10 12:00:00', $model->getDateLastActivity());
        self::assertSame('https://bing.com', $model->getReferer());
        self::assertSame('2024-03-10 12:00:00', $model->getDate());
    }

    /**
     * Tests setByArray() ignores missing keys (defaults remain).
     */
    public function testSetByArrayMissingKeys()
    {
        $model = new Statistic();
        $model->setByArray([
            'id'   => 1,
            'os'   => 'Linux',
        ]);

        self::assertSame(1, $model->getId());
        self::assertSame('Linux', $model->getOS());
        self::assertSame(0, $model->getUserId());
        self::assertSame('', $model->getSessionId());
        self::assertSame(0, $model->getVisits());
        self::assertSame('', $model->getSite());
        self::assertSame('', $model->getReferer());
        self::assertSame('', $model->getIPAdress());
        self::assertSame('', $model->getOSVersion());
        self::assertSame('', $model->getBrowser());
        self::assertSame('', $model->getBrowserVersion());
        self::assertSame('', $model->getLang());
        self::assertSame('', $model->getDateLastActivity());
        self::assertSame('', $model->getDate());
    }

    /**
     * Tests setByArray() with empty array leaves all defaults.
     */
    public function testSetByArrayEmpty()
    {
        $model = new Statistic();
        $model->setByArray([]);

        self::assertSame(0, $model->getId());
        self::assertSame(0, $model->getUserId());
        self::assertSame('', $model->getSessionId());
        self::assertSame(0, $model->getVisits());
        self::assertSame('', $model->getSite());
    }
}
