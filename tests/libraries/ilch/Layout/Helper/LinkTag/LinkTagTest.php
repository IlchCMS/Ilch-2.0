<?php
/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch;

use Ilch\Layout\Frontend;
use Ilch\Layout\Helper\LinkTag\Model as LinkTagModel;
use InvalidArgumentException;
use PHPUnit\Ilch\TestCase;

/**
 * Tests the LinkTag Model and getLinkTagString(), which works with the model.
 */
class LinkTagTest extends TestCase
{
    /**
     * @var LinkTagModel
     */
    protected $model;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var Frontend
     */
    protected $frontend;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->model = new LinkTagModel();
        $this->request = new Request();
        $this->translator = new Translator();
        $this->router = new Router($this->request);
        $this->frontend = new Frontend($this->request, $this->translator, $this->router, 'localhost');
    }

    /**
     * Tests creating a link tag with rel and href.
     *
     * @return void
     */
    public function testRelHref()
    {
        $this->model->setRel('stylesheet');
        $this->model->setHref('default.css');
        $this->frontend->add('linkTags', 'stylesheet', $this->model);
        $linkTagString = $this->frontend->getLinkTagString('stylesheet');

        self::assertEquals('<link rel="stylesheet" href="default.css">', $linkTagString);
    }

    /**
     * Test creating a link tag with rel, href and a title.
     *
     * @return void
     */
    public function testRelHrefTitle()
    {
        $this->model->setRel('stylesheet');
        $this->model->setHref('green.css');
        $this->model->setTitle('Green styles');
        $this->frontend->add('linkTags', 'stylesheet', $this->model);
        $linkTagString = $this->frontend->getLinkTagString('stylesheet');

        self::assertEquals('<link rel="stylesheet" href="green.css" title="Green styles">', $linkTagString);
    }

    /**
     * Test creating a link tag with rel, href, hreflang, type and a title.
     *
     * @return void
     */
    public function testHreflangType()
    {
        $this->model->setRel('alternate');
        $this->model->setHref('/en/html');
        $this->model->setHreflang('en');
        $this->model->setType('text/html');
        $this->model->setTitle('English HTML');
        $this->frontend->add('linkTags', 'alternate', $this->model);
        $linkTagString = $this->frontend->getLinkTagString('alternate');

        self::assertEquals('<link rel="alternate" href="/en/html" hreflang="en" type="text/html" title="English HTML">', $linkTagString);
    }

    /**
     * Test creating a link tag with rel, href, hreflang, type, media and a title.
     *
     * @return void
     */
    public function testMedia()
    {
        $this->model->setRel('alternate');
        $this->model->setHref('/en/html/print');
        $this->model->setHreflang('en');
        $this->model->setType('text/html');
        $this->model->setMedia('print');
        $this->model->setTitle('English HTML (for printing)');
        $this->frontend->add('linkTags', 'alternate', $this->model);
        $linkTagString = $this->frontend->getLinkTagString('alternate');

        self::assertEquals('<link rel="alternate" href="/en/html/print" hreflang="en" type="text/html" media="print" title="English HTML (for printing)">', $linkTagString);
    }

    /**
     * Test creating a link tag with rel, href, type and sizes.
     *
     * @return void
     */
    public function testSizes()
    {
        $this->model->setRel('icon');
        $this->model->setHref('demo_icon.gif');
        $this->model->setType('image/gif');
        $this->model->setSizes('16x16');
        $this->frontend->add('linkTags', 'icon', $this->model);
        $linkTagString = $this->frontend->getLinkTagString('icon');

        self::assertEquals('<link rel="icon" href="demo_icon.gif" sizes="16x16" type="image/gif">', $linkTagString);
    }

    /**
     * Test creating a link tag with rel, href, type and sizes.
     *
     * @return void
     */
    public function testCrossoriginAs()
    {
        $this->model->setRel('preload');
        $this->model->setHref('myFont.woff2');
        $this->model->setAs('font');
        $this->model->setType('font/woff2');
        $this->model->setCrossorigin('anonymous');
        $this->frontend->add('linkTags', 'preload', $this->model);
        $linkTagString = $this->frontend->getLinkTagString('preload');

        self::assertEquals('<link rel="preload" href="myFont.woff2" crossorigin="anonymous" type="font/woff2" as="font">', $linkTagString);
    }

    // Test the expected exceptions in the model.
    /**
     * Test if the setter throws InvalidArgumentException with the correct exception message for an invalid value.
     *
     * @return void
     */
    public function testInvalidCrossOrigin()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid value for crossorigin.');
        $this->model->setCrossorigin('invalid');
    }

    /**
     * Test if the setter throws InvalidArgumentException with the correct exception message for an invalid value.
     *
     * @return void
     */
    public function testInvalidReferrerpolicy()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid referrer policy.');
        $this->model->setReferrerpolicy('invalid');
    }

    /**
     * Test if the setter throws InvalidArgumentException with the correct exception message for an invalid value.
     *
     * @return void
     */
    public function testInvalidBlocking()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid value for blocking.');
        $this->model->setBlocking('invalid');
    }

    /**
     * Test if the setter throws InvalidArgumentException with the correct exception message for an invalid value.
     *
     * @return void
     */
    public function testInvalidFetchPriority()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid value for fetchpriority.');
        $this->model->setFetchpriority('invalid');
    }

    /**
     * Test if the setter throws InvalidArgumentException with the correct exception message for an invalid value.
     *
     * @return void
     */
    public function testInvalidAs()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid value for as.');
        $this->model->setAs('invalid');
    }
}
