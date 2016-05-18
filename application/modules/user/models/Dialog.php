<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Models;

class Dialog extends \Ilch\Model
{
    /**
    * ID of the dialog
    */
    private $id;

    /**
    * C_ID of the dialog
    */
    private $c_id;

    /**
    * CR_ID of the dialog
    */
    private $cr_id;

    /**
    * The TEXT of the dialog
    */
    private $text;

    /**
    * USER_ONE of the dialog
    */
    private $user_one;

    /**
    * USER_TWO of the dialog
    */
    private $user_two;

    /**
    * TIME when the message was sent (TIMESTAMP)
    */
    private $time;

    /**
    * NAME of the user
    */
    private $name;

    /**
    * READ
    */
    private $read;

    /**
    * Set the ID of the message
    * @param int $id
    * @return id
    */
    public function setId( $id )
    {
        $this->id = $id;
    }

    /**
    * Get the ID of the message
    * @return ID
    */
    public function getId()
    {
        return $this->id;
    }

    /**
    * Set the CONVERSATION_ID of the dialog
    * @param int $cid
    * @return CID
    */
    public function setCId( $cid )
    {
        $this->c_id = $cid;
    }

    /**
    * Get the CONVERSATION_ID of the dialog
    * @return int
    */
    public function getCId()
    {
        return $this->c_id;
    }

    /**
    * Set the CONVERSATION_REPLY_ID of the dialog
    * @param int $crid
    * @return void
    */
    public function setCrId( $crid )
    {
        $this->cr_id = $crid;
    }

    /**
    * Get the CONVERSATION_REPLY_ID of the dialog
    * @return int
    */
    public function getCrId()
    {
        return $this->cr_id;
    }

    /**
    * Set the senttime of the dialog
    * @param int $time
    * @return void
    */
    public function setTime( $time )
    {
        $this->time = $time;
    }

    /**
    * Get the senttime of the message
    * @return int
    */
    public function getTime()
    {
        return $this->time;
    }

    /**
    * Set the USER_ONE of the dialog
    * @param int $userone
    * @return void
    */
    public function setUserOne( $userone )
    {
        $this->user_one = $userone;
    }

    /**
    * Get the USER_ONE of the dialog
    * @return void
    */
    public function getUserOne()
    {
        return $this->user_one;
    }

    /**
    * Set the USER_TWO of the dialog
    * @param int $usertwo
    * @return void
    */
    public function setUserTwo( $usertwo )
    {
        $this->user_two = $usertwo;
    }

    /**
    * Get the USER_ONE of the dialog
    * @return void
    */
    public function getUserTwo()
    {
        return $this->user_two;
    }

    /**
    * Set the TEXT of the dialog
    * @param String $text
    * @return void
    */
    public function setText( $text )
    {
        $this->text = $text;
    }

    /**
    * Get the text of the dialog
    * @return text
    */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Get the avatar of the user.
     * @return void
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set the AVATAR of the dialog
     * @param $avatar
     * @return void
     */
    public function setAvatar($avatar)
    {
        $this->avatar = (string)$avatar;
    }

    /**
    * Set the NAME of the user
    * @param string $name
    * @return void
    */
    public function setName( $name)
    {
        $this->name = $name;
    }

    /**
    * Get the NAME of the user
    * @return void
    */
    public function getName()
    {
        return $this->name;
    }

    /**
    * Set the read
    * @param string $read
    * @return int
    */
    public function setRead($read)
    {
        $this->read = $read;
    }

    /**
    * Get the read
    * @return int
    */
    public function getRead()
    {
        return $this->read;
    }
}
