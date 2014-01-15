<?php
/**
 * Holds class User.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace User\Mappers;

use User\Models\Confirm as ConfirmModel;

defined('ACCESS') or die('no direct access');

/**
 * The confirm mapper class.
 *
 * @package ilch
 */
class Confirm extends \Ilch\Mapper
{
    public function getCheck($where = array())
    {
        $checkArray = $this->db()->selectArray('*', 'usercheck', $where);

        if (empty($checkArray)) {
            return array();
        }

        $checks = array();

        foreach ($checkArray as $checkRow) {
            $checkModel = new ConfirmModel();
            $checkModel->setCheck($checkRow['check']);
            $checkModel->setName($checkRow['name']);
            $checkModel->setEmail($checkRow['email']);
            $checkModel->setPassword($checkRow['password']);
            $checkModel->setDateCreated($checkRow['date_created']);
         
            $checks[] = $checkModel;
        }

        return $checks;
    }

    /**
     * Deletes user with given check.
     *
     * @param integer $check
     */
    public function delete($check)
    {
        $this->db()->delete
        (
            'usercheck',
            array('check' => $check)
        );
    }
}
