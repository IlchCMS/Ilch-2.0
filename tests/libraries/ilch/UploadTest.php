<?php

/**
 * @package ilch_phpunit
 */

namespace Ilch;

use PHPUnit\Ilch\TestCase;

class UploadTest extends TestCase
{
    protected Upload $upload;

    protected function setUp(): void
    {
        parent::setUp();
        $this->upload = new Upload();
    }

    protected function tearDown(): void
    {
        unset($this->upload);
        parent::tearDown();
    }

    public function testProperties()
    {
        $this->upload->setFile('file.png')
            ->setUrl('https://test/file.png')
            ->setUrlThumb('https://test/file_thumb.png')
            ->setAllowedExtensions('png jpg jpeg')
            ->setPath('file');

        self::assertSame('file.png', $this->upload->getFile());
        self::assertSame('file', $this->upload->getName());
        self::assertSame('png', $this->upload->getExtension());
        self::assertSame('https://test/file.png', $this->upload->getUrl());
        self::assertSame('https://test/file_thumb.png', $this->upload->getUrlThumb());
        self::assertSame('png jpg jpeg', $this->upload->getAllowedExtensions());
        self::assertSame('file', $this->upload->getPath());
    }

    public function testIsAllowedExtension()
    {
        $this->upload->setFile('file.png')
            ->setAllowedExtensions('png jpg jpeg');

        self::assertTrue($this->upload->isAllowedExtension());
    }

    public function testIsAllowedExtensionFalse()
    {
        $this->upload->setFile('file.php')
            ->setAllowedExtensions('png jpg jpeg');

        self::assertFalse($this->upload->isAllowedExtension());
    }

    public function testReturnBytes()
    {
        self::assertSame(1048576, $this->upload->returnBytes('1M'));
        self::assertSame(1024, $this->upload->returnBytes('1K'));
        self::assertSame(1073741824, $this->upload->returnBytes('1G'));
        self::assertSame(1048576, $this->upload->returnBytes('1m'));
        self::assertSame(1024, $this->upload->returnBytes('1k'));
        self::assertSame(1073741824, $this->upload->returnBytes('1g'));

        self::assertSame(2097152, $this->upload->returnBytes('2M'));
        self::assertSame(2048, $this->upload->returnBytes('2K'));
        self::assertSame(2147483648, $this->upload->returnBytes('2G'));

        self::assertSame('1024', $this->upload->returnBytes('1024'));
    }
}
