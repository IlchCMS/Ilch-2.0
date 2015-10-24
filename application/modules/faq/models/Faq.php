<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Faq\Models;

class Faq extends \Ilch\Model
{
    /**
     * The id of the faq.
     *
     * @var int
     */
    protected $id;

    /**
     * The cat_id of the faq.
     *
     * @var int
     */
    protected $catId;

    /**
     * The question of the faq.
     *
     * @var string
     */
    protected $question;

    /**
     * The answer of the faq.
     *
     * @var string
     */
    protected $answer;

    /**
     * Gets the id of the faq.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the faq.
     *
     * @param int $id
     * @return this
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * Gets the catId of the faq.
     *
     * @return int
     */
    public function getCatId()
    {
        return $this->catId;
    }

    /**
     * Sets the catId of the faq.
     *
     * @param int $catId
     * @return this
     */
    public function setCatId($catId)
    {
        $this->catId = (int)$catId;

        return $this;
    }

    /**
     * Gets the question of the faq.
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Sets the question of the faq.
     *
     * @param string $question
     * @return this
     */
    public function setQuestion($question)
    {
        $this->question = (string)$question;

        return $this;
    }

    /**
     * Gets the answer of the faq.
     *
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Sets the answer of the faq.
     *
     * @param string $answer
     * @return this
     */
    public function setAnswer($answer)
    {
        $this->answer = (string)$answer;

        return $this;
    }
}
