<?php
/**
 * @copyright Balthazar3k 2014
 * @package Eventplaner 2.0
 */

namespace Eventplaner\Mappers;

use Eventplaner\Models\Registrations as RegistrationsModel;

defined('ACCESS') or die('no direct access');

class Registrations extends \Ilch\Mapper
{
    public function get($eid)
    {
        $res = $this->db()
            ->selectArray('*')
            ->from('ep_registrations')
            ->where(array('eid' => $eid))
            ->execute();
        
        if (empty($res)) {
            return array();
        }
        
        $return = array();
        
        foreach( $res as $val ){
            $model = new RegistrationsModel();
            $model->setId($val['id']);
            $model->setStatus($val['status']);
            $model->setEid($val['eid']);
            $model->setUid($val['uid']);
            $model->setCid($val['cid']);
            $model->setComment($val['comment']);
            $model->setChanged($val['changed']);
            $model->setRegistered($val['registered']);
            $return[] = $model;
        }
        
        return $return;
    }
    
    public function numRegistrations($eid)
    {  
        return $this->db()->queryCell('
            SELECT COUNT(`id`) as numRegistrations 
            FROM [prefix]_ep_registrations 
            WHERE eid=\''.$eid.'\';
        ');
    }
	
    public function register($eid, $uid)
    {
        
    }
	
    public function cancel( $eid, $uid)
    {
    }
	
    public function change( $eid, $uid)
    {
    }

    public function delete($id)
    {
        return $this->db()->delete('ep_registrations')
            ->where(array('id' => $id))
            ->execute();
    }
}
?>