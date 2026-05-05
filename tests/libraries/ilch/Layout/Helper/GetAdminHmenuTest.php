<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch\Layout\Helper;

use Ilch\Layout\Admin;
use Ilch\Request;
use Ilch\Router;
use Ilch\Translator;
use PHPUnit\Ilch\TestCase;

class GetAdminHmenuTest extends TestCase
{
    protected Request $request;
    protected Admin $layout;

    public function setUp(): void
    {
        parent::setUp();

        $this->request = new Request();
        $this->request->setModuleName('article');
        $this->request->setControllerName('index');
        $this->request->setActionName('index');
        $this->layout = new Admin($this->request, new Translator(), new Router($this->request), '');
    }

    public function testGetAdminHmenu()
    {
        $test = new GetAdminHmenu($this->layout);
        $test->getAdminHmenu()
            ->add('test', ['controller' => 'index', 'action' => 'index']);

        self::assertSame('<div aria-label="breadcrumb"><ol class="breadcrumb">&raquo; &nbsp;<li class="breadcrumb-item active"><a href="/index.php/admin/admin/index/index">Admincenter</a></li><li class="breadcrumb-item"><a href="/index.php/article/index/index">test</a></li></ol></div>', (string)$test->getAdminHmenu());
    }

    public function testGetAdminHmenuTwoAdditions()
    {
        $test = new GetAdminHmenu($this->layout);
        $test->getAdminHmenu()
            ->add('test2', ['controller' => 'index', 'action' => 'index'])
            ->add('test', ['controller' => 'index', 'action' => 'index']);

        self::assertSame('<div aria-label="breadcrumb"><ol class="breadcrumb">&raquo; &nbsp;<li class="breadcrumb-item active"><a href="/index.php/admin/admin/index/index">Admincenter</a></li><li class="breadcrumb-item"><a href="/index.php/article/index/index">test2</a></li><li class="breadcrumb-item"><a href="/index.php/article/index/index">test</a></li></ol></div>', (string)$test->getAdminHmenu());
    }

    /**
     * This would have failed with Ilch 2.2.15 and earlier as the indices of the array
     * wouldn't be unique.
     *
     * @return void
     */
    public function testGetAdminHmenuDuplicateKeysIndices()
    {
        $test = new GetAdminHmenu($this->layout);
        $test->getAdminHmenu()
            ->add('test', ['controller' => 'index', 'action' => 'index'])
            ->add('test', ['controller' => 'index', 'action' => 'index', 'id' => '1']);

        self::assertSame('<div aria-label="breadcrumb"><ol class="breadcrumb">&raquo; &nbsp;<li class="breadcrumb-item active"><a href="/index.php/admin/admin/index/index">Admincenter</a></li><li class="breadcrumb-item"><a href="/index.php/article/index/index">test</a></li><li class="breadcrumb-item"><a href="/index.php/article/index/index/id/1">test</a></li></ol></div>', (string)$test->getAdminHmenu());
    }

    public function testGetAdminHmenuEmptyKey()
    {
        $test = new GetAdminHmenu($this->layout);
        $test->getAdminHmenu()
            ->add('', ['controller' => 'index', 'action' => 'index']);

        self::assertSame('<div aria-label="breadcrumb"><ol class="breadcrumb">&raquo; &nbsp;<li class="breadcrumb-item active"><a href="/index.php/admin/admin/index/index">Admincenter</a></li><li class="breadcrumb-item"><a href="/index.php/article/index/index"></a></li></ol></div>', (string)$test->getAdminHmenu());
    }

    public function testGetAdminHmenuEmptyValue()
    {
        $test = new GetAdminHmenu($this->layout);
        $test->getAdminHmenu()
            ->add('test', []);

        self::assertSame('<div aria-label="breadcrumb"><ol class="breadcrumb">&raquo; &nbsp;<li class="breadcrumb-item active"><a href="/index.php/admin/admin/index/index">Admincenter</a></li>test</ol></div>', (string)$test->getAdminHmenu());
    }

    public function testGetAdminHmenuEmptyValueOther()
    {
        $test = new GetAdminHmenu($this->layout);
        $test->getAdminHmenu()
            ->add('test', ['controller' => '', 'action' => '']);

        self::assertSame('<div aria-label="breadcrumb"><ol class="breadcrumb">&raquo; &nbsp;<li class="breadcrumb-item active"><a href="/index.php/admin/admin/index/index">Admincenter</a></li><li class="breadcrumb-item"><a href="/index.php/article//">test</a></li></ol></div>', (string)$test->getAdminHmenu());
    }
}