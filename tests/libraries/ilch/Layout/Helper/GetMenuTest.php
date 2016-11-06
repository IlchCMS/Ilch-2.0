<?php
/**
 * @copyright Ilch 2.0
 * @package ilch_phpunit
 */

namespace Ilch\Layout\Helper;

use Ilch\Layout\Frontend;
use Ilch\Request;
use Ilch\Router;
use Ilch\Translator;
use PHPUnit\Ilch\DatabaseTestCase;

class GetMenuTest extends DatabaseTestCase
{
    static protected $fillDbOnSetUp = self::PROVISION_ON_SETUP_BEFORE_CLASS;

    protected static function getSchemaSQLQueries()
    {
        $adminConfig = new \Modules\Admin\Config\Config();
        return $adminConfig->getInstallSql();
    }

    /**
     * Returns the test dataset.
     *
     * @return \PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    protected function getDataSet()
    {
        return new \PHPUnit_Extensions_Database_DataSet_YamlDataSet(__DIR__ . '/../../_files/mysql_menu.yml');
    }

    /**
     * @dataProvider dpForTestGetMenu
     *
     * @param array $options
     * @param string $expected
     */
    public function testGetMenu(array $options, $expected)
    {
        $request = new Request();
        $layout = new Frontend($request, new Translator(), new Router($request), '');

        $out = new GetMenu($layout);

        $this->assertSame($expected, $out->getMenu(1, '<h2>%s</h2>%c', $options));
    }

    public function dpForTestGetMenu()
    {
        $menuTitle = '<h2>Menue</h2>';
        $testBox = '<h2>Testbox</h2><p>Inhalt der Testbox</p>';

        return [
            'default options' => [
                'options' => GetMenu::DEFAULT_OPTIONS,
                'expected' => $menuTitle . '<ul class="list-unstyled ilch_menu_ul">'
                    . '<li class=""><a href="/index.php/article/index/index">Artikel</a></li>'
                    . '<li class=""><a href="/index.php/user/index/index">Benutzer</a>'
                    . '<ul class="list-unstyled ilch_menu_ul">'
                        . '<li class=""><a href="/index.php/jobs/index/index">Jobs</a></li>'
                        . '<li class=""><a href="/index.php/birthday/index/index">Geburtstag</a></li>'
                    . '</ul></li><li class=""><a href="/index.php/testseite">Seite</a></li></ul>' . $testBox
            ],
            'different classes for everything' => [
                'options' => [
                    'menus' => [
                        'ul-class-root' => 'root-ul',
                        'ul-class-child' => 'child-ul',
                        'li-class-root' => 'root-li',
                        'li-class-child' => 'child-li',
                        'allow-nesting' => true,
                    ],
                    'boxes' => [
                        'render' => true,
                    ],
                ],
                'expected' => $menuTitle . '<ul class="root-ul">'
                    . '<li class="root-li"><a href="/index.php/article/index/index">Artikel</a></li>'
                    . '<li class="root-li"><a href="/index.php/user/index/index">Benutzer</a>'
                    . '<ul class="child-ul">'
                        . '<li class="child-li"><a href="/index.php/jobs/index/index">Jobs</a></li>'
                        . '<li class="child-li"><a href="/index.php/birthday/index/index">Geburtstag</a></li>'
                    . '</ul></li><li class="root-li"><a href="/index.php/testseite">Seite</a></li></ul>' . $testBox
            ],
            'nesting disabled' => [
                'options' => [
                    'menus' => [
                        'ul-class-root' => 'root-ul',
                        'ul-class-child' => 'child-ul',
                        'li-class-root' => 'root-li',
                        'li-class-child' => 'child-li',
                        'allow-nesting' => false,
                    ],
                    'boxes' => [
                        'render' => true,
                    ],
                ],
                'expected' => $menuTitle . '<ul class="root-ul">'
                    . '<li class="root-li"><a href="/index.php/article/index/index">Artikel</a></li>'
                    . '<li class="root-li"><a href="/index.php/user/index/index">Benutzer</a></li>'
                    . '<li class="root-li"><a href="/index.php/jobs/index/index">Jobs</a></li>'
                    . '<li class="root-li"><a href="/index.php/birthday/index/index">Geburtstag</a></li>'
                    . '<li class="root-li"><a href="/index.php/testseite">Seite</a></li></ul>' . $testBox
            ],
            'boxes disabled' => [
                'options' => [
                    'menus' => [
                        'ul-class-root' => 'root-ul',
                        'ul-class-child' => 'child-ul',
                        'li-class-root' => 'root-li',
                        'li-class-child' => 'child-li',
                        'allow-nesting' => true,
                    ],
                    'boxes' => [
                        'render' => false,
                    ],
                ],
                'expected' => $menuTitle . '<ul class="root-ul">'
                    . '<li class="root-li"><a href="/index.php/article/index/index">Artikel</a></li>'
                    . '<li class="root-li"><a href="/index.php/user/index/index">Benutzer</a>'
                    . '<ul class="child-ul">'
                    . '<li class="child-li"><a href="/index.php/jobs/index/index">Jobs</a></li>'
                    . '<li class="child-li"><a href="/index.php/birthday/index/index">Geburtstag</a></li>'
                    . '</ul></li><li class="root-li"><a href="/index.php/testseite">Seite</a></li></ul>'
            ],
        ];
    }
}
