<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Teams\Models;

use PHPUnit\Framework\TestCase;

class JoinsModelTest extends TestCase
{
    /**
     * Tests that default values are 0 for int fields and empty string for string fields.
     */
    public function testDefaultValues()
    {
        $model = new Joins();

        self::assertSame(0, $model->getId());
        self::assertSame(0, $model->getUserId());
        self::assertSame('', $model->getName());
        self::assertSame('', $model->getEmail());
        self::assertSame(0, $model->getGender());
        self::assertSame('', $model->getBirthday());
        self::assertSame('', $model->getPlace());
        self::assertSame(0, $model->getSkill());
        self::assertSame(0, $model->getTeamId());
        self::assertSame('', $model->getLocale());
        self::assertSame('', $model->getDateCreated());
        self::assertSame('', $model->getText());
        self::assertSame(0, $model->getDecision());
        self::assertSame(0, $model->getUndecided());
    }

    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new Joins();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setUserId() sets and returns the userId.
     */
    public function testSetUserId()
    {
        $model = new Joins();
        $model->setUserId(4);

        self::assertSame(4, $model->getUserId());
    }

    /**
     * Tests that setName() sets and returns the name.
     */
    public function testSetName()
    {
        $model = new Joins();
        $model->setName('Testuser');

        self::assertSame('Testuser', $model->getName());
    }

    /**
     * Tests that setEmail() sets and returns the email.
     */
    public function testSetEmail()
    {
        $model = new Joins();
        $model->setEmail('Testuser@localhost.net');

        self::assertSame('Testuser@localhost.net', $model->getEmail());
    }

    /**
     * Tests that setGender() sets and returns the gender.
     */
    public function testSetGender()
    {
        $model = new Joins();
        $model->setGender(1);

        self::assertSame(1, $model->getGender());
    }

    /**
     * Tests that setBirthday() sets and returns the birthday.
     */
    public function testSetBirthday()
    {
        $model = new Joins();
        $model->setBirthday('2000-01-02');

        self::assertSame('2000-01-02', $model->getBirthday());
    }

    /**
     * Tests that setPlace() sets and returns the place.
     */
    public function testSetPlace()
    {
        $model = new Joins();
        $model->setPlace('Zuhause');

        self::assertSame('Zuhause', $model->getPlace());
    }

    /**
     * Tests that setSkill() sets and returns the skill.
     */
    public function testSetSkill()
    {
        $model = new Joins();
        $model->setSkill(2);

        self::assertSame(2, $model->getSkill());
    }

    /**
     * Tests that setTeamId() sets and returns the teamId.
     */
    public function testSetTeamId()
    {
        $model = new Joins();
        $model->setTeamId(2);

        self::assertSame(2, $model->getTeamId());
    }

    /**
     * Tests that setLocale() sets and returns the locale.
     */
    public function testSetLocale()
    {
        $model = new Joins();
        $model->setLocale('de_DE');

        self::assertSame('de_DE', $model->getLocale());
    }

    /**
     * Tests that setDateCreated() sets and returns the dateCreated.
     */
    public function testSetDateCreated()
    {
        $model = new Joins();
        $model->setDateCreated('2023-03-23 00:00:00');

        self::assertSame('2023-03-23 00:00:00', $model->getDateCreated());
    }

    /**
     * Tests that setText() sets and returns the text.
     */
    public function testSetText()
    {
        $model = new Joins();
        $model->setText('Application text');

        self::assertSame('Application text', $model->getText());
    }

    /**
     * Tests that setDecision() sets and returns the decision.
     */
    public function testSetDecision()
    {
        $model = new Joins();
        $model->setDecision(1);

        self::assertSame(1, $model->getDecision());
    }

    /**
     * Tests that setUndecided() sets and returns the undecided flag.
     */
    public function testSetUndecided()
    {
        $model = new Joins();
        $model->setUndecided(0);

        self::assertSame(0, $model->getUndecided());
    }

    /**
     * Tests that all setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new Joins();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setUserId(4));
        self::assertSame($model, $model->setName('Testuser'));
        self::assertSame($model, $model->setEmail('Testuser@localhost.net'));
        self::assertSame($model, $model->setGender(3));
        self::assertSame($model, $model->setBirthday('2000-01-02'));
        self::assertSame($model, $model->setPlace('Zuhause'));
        self::assertSame($model, $model->setSkill(1));
        self::assertSame($model, $model->setTeamId(2));
        self::assertSame($model, $model->setLocale('de_DE'));
        self::assertSame($model, $model->setDateCreated('2023-03-23 00:00:00'));
        self::assertSame($model, $model->setText(''));
        self::assertSame($model, $model->setDecision(0));
        self::assertSame($model, $model->setUndecided(1));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new Joins())
            ->setId(1)
            ->setUserId(4)
            ->setName('Testuser')
            ->setEmail('Testuser@localhost.net')
            ->setGender(3)
            ->setBirthday('2000-01-02')
            ->setPlace('Zuhause')
            ->setSkill(1)
            ->setTeamId(2)
            ->setLocale('de_DE')
            ->setDateCreated('2023-03-23 00:00:00')
            ->setText('')
            ->setDecision(0)
            ->setUndecided(1);

        self::assertSame(1, $model->getId());
        self::assertSame(4, $model->getUserId());
        self::assertSame('Testuser', $model->getName());
        self::assertSame('Testuser@localhost.net', $model->getEmail());
        self::assertSame(3, $model->getGender());
        self::assertSame('2000-01-02', $model->getBirthday());
        self::assertSame('Zuhause', $model->getPlace());
        self::assertSame(1, $model->getSkill());
        self::assertSame(2, $model->getTeamId());
        self::assertSame('de_DE', $model->getLocale());
        self::assertSame('2023-03-23 00:00:00', $model->getDateCreated());
        self::assertSame('', $model->getText());
        self::assertSame(0, $model->getDecision());
        self::assertSame(1, $model->getUndecided());
    }

    /**
     * Tests that getArray() returns all fields including the id.
     */
    public function testGetArrayWithId()
    {
        $model = new Joins();
        $model->setId(1);
        $model->setUserId(4);
        $model->setName('Testuser');
        $model->setEmail('Testuser@localhost.net');
        $model->setGender(3);
        $model->setBirthday('2000-01-02');
        $model->setPlace('Zuhause');
        $model->setSkill(1);
        $model->setTeamId(2);
        $model->setLocale('de_DE');
        $model->setDateCreated('2023-03-23 00:00:00');
        $model->setText('');
        $model->setDecision(0);
        $model->setUndecided(1);

        self::assertSame([
            'id' => 1,
            'userId' => 4,
            'name' => 'Testuser',
            'email' => 'Testuser@localhost.net',
            'gender' => 3,
            'birthday' => '2000-01-02',
            'place' => 'Zuhause',
            'skill' => 1,
            'teamId' => 2,
            'locale' => 'de_DE',
            'dateCreated' => '2023-03-23 00:00:00',
            'text' => '',
            'decision' => 0,
            'undecided' => 1,
        ], $model->getArray());
    }

    /**
     * Tests that getArray(false) omits the id.
     */
    public function testGetArrayWithoutId()
    {
        $model = new Joins();
        $model->setId(1);
        $model->setTeamId(2);

        $array = $model->getArray(false);

        self::assertArrayNotHasKey('id', $array);
        self::assertSame(2, $array['teamId']);
    }

    /**
     * Tests that setByArray() fills the model from a complete row.
     */
    public function testSetByArrayFull()
    {
        $model = new Joins();
        $result = $model->setByArray([
            'id' => 1,
            'userId' => 4,
            'name' => 'Testuser',
            'email' => 'Testuser@localhost.net',
            'gender' => 3,
            'birthday' => '2000-01-02',
            'place' => 'Zuhause',
            'skill' => 1,
            'teamId' => 2,
            'locale' => 'de_DE',
            'dateCreated' => '2023-03-23 00:00:00',
            'text' => '',
            'decision' => 0,
            'undecided' => 1,
        ]);

        self::assertSame($model, $result);
        self::assertSame(1, $model->getId());
        self::assertSame(4, $model->getUserId());
        self::assertSame('Testuser', $model->getName());
        self::assertSame('Testuser@localhost.net', $model->getEmail());
        self::assertSame(3, $model->getGender());
        self::assertSame('2000-01-02', $model->getBirthday());
        self::assertSame('Zuhause', $model->getPlace());
        self::assertSame(1, $model->getSkill());
        self::assertSame(2, $model->getTeamId());
        self::assertSame('de_DE', $model->getLocale());
        self::assertSame('2023-03-23 00:00:00', $model->getDateCreated());
        self::assertSame('', $model->getText());
        self::assertSame(0, $model->getDecision());
        self::assertSame(1, $model->getUndecided());
    }

    /**
     * Tests that setByArray() only changes the keys that are present.
     */
    public function testSetByArrayPartial()
    {
        $model = new Joins();
        $model->setByArray(['name' => 'Partial', 'teamId' => 5]);

        self::assertSame('Partial', $model->getName());
        self::assertSame(5, $model->getTeamId());
        self::assertSame(0, $model->getId());
        self::assertSame('', $model->getEmail());
        self::assertSame(0, $model->getUndecided());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new Joins();
        $model->setName('Old')->setTeamId(1)->setDecision(1);

        $model->setName('New')->setTeamId(2)->setDecision(2);

        self::assertSame('New', $model->getName());
        self::assertSame(2, $model->getTeamId());
        self::assertSame(2, $model->getDecision());
    }
}
