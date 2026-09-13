<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Events\Tests;

use PHPUnit\Framework\TestCase;
use Modules\Events\Models\Events;

class EventsModelTest extends TestCase
{
    private function createFullyPopulatedModel(): Events
    {
        $model = new Events();
        $model->setId(1);
        $model->setUserId(2);
        $model->setStart('2026-10-01 10:00:00');
        $model->setEnd('2026-10-01 18:00:00');
        $model->setTitle('Test Event');
        $model->setPlace('Berlin');
        $model->setType('Conference');
        $model->setWebsite('https://example.com');
        $model->setLatLong('52.52,13.40');
        $model->setImage('/path/to/image.jpg');
        $model->setText('Event description');
        $model->setCurrency(1);
        $model->setPrice('50');
        $model->setPriceArt(1);
        $model->setShow(1);
        $model->setUserLimit(100);
        $model->setReadAccess('2,3');

        return $model;
    }

    /**
     * Tests that all default property values are correct.
     */
    public function testDefaults()
    {
        $model = new Events();

        self::assertEquals(0, $model->getId());
        self::assertEquals(0, $model->getUserId());
        self::assertEquals('', $model->getStart());
        self::assertEquals('', $model->getEnd());
        self::assertEquals('', $model->getTitle());
        self::assertEquals('', $model->getPlace());
        self::assertEquals('', $model->getType());
        self::assertEquals('', $model->getWebsite());
        self::assertEquals('', $model->getLatLong());
        self::assertEquals('', $model->getImage());
        self::assertEquals('', $model->getText());
        self::assertEquals(0, $model->getCurrency());
        self::assertEquals('', $model->getPrice());
        self::assertEquals(0, $model->getPriceArt());
        self::assertEquals(0, $model->getShow());
        self::assertEquals(0, $model->getUserLimit());
        self::assertEquals('', $model->getReadAccess());
    }

    /**
     * Tests that all setters store the value and return $this.
     */
    public function testAllSettersReturnThis()
    {
        $model = new Events();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setUserId(2));
        self::assertSame($model, $model->setStart('2026-10-01'));
        self::assertSame($model, $model->setEnd('2026-10-02'));
        self::assertSame($model, $model->setTitle('Title'));
        self::assertSame($model, $model->setPlace('Place'));
        self::assertSame($model, $model->setType('Type'));
        self::assertSame($model, $model->setWebsite('url'));
        self::assertSame($model, $model->setLatLong('1,2'));
        self::assertSame($model, $model->setImage('img'));
        self::assertSame($model, $model->setText('text'));
        self::assertSame($model, $model->setCurrency(3));
        self::assertSame($model, $model->setPrice('99'));
        self::assertSame($model, $model->setPriceArt(1));
        self::assertSame($model, $model->setShow(1));
        self::assertSame($model, $model->setUserLimit(50));
        self::assertSame($model, $model->setReadAccess('2,3'));
    }

    /**
     * Tests that all getters return the values set by setters.
     */
    public function testAllGetters()
    {
        $model = $this->createFullyPopulatedModel();

        self::assertEquals(1, $model->getId());
        self::assertEquals(2, $model->getUserId());
        self::assertEquals('2026-10-01 10:00:00', $model->getStart());
        self::assertEquals('2026-10-01 18:00:00', $model->getEnd());
        self::assertEquals('Test Event', $model->getTitle());
        self::assertEquals('Berlin', $model->getPlace());
        self::assertEquals('Conference', $model->getType());
        self::assertEquals('https://example.com', $model->getWebsite());
        self::assertEquals('52.52,13.40', $model->getLatLong());
        self::assertEquals('/path/to/image.jpg', $model->getImage());
        self::assertEquals('Event description', $model->getText());
        self::assertEquals(1, $model->getCurrency());
        self::assertEquals('50', $model->getPrice());
        self::assertEquals(1, $model->getPriceArt());
        self::assertEquals(1, $model->getShow());
        self::assertEquals(100, $model->getUserLimit());
        self::assertEquals('2,3', $model->getReadAccess());
    }

    /**
     * Tests that setByArray() populates all fields from a complete array.
     */
    public function testSetByArrayComplete()
    {
        $model = new Events();
        $result = $model->setByArray([
            'id'          => 5,
            'user_id'     => 3,
            'start'       => '2026-05-01 09:00:00',
            'end'         => '2026-05-01 17:00:00',
            'title'       => 'Full Event',
            'place'       => 'Hamburg',
            'type'        => 'Festival',
            'website'     => 'https://fest.example.com',
            'lat_long'    => '53.55,9.99',
            'image'       => '/img/fest.jpg',
            'text'        => 'A festival event',
            'currency'    => 2,
            'price'       => '25',
            'price_art'   => 1,
            'show'        => 1,
            'user_limit'  => 50,
            'read_access' => '2,3',
        ]);

        self::assertSame($model, $result);
        self::assertEquals(5, $model->getId());
        self::assertEquals(3, $model->getUserId());
        self::assertEquals('2026-05-01 09:00:00', $model->getStart());
        self::assertEquals('2026-05-01 17:00:00', $model->getEnd());
        self::assertEquals('Full Event', $model->getTitle());
        self::assertEquals('Hamburg', $model->getPlace());
        self::assertEquals('Festival', $model->getType());
        self::assertEquals('https://fest.example.com', $model->getWebsite());
        self::assertEquals('53.55,9.99', $model->getLatLong());
        self::assertEquals('/img/fest.jpg', $model->getImage());
        self::assertEquals('A festival event', $model->getText());
        self::assertEquals(2, $model->getCurrency());
        self::assertEquals('25', $model->getPrice());
        self::assertEquals(1, $model->getPriceArt());
        self::assertEquals(1, $model->getShow());
        self::assertEquals(50, $model->getUserLimit());
        self::assertEquals('2,3', $model->getReadAccess());
    }

    /**
     * Tests that setByArray() only updates keys that are present.
     */
    public function testSetByArrayPartial()
    {
        $model = new Events();
        $model->setId(99);
        $model->setTitle('Original Title');
        $model->setPrice('100');

        $model->setByArray([
            'title' => 'Changed Title',
        ]);

        self::assertEquals(99, $model->getId());
        self::assertEquals('Changed Title', $model->getTitle());
        self::assertEquals('100', $model->getPrice());
    }

    /**
     * Tests that setByArray() with an empty array leaves defaults intact.
     */
    public function testSetByArrayEmpty()
    {
        $model = new Events();
        $model->setByArray([]);

        self::assertEquals(0, $model->getId());
        self::assertEquals('', $model->getTitle());
        self::assertEquals(0, $model->getShow());
    }

    /**
     * Tests that getArray() includes id when $withId is true (default).
     */
    public function testGetArrayWithId()
    {
        $model = $this->createFullyPopulatedModel();
        $array = $model->getArray();

        self::assertArrayHasKey('id', $array);
        self::assertEquals(1, $array['id']);
        self::assertEquals('Test Event', $array['title']);
        self::assertEquals('2,3', $array['read_access']);
    }

    /**
     * Tests that getArray() excludes id when $withId is false.
     */
    public function testGetArrayWithoutId()
    {
        $model = $this->createFullyPopulatedModel();
        $array = $model->getArray(false);

        self::assertArrayNotHasKey('id', $array);
        self::assertEquals('Test Event', $array['title']);
    }

    /**
     * Tests that getArray() with defaults returns all zero/empty values.
     */
    public function testGetArrayDefaults()
    {
        $model = new Events();
        $array = $model->getArray();

        self::assertEquals(0, $array['id']);
        self::assertEquals(0, $array['user_id']);
        self::assertEquals('', $array['start']);
        self::assertEquals('', $array['end']);
        self::assertEquals('', $array['title']);
        self::assertEquals('', $array['place']);
        self::assertEquals('', $array['type']);
        self::assertEquals('', $array['website']);
        self::assertEquals('', $array['lat_long']);
        self::assertEquals('', $array['image']);
        self::assertEquals('', $array['text']);
        self::assertEquals(0, $array['currency']);
        self::assertEquals('', $array['price']);
        self::assertEquals(0, $array['price_art']);
        self::assertEquals(0, $array['show']);
        self::assertEquals(0, $array['user_limit']);
        self::assertEquals('', $array['read_access']);
    }

    /**
     * Tests that getArray(false) with defaults has no id key.
     */
    public function testGetArrayWithoutIdDefaults()
    {
        $model = new Events();
        $array = $model->getArray(false);

        self::assertArrayNotHasKey('id', $array);
        self::assertCount(16, $array);
    }

    /**
     * Tests a full round-trip: set → getArray → setByArray → getArray.
     */
    public function testFullRoundTrip()
    {
        $model = $this->createFullyPopulatedModel();
        $array = $model->getArray();

        $model2 = new Events();
        $model2->setByArray($array);

        self::assertEquals($array, $model2->getArray());
    }

    /**
     * Tests that string setters accept empty strings.
     */
    public function testSettersAcceptEmptyStrings()
    {
        $model = new Events();
        $model->setTitle('');
        $model->setPlace('');
        $model->setType('');
        $model->setWebsite('');
        $model->setLatLong('');
        $model->setImage('');
        $model->setText('');
        $model->setPrice('');
        $model->setReadAccess('');

        self::assertEquals('', $model->getTitle());
        self::assertEquals('', $model->getPlace());
        self::assertEquals('', $model->getType());
        self::assertEquals('', $model->getWebsite());
        self::assertEquals('', $model->getLatLong());
        self::assertEquals('', $model->getImage());
        self::assertEquals('', $model->getText());
        self::assertEquals('', $model->getPrice());
        self::assertEquals('', $model->getReadAccess());
    }
}
