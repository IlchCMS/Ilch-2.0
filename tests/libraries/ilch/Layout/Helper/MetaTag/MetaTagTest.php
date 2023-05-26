<?php
/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch;

use Ilch\Layout\Frontend;
use Ilch\Layout\Helper\MetaTag\Model as MetaTagModel;
use PHPUnit\Ilch\TestCase;

/**
 * Tests the MetaTag Model and getMetaTagString(), which works with the model.
 */
class MetaTagTest extends TestCase
{
    /**
     * @var MetaTagModel
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

        $this->model = new MetaTagModel();
        $this->request = new Request();
        $this->translator = new Translator();
        $this->router = new Router($this->request);
        $this->frontend = new Frontend($this->request, $this->translator, $this->router, 'localhost');
    }

    /**
     * Tests creating a meta tag with name and content.
     *
     * @return void
     */
    public function testNameContent()
    {
        $this->model->setName('generator');
        $this->model->setContent('Frontweaver 8.2');
        $this->frontend->add('metaTags', 'generator', $this->model);
        $metaTagString = $this->frontend->getMetaTagString('generator');

        self::assertEquals('<meta name="generator" content="Frontweaver 8.2">', $metaTagString);
    }

    /**
     * Test creating a meta tag with HTTPEquiv and content.
     *
     * @return void
     */
    public function testHTTPEquivContent()
    {
        $this->model->setHTTPEquiv('Refresh');
        $this->model->setContent('300');
        $this->frontend->add('metaTags', 'refresh', $this->model);
        $metaTagString = $this->frontend->getMetaTagString('refresh');

        self::assertEquals('<meta http-equiv="Refresh" content="300">', $metaTagString);
    }

    /**
     * Test creating a meta tag with charset.
     *
     * @return void
     */
    public function testCharset()
    {
        $this->model->setCharset('utf-8');
        $this->frontend->add('metaTags', 'charset', $this->model);
        $metaTagString = $this->frontend->getMetaTagString('charset');

        self::assertEquals('<meta charset="utf-8">', $metaTagString);
    }
}
