<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Faq\Models;

class Faq extends \Ilch\Model
{
    protected $id;
    protected $catId;
    protected $question;
    protected $answer;


    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            $this->__set($key, $value);
        }
    }

    public function __set($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }

        return $this;
    }


    public function getId()
    {
        return $this->id;
    }

    public function getCatId()
    {
        return $this->catId;
    }


    public function getQuestion()
    {
        return $this->question;
    }


    public function getAnswer()
    {
        return $this->answer;
    }

}
