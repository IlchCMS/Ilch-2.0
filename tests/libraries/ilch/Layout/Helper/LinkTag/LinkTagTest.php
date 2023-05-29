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
     * Test creating a link tag with rel, href and sizes. This time with Rel set to 'apple-touch-icon'.
     *
     * @return void
     */
    public function testSizesAppleIcon()
    {
        $this->model->setRel('apple-touch-icon');
        $this->model->setHref('/apple-touch-icon.png');
        $this->model->setSizes('180x180');
        $this->frontend->add('linkTags', 'appleicon', $this->model);
        $linkTagString = $this->frontend->getLinkTagString('appleicon');

        self::assertEquals('<link rel="apple-touch-icon" href="/apple-touch-icon.png" sizes="180x180">', $linkTagString);
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

    /**
     * Test creating a link tag with rel, as, imagesrcset and imagesizes.
     *
     * @return void
     */
    public function testImagesrcsetImagesizes()
    {
        $this->model->setRel('preload');
        $this->model->setAs('image');
        $this->model->setImagesrcset('wolf_400px.jpg 400w, wolf_800px.jpg 800w, wolf_1600px.jpg 1600w');
        $this->model->setImagesizes('50vw');
        $this->frontend->add('linkTags', 'preload', $this->model);
        $linkTagString = $this->frontend->getLinkTagString('preload');

        self::assertEquals('<link rel="preload" imagesrcset="wolf_400px.jpg 400w, wolf_800px.jpg 800w, wolf_1600px.jpg 1600w" imagesizes="50vw" as="image">', $linkTagString);
    }

    /**
     * Test creating a link tag with rel, as, imagesrcset and imagesizes. Invalid rel.
     * The imagesrcset and imagesizes attributes must only be specified on link elements that have both a rel attribute that specifies the preload keyword, as well as an as attribute in the "image" state.
     * imagesrcset and imagesizes attributes dropped. As aswell.
     *
     * @return void
     */
    public function testImagesrcsetImagesizesInvalidRel()
    {
        $this->model->setRel('test');
        $this->model->setAs('image');
        $this->model->setImagesrcset('wolf_400px.jpg 400w, wolf_800px.jpg 800w, wolf_1600px.jpg 1600w');
        $this->model->setImagesizes('50vw');
        $this->frontend->add('linkTags', 'preload', $this->model);
        $linkTagString = $this->frontend->getLinkTagString('preload');

        self::assertEquals('<link rel="test">', $linkTagString);
    }

    /**
     * Test creating a link tag with rel, as, imagesrcset and imagesizes. Invalid As.
     * The imagesrcset and imagesizes attributes must only be specified on link elements that have both a rel attribute that specifies the preload keyword, as well as an as attribute in the "image" state.
     * imagesrcset and imagesizes attributes dropped.
     *
     * @return void
     */
    public function testImagesrcsetImagesizesInvalidAs()
    {
        $this->model->setRel('preload');
        $this->model->setAs('video');
        $this->model->setImagesrcset('wolf_400px.jpg 400w, wolf_800px.jpg 800w, wolf_1600px.jpg 1600w');
        $this->model->setImagesizes('50vw');
        $this->frontend->add('linkTags', 'preload', $this->model);
        $linkTagString = $this->frontend->getLinkTagString('preload');

        self::assertEquals('<link rel="preload" as="video">', $linkTagString);
    }

    /**
     * Test creating a link with an invalid rel for the as attribute.
     *
     * The attribute must be specified on link elements that have a rel attribute that contains the preload keyword.
     * It may be specified on link elements that have a rel attribute that contains the modulepreload keyword; in such cases it must have a value which is a script-like destination.
     * For other link elements, it must not be specified.
     * As dropped.
     *
     * @return void
     */
    public function testAsInvalidRel()
    {
        $this->model->setRel('test');
        $this->model->setAs('image');
        $this->frontend->add('linkTags', 'As', $this->model);
        $linkTagString = $this->frontend->getLinkTagString('As');

        self::assertEquals('<link rel="test">', $linkTagString);
    }

    /**
     * Test creating a link with modulepreload as rel for the as attribute.
     *
     * @return void
     */
    public function testAsModulePreloadRel()
    {
        $this->model->setRel('modulepreload');
        $this->model->setHref('super-critical-stuff.mjs');
        $this->frontend->add('linkTags', 'As', $this->model);
        $linkTagString = $this->frontend->getLinkTagString('As');

        self::assertEquals('<link rel="modulepreload" href="super-critical-stuff.mjs">', $linkTagString);
    }

    /**
     * Test creating a link tag with invalid rel for the sizes attribute.
     * The (sizes) attribute must only be specified on link elements that have a rel attribute that specifies the icon keyword or the apple-touch-icon keyword.
     * Sizes dropped.
     *
     * @return void
     */
    public function testWrongRelWithSizes()
    {
        $this->model->setRel('test');
        $this->model->setSizes('16x16');
        $this->frontend->add('linkTags', 'test', $this->model);
        $linkTagString = $this->frontend->getLinkTagString('test');

        self::assertEquals('<link rel="test">', $linkTagString);
    }

    /**
     * Test creating a link tag with href, rel and integrity.
     *
     * @return void
     */
    public function testIntegrity()
    {
        $this->model->setHref('https://localhost/css/stylesheet.min.css');
        $this->model->setRel('stylesheet');
        $this->model->setIntegrity('sha256-MfvZlkHCEqatNoGiOXveE8FIwMzZg4W85qfrfIFBfYc');
        $this->frontend->add('linkTags', 'integrity', $this->model);
        $linkTagString = $this->frontend->getLinkTagString('integrity');

        self::assertEquals('<link rel="stylesheet" href="https://localhost/css/stylesheet.min.css" integrity="sha256-MfvZlkHCEqatNoGiOXveE8FIwMzZg4W85qfrfIFBfYc">', $linkTagString);
    }

    /**
     * Test creating a link tag with href, rel and integrity. Rel set to preload.
     *
     * @return void
     */
    public function testIntegrityPreload()
    {
        $this->model->setHref('https://localhost/css/stylesheet.min.css');
        $this->model->setRel('preload');
        $this->model->setIntegrity('sha256-MfvZlkHCEqatNoGiOXveE8FIwMzZg4W85qfrfIFBfYc');
        $this->frontend->add('linkTags', 'integrity', $this->model);
        $linkTagString = $this->frontend->getLinkTagString('integrity');

        self::assertEquals('<link rel="preload" href="https://localhost/css/stylesheet.min.css" integrity="sha256-MfvZlkHCEqatNoGiOXveE8FIwMzZg4W85qfrfIFBfYc">', $linkTagString);
    }

    /**
     * Test creating a link tag with href, rel and integrity. Rel set to modulepreload.
     *
     * @return void
     */
    public function testIntegrityModulePreload()
    {
        $this->model->setHref('super-critical-stuff.mjs');
        $this->model->setRel('modulepreload');
        $this->model->setIntegrity('sha256-MfvZlkHCEqatNoGiOXveE8FIwMzZg4W85qfrfIFBfYc');
        $this->frontend->add('linkTags', 'integrity', $this->model);
        $linkTagString = $this->frontend->getLinkTagString('integrity');

        self::assertEquals('<link rel="modulepreload" href="super-critical-stuff.mjs" integrity="sha256-MfvZlkHCEqatNoGiOXveE8FIwMzZg4W85qfrfIFBfYc">', $linkTagString);
    }

    /**
     * Test creating a link tag with href, rel and integrity. rel is invalid.
     * The attribute must only be specified on link elements that have a rel attribute that contains the stylesheet, preload, or modulepreload keyword.
     * Integrity dropped.
     *
     * @return void
     */
    public function testIntegrityInvalidRel()
    {
        $this->model->setHref('https://localhost/css/stylesheet.min.css');
        $this->model->setRel('test');
        $this->model->setIntegrity('sha256-MfvZlkHCEqatNoGiOXveE8FIwMzZg4W85qfrfIFBfYc');
        $this->frontend->add('linkTags', 'integrity', $this->model);
        $linkTagString = $this->frontend->getLinkTagString('integrity');

        self::assertEquals('<link rel="test" href="https://localhost/css/stylesheet.min.css">', $linkTagString);
    }

    /**
     * Test creating a disabled link with href and rel.
     *
     * @return void
     */
    public function testDisabled()
    {
        $this->model->setHref('css/pooh');
        $this->model->setRel('alternate stylesheet');
        $this->model->setDisabled(true);
        $this->frontend->add('linkTags', 'disabled', $this->model);
        $linkTagString = $this->frontend->getLinkTagString('disabled');

        self::assertEquals('<link rel="alternate stylesheet" href="css/pooh" disabled>', $linkTagString);
    }

    /**
     * Test creating a link with href, rel and color.
     *
     * @return void
     */
    public function testColor()
    {
        $this->model->setHref('/safari-pinned-tab.svg');
        $this->model->setRel('mask-icon');
        $this->model->setColor('#193860');
        $this->frontend->add('linkTags', 'color', $this->model);
        $linkTagString = $this->frontend->getLinkTagString('color');

        self::assertEquals('<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#193860">', $linkTagString);
    }

    /**
     * Test creating a link with href, rel and color.
     * The attribute must only be specified on link elements that have a rel attribute that contains the mask-icon keyword.
     * color dropped.
     *
     * @return void
     */
    public function testColorInvalidRel()
    {
        $this->model->setHref('/safari-pinned-tab.svg');
        $this->model->setRel('test');
        $this->model->setColor('#193860');
        $this->frontend->add('linkTags', 'color', $this->model);
        $linkTagString = $this->frontend->getLinkTagString('color');

        self::assertEquals('<link rel="test" href="/safari-pinned-tab.svg">', $linkTagString);
    }

    /**
     * Test creating a link with href, rel and blocking.
     *
     * @return void
     */
    public function testBlockingRel()
    {
        $this->model->setRel('stylesheet');
        $this->model->setHref('green.css');
        $this->model->setBlocking('render');
        $this->frontend->add('linkTags', 'blocking', $this->model);
        $linkTagString = $this->frontend->getLinkTagString('blocking');

        self::assertEquals('<link rel="stylesheet" href="green.css" blocking="render">', $linkTagString);
    }

    /**
     * Test creating a link with href, rel and blocking. Rel is invalid for usage with blocking.
     * It is used by link type stylesheet, and it must only be specified on link elements that have a rel attribute containing that keyword.
     * blocking dropped.
     *
     * @return void
     */
    public function testBlockingInvalidRel()
    {
        $this->model->setRel('test');
        $this->model->setHref('green.css');
        $this->model->setBlocking('render');
        $this->frontend->add('linkTags', 'blocking', $this->model);
        $linkTagString = $this->frontend->getLinkTagString('blocking');

        self::assertEquals('<link rel="test" href="green.css">', $linkTagString);
    }

    /**
     * Test creating a link with href, type, rel and referrerpolicy.
     *
     * @return void
     */
    public function testReferrerPolicy()
    {
        $this->model->setRel('stylesheet');
        $this->model->setType('text/css');
        $this->model->setHref('styles.css');
        $this->model->setReferrerpolicy('no-referrer');
        $this->frontend->add('linkTags', 'referrerpolicy', $this->model);
        $linkTagString = $this->frontend->getLinkTagString('referrerpolicy');

        self::assertEquals('<link rel="stylesheet" href="styles.css" type="text/css" referrerpolicy="no-referrer">', $linkTagString);
    }

    /**
     * Test creating a link with href, rel and fetchpriority.
     *
     * @return void
     */
    public function testFetchPriority()
    {
        $this->model->setRel('stylesheet');
        $this->model->setHref('/path/to/main.css');
        $this->model->setFetchpriority('high');
        $this->frontend->add('linkTags', 'fetchpriority', $this->model);
        $linkTagString = $this->frontend->getLinkTagString('fetchpriority');

        self::assertEquals('<link rel="stylesheet" href="/path/to/main.css" fetchpriority="high">', $linkTagString);
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
