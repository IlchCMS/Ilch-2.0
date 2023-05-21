<?php
/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch;

use Ilch\Layout\Frontend;
use Ilch\Layout\Helper\ScriptTag\Model as ScriptTagModel;
use InvalidArgumentException;
use PHPUnit\Ilch\TestCase;

/**
 * Tests the ScriptTag Model and getScriptTagString(), which works with the model.
 */
class ScriptTagTest extends TestCase
{
    /**
     * @var ScriptTagModel
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

        $this->model = new ScriptTagModel();
        $this->request = new Request();
        $this->translator = new Translator();
        $this->router = new Router($this->request);
        $this->frontend = new Frontend($this->request, $this->translator, $this->router, 'localhost');
    }

    // Classic script, inline
    /**
     * Tests a classic script with inline code.
     *
     * @return void
     */
    public function testClassicScriptInline()
    {
        $this->model->setInline('alert("test")');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script>alert("test")</script>', $scriptTagString);
    }

    /**
     * Tests a classic script with inline code and a type specified.
     * The type is optionally in this case.
     *
     * @return void
     */
    public function testClassicScriptInlineWithType()
    {
        $this->model->setInline('alert("test")');
        $this->model->setType('application/javascript');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script type="application/javascript">alert("test")</script>', $scriptTagString);
    }

    /**
     * Tests a classic script with inline code and marked as blocking.
     *
     * @return void
     */
    public function testClassicScriptInlineBlocking()
    {
        $this->model->setInline('alert("test")');
        $this->model->setBlocking('render');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script blocking="render">alert("test")</script>', $scriptTagString);
    }

    /**
     * Tests a classic script with inline code and async.
     * Async should be dropped as this only works with a specified src.
     *
     * @return void
     */
    public function testClassicScriptInlineAsync()
    {
        $this->model->setInline('alert("test")');
        $this->model->setAsync(true);
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script>alert("test")</script>', $scriptTagString);
    }

    /**
     * Tests a classic script with inline code and defer.
     * Defer should be dropped as this only works with a specified src.
     *
     * @return void
     */
    public function testClassicScriptInlineDefer()
    {
        $this->model->setInline('alert("test")');
        $this->model->setDefer(true);
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script>alert("test")</script>', $scriptTagString);
    }

    /**
     * Tests a classic script with inline code and a value for integrity (usually a hash/checksum).
     * Integrity should be dropped as this only works with a specified src.
     *
     * @return void
     */
    public function testClassicScriptInlineIntegrity()
    {
        $this->model->setInline('alert("test")');
        $this->model->setIntegrity('7eec02a6d');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script>alert("test")</script>', $scriptTagString);
    }

    // Classic script with src
    /**
     * Tests a classic script with a src (the URL of the external script resource to use) specified.
     *
     * @return void
     */
    public function testClassicScript()
    {
        $this->model->setSrc('https://localhost/script.js');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script src="https://localhost/script.js"></script>', $scriptTagString);
    }

    /**
     * Tests a classic external script marked as blocking.
     *
     * @return void
     */
    public function testClassicScriptBlocking()
    {
        $this->model->setSrc('https://localhost/script.js');
        $this->model->setBlocking('render');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script src="https://localhost/script.js" blocking="render"></script>', $scriptTagString);
    }

    /**
     * Tests a classic external script with async.
     *
     * @return void
     */
    public function testClassicScriptAsync()
    {
        $this->model->setSrc('https://localhost/script.js');
        $this->model->setAsync(true);
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script src="https://localhost/script.js" async></script>', $scriptTagString);
    }

    /**
     * Tests a classic external script with defer.
     *
     * @return void
     */
    public function testClassicScriptDefer()
    {
        $this->model->setSrc('https://localhost/script.js');
        $this->model->setDefer(true);
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script src="https://localhost/script.js" defer></script>', $scriptTagString);
    }

    /**
     * Tests a classic external script with async and defer.
     *
     * @return void
     */
    public function testClassicScriptAsyncDefer()
    {
        $this->model->setSrc('https://localhost/script.js');
        $this->model->setAsync(true);
        $this->model->setDefer(true);
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script src="https://localhost/script.js" async defer></script>', $scriptTagString);
    }

    /**
     * Tests a classic external script with crossorigin.
     *
     * @return void
     */
    public function testClassicScriptCrossOrigin()
    {
        $this->model->setSrc('https://localhost/script.js');
        $this->model->setCrossorigin('anonymous');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script src="https://localhost/script.js" crossorigin="anonymous"></script>', $scriptTagString);
    }

    /**
     * Tests a classic external script with integrity (usually a hash/checksum).
     * @return void
     */
    public function testClassicScriptIntegrity()
    {
        $this->model->setSrc('https://localhost/script.js');
        $this->model->setIntegrity('7eec02a6d');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script src="https://localhost/script.js" integrity="7eec02a6d"></script>', $scriptTagString);
    }

    /**
     * Tests a classic external script with referrer policy.
     *
     * @return void
     */
    public function testClassicScriptReferrerPolicy()
    {
        $this->model->setSrc('https://localhost/script.js');
        $this->model->setReferrerpolicy('origin');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script src="https://localhost/script.js" referrerpolicy="origin"></script>', $scriptTagString);
    }

    /**
     * Tests a classic external script with nomodule set to true.
     *
     * @return void
     */
    public function testClassicScriptNoModule()
    {
        $this->model->setSrc('https://localhost/script.js');
        $this->model->setNomodule(true);
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script src="https://localhost/script.js" nomodule></script>', $scriptTagString);
    }

    /**
     * Tests a classic external script with fetch priority set to high.
     *
     * @return void
     */
    public function testClassicScriptFetchPriority()
    {
        $this->model->setSrc('https://localhost/script.js');
        $this->model->setFetchpriority('high');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script src="https://localhost/script.js" fetchpriority="high"></script>', $scriptTagString);
    }

    // module inline
    /**
     * Tests a module with inline code.
     *
     * @return void
     */
    public function testModuleScriptInline()
    {
        $this->model->setType('module');
        $this->model->setInline('somecode');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script type="module">somecode</script>', $scriptTagString);
    }

    /**
     * Tests a module with inline code and async.
     *
     * @return void
     */
    public function testModuleScriptInlineAsync()
    {
        $this->model->setType('module');
        $this->model->setInline('somecode');
        $this->model->setAsync(true);
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script type="module" async>somecode</script>', $scriptTagString);
    }

    /**
     * Tests a module with inline code and defer.
     * Defer should be dropped.
     *
     * @return void
     */
    public function testModuleScriptInlineDefer()
    {
        $this->model->setType('module');
        $this->model->setInline('somecode');
        $this->model->setDefer(true);
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script type="module">somecode</script>', $scriptTagString);
    }

    /**
     * Tests a module with inline code and blocking.
     *
     * @return void
     */
    public function testModuleScriptInlineBlocking()
    {
        $this->model->setType('module');
        $this->model->setInline('somecode');
        $this->model->setBlocking('render');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script type="module" blocking="render">somecode</script>', $scriptTagString);
    }

    // module with src
    /**
     * Tests a external (src set) module.
     *
     * @return void
     */
    public function testModuleScript()
    {
        $this->model->setType('module');
        $this->model->setSrc('https://localhost/script.js');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script type="module" src="https://localhost/script.js"></script>', $scriptTagString);
    }

    /**
     * Tests a external module with async.
     *
     * @return void
     */
    public function testModuleScriptAsync()
    {
        $this->model->setType('module');
        $this->model->setSrc('https://localhost/script.js');
        $this->model->setAsync(true);
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script type="module" src="https://localhost/script.js" async></script>', $scriptTagString);
    }

    /**
     * Tests a external module with defer.
     * Defer should be dropped.
     *
     * @return void
     */
    public function testModuleScriptDefer()
    {
        $this->model->setType('module');
        $this->model->setSrc('https://localhost/script.js');
        $this->model->setDefer(true);
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script type="module" src="https://localhost/script.js"></script>', $scriptTagString);
    }

    /**
     * Tests a external module with blocking.
     *
     * @return void
     */
    public function testModuleScriptBlocking()
    {
        $this->model->setType('module');
        $this->model->setSrc('https://localhost/script.js');
        $this->model->setBlocking('render');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script type="module" src="https://localhost/script.js" blocking="render"></script>', $scriptTagString);
    }

    // importmap
    /**
     * Tests importmap.
     *
     * @return void
     */
    public function testImportMap()
    {
        $this->model->setType('importmap');
        $this->model->setInline('somecode');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script type="importmap">somecode</script>', $scriptTagString);
    }

    /**
     * Tests importmap with async.
     * Async should be dropped.
     *
     * @return void
     */
    public function testImportMapAsync()
    {
        $this->model->setType('importmap');
        $this->model->setInline('somecode');
        $this->model->setAsync(true);
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script type="importmap">somecode</script>', $scriptTagString);
    }

    /**
     * Tests importmap with NoModule.
     * NoModule should be dropped.
     *
     * @return void
     */
    public function testImportMapNoModule()
    {
        $this->model->setType('importmap');
        $this->model->setInline('somecode');
        $this->model->setNomodule(true);
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script type="importmap">somecode</script>', $scriptTagString);
    }

    /**
     * Tests importmap with defer.
     * Defer should be dropped.
     *
     * @return void
     */
    public function testImportMapDefer()
    {
        $this->model->setType('importmap');
        $this->model->setInline('somecode');
        $this->model->setDefer(true);
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script type="importmap">somecode</script>', $scriptTagString);
    }

    /**
     * Tests importmap with crossorigin.
     * Crossorigin should be dropped.
     *
     * @return void
     */
    public function testImportMapCrossorigin()
    {
        $this->model->setType('importmap');
        $this->model->setInline('somecode');
        $this->model->setCrossorigin('anonymous');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script type="importmap">somecode</script>', $scriptTagString);
    }

    /**
     * Tests importmap with integrity.
     * Integrity should be dropped.
     *
     * @return void
     */
    public function testImportMapIntegrity()
    {
        $this->model->setType('importmap');
        $this->model->setInline('somecode');
        $this->model->setIntegrity('7eec02a6d');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script type="importmap">somecode</script>', $scriptTagString);
    }

    /**
     * Tests importmap with referrer policy.
     * Referrer policy should be dropped.
     *
     * @return void
     */
    public function testImportMapReferrerPolicy()
    {
        $this->model->setType('importmap');
        $this->model->setInline('somecode');
        $this->model->setReferrerpolicy('origin');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script type="importmap">somecode</script>', $scriptTagString);
    }

    /**
     * Tests importmap with blocking.
     *
     * @return void
     */
    public function testImportMapBlocking()
    {
        $this->model->setType('importmap');
        $this->model->setInline('somecode');
        $this->model->setBlocking('render');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script type="importmap" blocking="render">somecode</script>', $scriptTagString);
    }

    /**
     * Tests importmap with a source specified.
     * Src should be dropped.
     * Import maps can only be inline, i.e., the src attribute and most other attributes are meaningless and not to be used with them.
     *
     * @return void
     */
    public function testImportMapSource()
    {
        $this->model->setType('importmap');
        $this->model->setSrc('https://localhost/script.js');
        $this->model->setInline('somecode');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script type="importmap">somecode</script>', $scriptTagString);
    }

    // data block
    /**
     * Tests a data block.
     *
     * @return void
     */
    public function testDataBlock()
    {
        $this->model->setType('text/x-game-map');
        $this->model->setInline('........U.........e');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script type="text/x-game-map">........U.........e</script>', $scriptTagString);
    }

    /**
     * Tests a data block with blocking.
     *
     * @return void
     */
    public function testDataBlockBlocking()
    {
        $this->model->setType('text/x-game-map');
        $this->model->setInline('........U.........e');
        $this->model->setBlocking('render');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script type="text/x-game-map" blocking="render">........U.........e</script>', $scriptTagString);
    }

    /**
     * Tests a data block with source.
     * Source should be dropped.
     *
     * @return void
     */
    public function testDataBlockSource()
    {
        $this->model->setType('text/x-game-map');
        $this->model->setSrc('https://localhost/script.js');
        $this->model->setInline('........U.........e');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script type="text/x-game-map">........U.........e</script>', $scriptTagString);
    }

    /**
     * Tests a data block with async.
     * Async should be dropped.
     *
     * @return void
     */
    public function testDataBlockAsync()
    {
        $this->model->setType('text/x-game-map');
        $this->model->setInline('........U.........e');
        $this->model->setAsync(true);
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script type="text/x-game-map">........U.........e</script>', $scriptTagString);
    }

    /**
     * Tests a data block with defer.
     * Defer should be dropped.
     *
     * @return void
     */
    public function testDataBlockDefer()
    {
        $this->model->setType('text/x-game-map');
        $this->model->setInline('........U.........e');
        $this->model->setDefer(true);
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script type="text/x-game-map">........U.........e</script>', $scriptTagString);
    }

    /**
     * Tests a data block with crossorigin
     * Crossorigin should be dropped.
     *
     * @return void
     */
    public function testDataBlockCrossOrigin()
    {
        $this->model->setType('text/x-game-map');
        $this->model->setInline('........U.........e');
        $this->model->setCrossorigin('anonymous');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script type="text/x-game-map">........U.........e</script>', $scriptTagString);
    }

    /**
     * Tests a data block with integrity.
     * Integrity should be dropped.
     *
     * @return void
     */
    public function testDataBlockIntegrity()
    {
        $this->model->setType('text/x-game-map');
        $this->model->setInline('........U.........e');
        $this->model->setIntegrity('7eec02a6d');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script type="text/x-game-map">........U.........e</script>', $scriptTagString);
    }

    /**
     * Tests a data block with referrer policy.
     * Referrer policy should be dropped.
     *
     * @return void
     */
    public function testDataBlockReferrerPolicy()
    {
        $this->model->setType('text/x-game-map');
        $this->model->setInline('........U.........e');
        $this->model->setReferrerpolicy('origin');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script type="text/x-game-map">........U.........e</script>', $scriptTagString);
    }

    /**
     * Tests a data block with fetch priority.
     * Fetch priority should be dropped.
     *
     * @return void
     */
    public function testDataBlockFetchPriority()
    {
        $this->model->setType('text/x-game-map');
        $this->model->setInline('........U.........e');
        $this->model->setFetchpriority('high');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertEquals('<script type="text/x-game-map">........U.........e</script>', $scriptTagString);
    }

    /**
     * Tests a data block with NoModule set.
     * NoModule should be dropped.
     *
     * @return void
     */
    public function testDataBlockNoModule()
    {
        $this->model->setType('text/x-game-map');
        $this->model->setInline('........U.........e');
        $this->model->setNomodule(true);
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertTrue($this->model->isDataBlock());
        self::assertEquals('<script type="text/x-game-map">........U.........e</script>', $scriptTagString);
    }

    /**
     * Test if isDataBlock returns true for a data block.
     *
     * @return void
     */
    public function testDataBlockIsDataBlockTrue()
    {
        $this->model->setType('text/x-game-map');
        $this->model->setInline('........U.........e');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertTrue($this->model->isDataBlock());
        self::assertEquals('<script type="text/x-game-map">........U.........e</script>', $scriptTagString);
    }

    // Test the conditions where isDataBlock() should return false.
    /**
     * Test if isDataBlock returns false for a classic inline script.
     *
     * @return void
     */
    public function testIsDataBlockFalse()
    {
        $this->model->setType('');
        $this->model->setInline('........U.........e');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertFalse($this->model->isDataBlock());
        self::assertEquals('<script>........U.........e</script>', $scriptTagString);
    }

    /**
     * Test if isDataBlock returns false for a module.
     *
     * @return void
     */
    public function testIsDataBlockFalseModule()
    {
        $this->model->setType('module');
        $this->model->setInline('........U.........e');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertFalse($this->model->isDataBlock());
        self::assertEquals('<script type="module">........U.........e</script>', $scriptTagString);
    }

    /**
     * Test if isDataBlock returns false for an import map.
     *
     * @return void
     */
    public function testIsDataBlockFalseImportMap()
    {
        $this->model->setType('importmap');
        $this->model->setInline('........U.........e');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertFalse($this->model->isDataBlock());
        self::assertEquals('<script type="importmap">........U.........e</script>', $scriptTagString);
    }

    /**
     * Test if isDataBlock returns false for a classic inline script with type specified.
     *
     * @return void
     */
    public function testIsDataBlockFalseScript()
    {
        $this->model->setType('application/javascript');
        $this->model->setInline('........U.........e');
        $this->frontend->add('scriptTags', 'somescript', $this->model);
        $scriptTagString = $this->frontend->getScriptTagString('somescript');

        self::assertFalse($this->model->isDataBlock());
        self::assertEquals('<script type="application/javascript">........U.........e</script>', $scriptTagString);
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
}
