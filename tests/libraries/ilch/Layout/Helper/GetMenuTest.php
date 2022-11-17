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
use PHPUnit\Ilch\PhpunitDataset;

class GetMenuTest extends DatabaseTestCase
{
    static protected $fillDbOnSetUp = self::PROVISION_ON_SETUP_BEFORE_CLASS;

    protected $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../../_files/mysql_menu.yml');
    }

    protected static function getSchemaSQLQueries()
    {
        $adminConfig = new \Modules\Admin\Config\Config();
        return $adminConfig->getInstallSql();
    }

    /**
     * @dataProvider dpForTestGetMenu
     *
     * @param array $options
     * @param string $expected
     * @param string $currentUrlModule
     */
    public function testGetMenu(array $options, $expected, $currentUrlModule = 'article')
    {
        $request = new Request();
        $request->setModuleName($currentUrlModule);
        $request->setControllerName('index');
        $request->setActionName('index');
        $layout = new Frontend($request, new Translator(), new Router($request), '');

        $out = new GetMenu($layout);

        self::assertSame($expected, $out->getMenu(1, '<h2>%s</h2>%c', $options));
    }

    public function dpForTestGetMenu()
    {
        $menuTitle = '<h2>Menue</h2>';
        $testBox = '<h2>Testbox</h2><p>Inhalt der Testbox</p>';

        return [
            'default options' => [
                'options' => GetMenu::DEFAULT_OPTIONS,
                'expected' => $menuTitle . '<ul class="list-unstyled ilch_menu_ul">'
                    . '<li><a href="/index.php/article/index/index">Artikel</a></li>'
                    . '<li><a href="/index.php/user/index/index">Benutzer</a>'
                    . '<ul class="list-unstyled ilch_menu_ul">'
                        . '<li><a href="/index.php/jobs/index/index">Jobs</a></li>'
                        . '<li><a href="/index.php/birthday/index/index">Geburtstag</a></li>'
                    . '</ul></li><li><a href="/index.php/testseite">Seite</a></li></ul>' . $testBox
            ],
            'different classes for everything, with li-class-active' => [
                'options' => [
                    'menus' => [
                        'ul-class-root' => 'root-ul',
                        'ul-class-child' => 'child-ul',
                        'li-class-root' => 'root-li',
                        'li-class-child' => 'child-li',
                        'li-class-active' => 'active',
                        'a-class' => 'ilch_menu_a',
                        'allow-nesting' => true,
                    ],
                    'boxes' => [
                        'render' => true,
                    ],
                ],
                'expected' => $menuTitle . '<ul class="root-ul">'
                    . '<li class="root-li active"><a class="ilch_menu_a" href="/index.php/article/index/index">Artikel</a></li>'
                    . '<li class="root-li"><a class="ilch_menu_a" href="/index.php/user/index/index">Benutzer</a>'
                    . '<ul class="child-ul">'
                        . '<li class="child-li"><a class="ilch_menu_a" href="/index.php/jobs/index/index">Jobs</a></li>'
                        . '<li class="child-li"><a class="ilch_menu_a" href="/index.php/birthday/index/index">Geburtstag</a></li>'
                    . '</ul></li><li class="root-li"><a class="ilch_menu_a" href="/index.php/testseite">Seite</a></li></ul>' . $testBox
            ],
            'test li-class-root-nesting option' => [
                'options' => [
                    'menus' => [
                        'li-class-root-nesting' => 'li-has-children',
                    ],

                ],
                'expected' => $menuTitle . '<ul class="list-unstyled ilch_menu_ul">'
                    . '<li><a href="/index.php/article/index/index">Artikel</a></li>'
                    . '<li class="li-has-children"><a href="/index.php/user/index/index">Benutzer</a>'
                    . '<ul class="list-unstyled ilch_menu_ul">'
                    . '<li><a href="/index.php/jobs/index/index">Jobs</a></li>'
                    . '<li><a href="/index.php/birthday/index/index">Geburtstag</a></li>'
                    . '</ul></li><li><a href="/index.php/testseite">Seite</a></li></ul>' . $testBox
            ],
            'nesting disabled' => [
                'options' => [
                    'menus' => [
                        'ul-class-root' => 'root-ul',
                        'ul-class-child' => 'child-ul',
                        'li-class-root' => 'root-li',
                        'li-class-root-nesting' => 'li-has-children',
                        'li-class-child' => 'child-li',
                        'a-class' => 'ilch_menu_a',
                        'allow-nesting' => false,
                    ],
                    'boxes' => [
                        'render' => true,
                    ],
                ],
                'expected' => $menuTitle . '<ul class="root-ul">'
                    . '<li class="root-li"><a class="ilch_menu_a" href="/index.php/article/index/index">Artikel</a></li>'
                    . '<li class="root-li"><a class="ilch_menu_a" href="/index.php/user/index/index">Benutzer</a></li>'
                    . '<li class="root-li"><a class="ilch_menu_a" href="/index.php/jobs/index/index">Jobs</a></li>'
                    . '<li class="root-li"><a class="ilch_menu_a" href="/index.php/birthday/index/index">Geburtstag</a></li>'
                    . '<li class="root-li"><a class="ilch_menu_a" href="/index.php/testseite">Seite</a></li></ul>' . $testBox
            ],
            'boxes disabled with nested active' => [
                'options' => [
                    'menus' => [
                        'ul-class-root' => 'root-ul',
                        'ul-class-child' => 'child-ul',
                        'li-class-root' => 'root-li',
                        'li-class-child' => 'child-li',
                        'li-class-active' => 'active',
                        'a-class' => 'ilch_menu_a',
                        'allow-nesting' => true,
                    ],
                    'boxes' => [
                        'render' => false,
                    ],
                ],
                'expected' => $menuTitle . '<ul class="root-ul">'
                    . '<li class="root-li"><a class="ilch_menu_a" href="/index.php/article/index/index">Artikel</a></li>'
                    . '<li class="root-li"><a class="ilch_menu_a" href="/index.php/user/index/index">Benutzer</a>'
                    . '<ul class="child-ul">'
                    . '<li class="child-li active"><a class="ilch_menu_a" href="/index.php/jobs/index/index">Jobs</a></li>'
                    . '<li class="child-li"><a class="ilch_menu_a" href="/index.php/birthday/index/index">Geburtstag</a></li>'
                    . '</ul></li><li class="root-li"><a class="ilch_menu_a" href="/index.php/testseite">Seite</a></li></ul>',
                'currentUrlModule' => 'jobs'
            ],
        ];
    }
}
