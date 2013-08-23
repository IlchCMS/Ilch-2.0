<?php
/**
 * @author Dominik Meyer <kinimodmeyer@gmail.com>
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

class Ilch_Mapper
{
    /**
     * Injects the database adapter to the mapper.
     */
    public function __construct()
    {
        $this->db = Ilch_Registry::get('db');
    }
}