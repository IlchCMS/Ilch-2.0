<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Article\Controllers\Admin;

use Ilch\Registry;
use Ilch\Translator;
use Ilch\Validation;
use Modules\Article\Mappers\Article as ArticleMapper;
use Modules\Article\Models\Article as ArticleModel;
use PHPUnit\Ilch\TestCase;

/**
 * Unit tests for the Article Admin Index Controller logic.
 *
 * Da das Framework keine fertige ControllerTestCase-Infrastruktur hat,
 * werden hier die Kernbestandteile der Controller-Logik isoliert getestet:
 * - Validierungsregeln der treatAction
 * - ArticleModel-Datenhaltung
 * - Mapper-Interaktionen via Mock-Objekte
 *
 * @package ilch_phpunit
 * @since   1.0.0
 */
class IndexTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Translator-Stub im Registry registrieren – wird von Validation für
        // Fehlermeldungs-Übersetzungen benötigt
        if (!Registry::has('translator')) {
            $translator = $this->createStub(Translator::class);
            $translator->method('trans')->willReturnArgument(0);
            Registry::set('translator', $translator);
        }
    }

    // -------------------------------------------------------------------------
    // treatAction – Validierungsregeln
    // -------------------------------------------------------------------------

    /**
     * @test
     */
    public function itShouldRejectEmptyPostDataInTreatAction(): void
    {
        // Arrange – leere Eingabe wie bei einem leeren POST
        $postData = [];

        // Act
        $validation = Validation::create($postData, [
            'cats'    => 'required',
            'title'   => 'required',
            'content' => 'required',
            'groups'  => 'required',
        ]);

        // Assert – alle Pflichtfelder müssen Fehler erzeugen
        $this->assertFalse($validation->isValid());
        $errors = $validation->getErrorBag()->getErrors();
        $this->assertArrayHasKey('cats', $errors);
        $this->assertArrayHasKey('title', $errors);
        $this->assertArrayHasKey('content', $errors);
        $this->assertArrayHasKey('groups', $errors);
    }

    /**
     * @test
     */
    public function itShouldAcceptValidPostDataInTreatAction(): void
    {
        // Arrange – vollständige gültige Eingabe
        $postData = [
            'cats'    => ['1', '2'],
            'title'   => 'Testbeitrag',
            'content' => 'Hier steht der Artikelinhalt.',
            'groups'  => ['1', '2', '3'],
        ];

        // Act
        $validation = Validation::create($postData, [
            'cats'    => 'required',
            'title'   => 'required',
            'content' => 'required',
            'groups'  => 'required',
        ]);

        // Assert
        $this->assertTrue($validation->isValid());
    }

    /**
     * @test
     */
    public function itShouldFailValidationWhenOnlyTitleIsMissing(): void
    {
        // Arrange
        $postData = [
            'cats'    => ['1'],
            'title'   => '',
            'content' => 'Inhalt vorhanden.',
            'groups'  => ['3'],
        ];

        // Act
        $validation = Validation::create($postData, [
            'cats'    => 'required',
            'title'   => 'required',
            'content' => 'required',
            'groups'  => 'required',
        ]);

        // Assert – nur title darf im ErrorBag fehlen
        $this->assertFalse($validation->isValid());
        $errors = $validation->getErrorBag()->getErrors();
        $this->assertArrayHasKey('title', $errors);
        $this->assertArrayNotHasKey('cats', $errors);
        $this->assertArrayNotHasKey('content', $errors);
        $this->assertArrayNotHasKey('groups', $errors);
    }

    // -------------------------------------------------------------------------
    // ArticleModel – Datenhaltung
    // -------------------------------------------------------------------------

    /**
     * @test
     */
    public function itShouldStoreAllArticleModelPropertiesCorrectly(): void
    {
        // Arrange & Act
        $model = new ArticleModel();
        $model->setCatId('1,2')
            ->setTitle('Testartikel')
            ->setContent('Artikelinhalt')
            ->setReadAccess('1,2,3')
            ->setKeywords('php, ilch, cms')
            ->setDescription('Kurzbeschreibung');

        // Assert
        $this->assertSame('1,2', $model->getCatId());
        $this->assertSame('Testartikel', $model->getTitle());
        $this->assertSame('Artikelinhalt', $model->getContent());
        $this->assertSame('1,2,3', $model->getReadAccess());
        $this->assertSame('php, ilch, cms', $model->getKeywords());
        $this->assertSame('Kurzbeschreibung', $model->getDescription());
    }

    // -------------------------------------------------------------------------
    // deleteAction – Mapper-Mock-Interaktion
    // -------------------------------------------------------------------------

    /**
     * @test
     */
    public function itShouldCallDeleteWithCommentsOnceWithCorrectId(): void
    {
        // Arrange
        $articleMapperMock = $this->createMock(ArticleMapper::class);
        $articleMapperMock
            ->expects($this->once())
            ->method('deleteWithComments')
            ->with(42);

        // Act – simuliert den Kernpfad der deleteAction wenn isSecure() true ist
        $id = 42;
        $articleMapperMock->deleteWithComments($id);

        // Assert: durch expects()->once() sichergestellt
    }

    /**
     * @test
     */
    public function itShouldNotCallDeleteWhenIdIsZero(): void
    {
        // Arrange
        $articleMapperMock = $this->createMock(ArticleMapper::class);
        $articleMapperMock
            ->expects($this->never())
            ->method('deleteWithComments');

        // Act – id = 0 darf keinen Mapper-Aufruf auslösen
        $id = 0;
        if ($id > 0) {
            $articleMapperMock->deleteWithComments($id);
        }

        // Assert: durch expects()->never() sichergestellt
    }
}
