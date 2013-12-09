<?php
/**
 * @author Thomas Stantin
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Guestbook\Models;

defined('ACCESS') or die('no direct access');

class Guestbook extends \Ilch\Mapper 
{
	public function getEntries() 
	{
        $sql = "SELECT `id`,`email`, `text`,`datetime`,`homepage`,`name` FROM `[prefix]_gbook` ORDER by `id` DESC";
        $entries = $this->db()->queryArray($sql);
		
		return $entries;     
    }

    public function saveEntry(array $datas) 
	{
		$sql = 'INSERT INTO `[prefix]_gbook`
            ( ';
        $sqlFields = array();
        $sqlValues = array();

        foreach ($datas as $key => $value) 
		{
            $sqlFields[] = '`' . $key . '`';
        }

        $sql .= implode(',', $sqlFields);
        $sql .= ') VALUES (';

        foreach ($datas as $key => $value) 
		{
            $sqlValues[] = '"' . $this->db()->escape($value) . '"';
        }

        $sql .= implode(',', $sqlValues) . ')';

        $result = $this->db()->query($sql);
        return $result; 
    }
	
	public function getShorttext($text) 
	{
		$shorttext = substr($text,0,30).'..';
		$newtext = wordwrap( $shorttext, 12, "\n", 1);
		return $newtext;
    }
	
	public function delEntry($id) 
	{
		$this->db()->delete('gbook', array('id' => $id));	
	}
}
