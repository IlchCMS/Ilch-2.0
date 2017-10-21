<?php
/**
 * @copyright Ilch 2.0
 * @package ilch_phpunit
 */

namespace Modules\Forum\Models;

use PHPUnit\Ilch\TestCase;
use Modules\Forum\Models\Rank as RankModel;

/**
 * Tests the rank model class.
 *
 * @package ilch_phpunit
 */
class RankTest extends TestCase
{
    /**
     * Tests if the rank model can save and return a id.
     */
    public function testId()
    {
        $model = new RankModel();
        $model->setId(1);

        $this->assertEquals(1, $model->getId(), 'The rank id was not saved correctly.');
    }

    /**
     * Tests if the rank model can save and return a title.
     */
    public function testTitle()
    {
        $model = new RankModel();
        $model->setTitle('TestTitle');

        $this->assertEquals('TestTitle', $model->getTitle(), 'The rank title was not saved correctly.');
    }

    /**
     * Tests if the rank model can save and return a title.
     */
    public function testPosts()
    {
        $model = new RankModel();
        $model->setPosts(100);

        $this->assertEquals(100, $model->getPosts(), 'The rank posts was not saved correctly.');
    }
}
