<?php

/**
 * @copyright Ilch 2
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
    protected $id = 0;

    /**
     * The cat_id of the faq.
     *
     * @var int
     */
    protected $catId = 0;

    /**
     * The question of the faq.
     *
     * @var string
     */
    protected $question = '';

    /**
     * The answer of the faq.
     *
     * @var string
     */
    protected $answer = '';

    /**
     * Value for read_access.
     *
     * @var string
     * @since 1.9.0
     */
    private $read_access = '';

    /**
     * @param array $entries
     * @return $this
     * @since 1.9.0
     */
    public function setByArray(array $entries): Faq
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['cat_id'])) {
            $this->setCatId($entries['cat_id']);
        }
        if (isset($entries['question'])) {
            $this->setQuestion($entries['question']);
        }
        if (isset($entries['answer'])) {
            $this->setAnswer($entries['answer']);
        }
        if (isset($entries['read_access'])) {
            $this->setReadAccess($entries['read_access']);
        }
        if (isset($entries['read_access_all'])) {
            if ($entries['read_access_all']) {
                $this->setReadAccess('all');
            }
        }

        return $this;
    }

    /**
     * Gets the id of the faq.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the id of the faq.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Faq
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the catId of the faq.
     *
     * @return int
     */
    public function getCatId(): int
    {
        return $this->catId;
    }

    /**
     * Sets the catId of the faq.
     *
     * @param int $catId
     * @return $this
     */
    public function setCatId(int $catId): Faq
    {
        $this->catId = $catId;

        return $this;
    }

    /**
     * Gets the question of the faq.
     *
     * @return string
     */
    public function getQuestion(): string
    {
        return $this->question;
    }

    /**
     * Sets the question of the faq.
     *
     * @param string $question
     * @return $this
     */
    public function setQuestion(string $question): Faq
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Gets the answer of the faq.
     *
     * @return string
     */
    public function getAnswer(): string
    {
        return $this->answer;
    }

    /**
     * Sets the answer of the faq.
     *
     * @param string $answer
     * @return $this
     */
    public function setAnswer(string $answer): Faq
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get the value for read_access.
     *
     * @return string
     * @since 1.9.0
     */
    public function getReadAccess(): string
    {
        return $this->read_access;
    }

    /**
     * Set the value for read_access.
     *
     * @param string $read_access
     * @return $this
     * @since 1.9.0
     */
    public function setReadAccess(string $read_access): Faq
    {
        $this->read_access = $read_access;
        return $this;
    }

    /**
     * @param bool $withId
     * @return array
     * @since 1.9.0
     */
    public function getArray(bool $withId = true): array
    {
        return array_merge(
            ($withId ? ['id' => $this->getId()] : []),
            [
                'cat_id'    => $this->getCatId(),
                'question'  => $this->getQuestion(),
                'answer'    => $this->getAnswer(),
            ]
        );
    }
}
