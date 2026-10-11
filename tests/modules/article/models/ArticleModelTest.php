<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Article\Models;

use PHPUnit\Framework\TestCase;
use Modules\Article\Models\Article as ArticleModel;

class ArticleModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new ArticleModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new ArticleModel();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new ArticleModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setCatId() sets and returns the catId.
     */
    public function testSetCatId()
    {
        $model = new ArticleModel();
        $model->setCatId('2');

        self::assertSame('2', $model->getCatId());
    }

    /**
     * Tests that setCatId() casts to string.
     */
    public function testSetCatIdCastsToString()
    {
        $model = new ArticleModel();
        $model->setCatId(2);

        self::assertSame('2', $model->getCatId());
        self::assertIsString($model->getCatId());
    }

    /**
     * Tests that setAuthorId() sets and returns the authorId.
     */
    public function testSetAuthorId()
    {
        $model = new ArticleModel();
        $model->setAuthorId(7);

        self::assertSame(7, $model->getAuthorId());
    }

    /**
     * Tests that setAuthorId() casts to int.
     */
    public function testSetAuthorIdCastsToInt()
    {
        $model = new ArticleModel();
        $model->setAuthorId('7');

        self::assertSame(7, $model->getAuthorId());
        self::assertIsInt($model->getAuthorId());
    }

    /**
     * Tests that setAuthorName() sets and returns the authorName.
     */
    public function testSetAuthorName()
    {
        $model = new ArticleModel();
        $model->setAuthorName('Ilch Admin');

        self::assertSame('Ilch Admin', $model->getAuthorName());
    }

    /**
     * Tests that setAuthorName() casts to string.
     * Currently fails: no (string) cast in setAuthorName() (see bug report).
     */
    public function testSetAuthorNameCastsToString()
    {
        $model = new ArticleModel();
        $model->setAuthorName(123);

        self::assertSame('123', $model->getAuthorName());
        self::assertIsString($model->getAuthorName());
    }

    /**
     * Tests that setVisits() sets and returns the visits.
     */
    public function testSetVisits()
    {
        $model = new ArticleModel();
        $model->setVisits(42);

        self::assertSame(42, $model->getVisits());
    }

    /**
     * Tests that setVisits() casts to int.
     */
    public function testSetVisitsCastsToInt()
    {
        $model = new ArticleModel();
        $model->setVisits('42');

        self::assertSame(42, $model->getVisits());
        self::assertIsInt($model->getVisits());
    }

    /**
     * Tests that setPerma() sets and returns the perma.
     */
    public function testSetPerma()
    {
        $model = new ArticleModel();
        $model->setPerma('willkommen.html');

        self::assertSame('willkommen.html', $model->getPerma());
    }

    /**
     * Tests that setPerma() casts to string.
     * Currently fails: no (string) cast in setPerma() (see bug report).
     */
    public function testSetPermaCastsToString()
    {
        $model = new ArticleModel();
        $model->setPerma(123);

        self::assertSame('123', $model->getPerma());
        self::assertIsString($model->getPerma());
    }

    /**
     * Tests that setTitle() sets and returns the title.
     */
    public function testSetTitle()
    {
        $model = new ArticleModel();
        $model->setTitle('Willkommen');

        self::assertSame('Willkommen', $model->getTitle());
    }

    /**
     * Tests that setTitle() casts to string.
     */
    public function testSetTitleCastsToString()
    {
        $model = new ArticleModel();
        $model->setTitle(123);

        self::assertSame('123', $model->getTitle());
        self::assertIsString($model->getTitle());
    }

    /**
     * Tests that setTeaser() sets and returns the teaser.
     */
    public function testSetTeaser()
    {
        $model = new ArticleModel();
        $model->setTeaser('Willkommen beim Ilch CMS!');

        self::assertSame('Willkommen beim Ilch CMS!', $model->getTeaser());
    }

    /**
     * Tests that setTeaser() casts to string.
     */
    public function testSetTeaserCastsToString()
    {
        $model = new ArticleModel();
        $model->setTeaser(123);

        self::assertSame('123', $model->getTeaser());
        self::assertIsString($model->getTeaser());
    }

    /**
     * Tests that setContent() sets and returns the content.
     */
    public function testSetContent()
    {
        $model = new ArticleModel();
        $model->setContent('<p>Dies ist dein erster Artikel</p>');

        self::assertSame('<p>Dies ist dein erster Artikel</p>', $model->getContent());
    }

    /**
     * Tests that setContent() casts to string.
     */
    public function testSetContentCastsToString()
    {
        $model = new ArticleModel();
        $model->setContent(123);

        self::assertSame('123', $model->getContent());
        self::assertIsString($model->getContent());
    }

    /**
     * Tests that setDescription() sets and returns the description.
     */
    public function testSetDescription()
    {
        $model = new ArticleModel();
        $model->setDescription('Description text');

        self::assertSame('Description text', $model->getDescription());
    }

    /**
     * Tests that setDescription() casts to string.
     */
    public function testSetDescriptionCastsToString()
    {
        $model = new ArticleModel();
        $model->setDescription(123);

        self::assertSame('123', $model->getDescription());
        self::assertIsString($model->getDescription());
    }

    /**
     * Tests that setKeywords() sets and returns the keywords.
     */
    public function testSetKeywords()
    {
        $model = new ArticleModel();
        $model->setKeywords('willkommen, ilch, cms');

        self::assertSame('willkommen, ilch, cms', $model->getKeywords());
    }

    /**
     * Tests that setKeywords() casts to string.
     */
    public function testSetKeywordsCastsToString()
    {
        $model = new ArticleModel();
        $model->setKeywords(123);

        self::assertSame('123', $model->getKeywords());
        self::assertIsString($model->getKeywords());
    }

    /**
     * Tests that setLocale() sets and returns the locale.
     */
    public function testSetLocale()
    {
        $model = new ArticleModel();
        $model->setLocale('de_DE');

        self::assertSame('de_DE', $model->getLocale());
    }

    /**
     * Tests that setLocale() casts to string.
     */
    public function testSetLocaleCastsToString()
    {
        $model = new ArticleModel();
        $model->setLocale(123);

        self::assertSame('123', $model->getLocale());
        self::assertIsString($model->getLocale());
    }

    /**
     * Tests that setDateCreated() sets and returns the dateCreated.
     */
    public function testSetDateCreated()
    {
        $model = new ArticleModel();
        $model->setDateCreated('2024-01-01 08:00:00');

        self::assertSame('2024-01-01 08:00:00', $model->getDateCreated());
    }

    /**
     * Tests that setDateCreated() casts to string.
     * Currently fails: no (string) cast in setDateCreated() (see bug report).
     */
    public function testSetDateCreatedCastsToString()
    {
        $model = new ArticleModel();
        $model->setDateCreated(20240101);

        self::assertSame('20240101', $model->getDateCreated());
        self::assertIsString($model->getDateCreated());
    }

    /**
     * Tests that setTopArticle() sets and returns the top flag.
     */
    public function testSetTopArticle()
    {
        $model = new ArticleModel();
        $model->setTopArticle(true);

        self::assertTrue($model->getTopArticle());
    }

    /**
     * Tests that setTopArticle() casts to boolean.
     * Currently fails: no (bool) cast in setTopArticle() (see bug report).
     */
    public function testSetTopArticleCastsToBool()
    {
        $model = new ArticleModel();
        $model->setTopArticle('1');

        self::assertSame(true, $model->getTopArticle());
        self::assertIsBool($model->getTopArticle());
    }

    /**
     * Tests that setCommentsDisabled() sets and returns the commentsDisabled flag.
     */
    public function testSetCommentsDisabled()
    {
        $model = new ArticleModel();
        $model->setCommentsDisabled(false);

        self::assertFalse($model->getCommentsDisabled());
    }

    /**
     * Tests that setCommentsDisabled() casts to boolean.
     * Currently fails: no (bool) cast in setCommentsDisabled() (see bug report).
     */
    public function testSetCommentsDisabledCastsToBool()
    {
        $model = new ArticleModel();
        $model->setCommentsDisabled('1');

        self::assertSame(true, $model->getCommentsDisabled());
        self::assertIsBool($model->getCommentsDisabled());
    }

    /**
     * Tests that setReadAccess() sets and returns the readAccess.
     */
    public function testSetReadAccess()
    {
        $model = new ArticleModel();
        $model->setReadAccess('1,2,3');

        self::assertSame('1,2,3', $model->getReadAccess());
    }

    /**
     * Tests that setReadAccess() casts to string.
     */
    public function testSetReadAccessCastsToString()
    {
        $model = new ArticleModel();
        $model->setReadAccess(123);

        self::assertSame('123', $model->getReadAccess());
        self::assertIsString($model->getReadAccess());
    }

    /**
     * Tests that setImage() sets and returns the image.
     */
    public function testSetImage()
    {
        $model = new ArticleModel();
        $model->setImage('article_1.jpg');

        self::assertSame('article_1.jpg', $model->getImage());
    }

    /**
     * Tests that setImage() casts to string.
     * Currently fails: no (string) cast in setImage() (see bug report).
     */
    public function testSetImageCastsToString()
    {
        $model = new ArticleModel();
        $model->setImage(123);

        self::assertSame('123', $model->getImage());
        self::assertIsString($model->getImage());
    }

    /**
     * Tests that setImageThumb() sets and returns the imageThumb.
     */
    public function testSetImageThumb()
    {
        $model = new ArticleModel();
        $model->setImageThumb('article_1_thumb.jpg');

        self::assertSame('article_1_thumb.jpg', $model->getImageThumb());
    }

    /**
     * Tests that setImageThumb() casts to string.
     * Currently fails: no (string) cast in setImageThumb() (see bug report).
     */
    public function testSetImageThumbCastsToString()
    {
        $model = new ArticleModel();
        $model->setImageThumb(123);

        self::assertSame('123', $model->getImageThumb());
        self::assertIsString($model->getImageThumb());
    }

    /**
     * Tests that setImageSource() sets and returns the imageSource.
     */
    public function testSetImageSource()
    {
        $model = new ArticleModel();
        $model->setImageSource('Ilch Community');

        self::assertSame('Ilch Community', $model->getImageSource());
    }

    /**
     * Tests that setImageSource() casts to string.
     * Currently fails: no (string) cast in setImageSource() (see bug report).
     */
    public function testSetImageSourceCastsToString()
    {
        $model = new ArticleModel();
        $model->setImageSource(123);

        self::assertSame('123', $model->getImageSource());
        self::assertIsString($model->getImageSource());
    }

    /**
     * Tests that setVotes() sets and returns the votes.
     */
    public function testSetVotes()
    {
        $model = new ArticleModel();
        $model->setVotes('{"1": 2, "2": 1}');

        self::assertSame('{"1": 2, "2": 1}', $model->getVotes());
    }

    /**
     * Tests that setVotes() casts to string.
     * Currently fails: no (string) cast in setVotes() (see bug report).
     */
    public function testSetVotesCastsToString()
    {
        $model = new ArticleModel();
        $model->setVotes(123);

        self::assertSame('123', $model->getVotes());
        self::assertIsString($model->getVotes());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new ArticleModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setCatId('1'));
        self::assertSame($model, $model->setAuthorId(1));
        self::assertSame($model, $model->setAuthorName('Admin'));
        self::assertSame($model, $model->setVisits(0));
        self::assertSame($model, $model->setPerma('willkommen.html'));
        self::assertSame($model, $model->setTitle('Willkommen'));
        self::assertSame($model, $model->setTeaser('Teaser'));
        self::assertSame($model, $model->setContent('Content'));
        self::assertSame($model, $model->setDescription('Description'));
        self::assertSame($model, $model->setKeywords('keywords'));
        self::assertSame($model, $model->setLocale('de_DE'));
        self::assertSame($model, $model->setDateCreated('2024-01-01 08:00:00'));
        self::assertSame($model, $model->setTopArticle(false));
        self::assertSame($model, $model->setCommentsDisabled(false));
        self::assertSame($model, $model->setReadAccess('1,2,3'));
        self::assertSame($model, $model->setImage('image.jpg'));
        self::assertSame($model, $model->setImageThumb('thumb.jpg'));
        self::assertSame($model, $model->setImageSource('Source'));
        self::assertSame($model, $model->setVotes('[]'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new ArticleModel())
            ->setId(1)
            ->setCatId('1')
            ->setAuthorId(1)
            ->setAuthorName('Ilch Admin')
            ->setVisits(5)
            ->setPerma('willkommen.html')
            ->setTitle('Willkommen')
            ->setTeaser('Willkommen beim Ilch CMS!')
            ->setContent('<p>Dies ist dein erster Artikel</p>')
            ->setKeywords('willkommen, ilch, cms')
            ->setLocale('de_DE')
            ->setDateCreated('2024-01-01 08:00:00')
            ->setTopArticle(false)
            ->setCommentsDisabled(false);

        self::assertSame(1, $model->getId());
        self::assertSame('1', $model->getCatId());
        self::assertSame(1, $model->getAuthorId());
        self::assertSame('Ilch Admin', $model->getAuthorName());
        self::assertSame(5, $model->getVisits());
        self::assertSame('willkommen.html', $model->getPerma());
        self::assertSame('Willkommen', $model->getTitle());
        self::assertSame('Willkommen beim Ilch CMS!', $model->getTeaser());
        self::assertSame('<p>Dies ist dein erster Artikel</p>', $model->getContent());
        self::assertSame('willkommen, ilch, cms', $model->getKeywords());
        self::assertSame('de_DE', $model->getLocale());
        self::assertSame('2024-01-01 08:00:00', $model->getDateCreated());
        self::assertFalse($model->getTopArticle());
        self::assertFalse($model->getCommentsDisabled());
    }

    /**
     * Tests that default values are null for all properties.
     */
    public function testDefaultValues()
    {
        $model = new ArticleModel();

        self::assertNull($model->getId());
        self::assertNull($model->getCatId());
        self::assertNull($model->getAuthorId());
        self::assertNull($model->getAuthorName());
        self::assertNull($model->getVisits());
        self::assertNull($model->getPerma());
        self::assertNull($model->getTitle());
        self::assertNull($model->getTeaser());
        self::assertNull($model->getContent());
        self::assertNull($model->getDescription());
        self::assertNull($model->getKeywords());
        self::assertNull($model->getLocale());
        self::assertNull($model->getDateCreated());
        self::assertNull($model->getTopArticle());
        self::assertNull($model->getCommentsDisabled());
        self::assertNull($model->getReadAccess());
        self::assertNull($model->getImage());
        self::assertNull($model->getImageThumb());
        self::assertNull($model->getImageSource());
        self::assertNull($model->getVotes());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new ArticleModel();
        $model->setId(1)->setTitle('Old Title')->setTeaser('Old Teaser');

        $model->setId(2)->setTitle('New Title')->setTeaser('New Teaser');

        self::assertSame(2, $model->getId());
        self::assertSame('New Title', $model->getTitle());
        self::assertSame('New Teaser', $model->getTeaser());
    }
}
