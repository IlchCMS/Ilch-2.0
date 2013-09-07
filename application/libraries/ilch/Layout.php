<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

class Ilch_Layout extends Ilch_Design_Base
{
	/**
	 * Defines if layout is disabled.
	 *
	 * @var boolean
	 */
	protected $_disabled = false;

	/**
	 * Holds the view output.
	 *
	 * @var string
	 */
	protected $_content = '';

	/**
	 * Set layout disabled flag.
	 *
	 * @param boolean $disabled
	 */
	public function setDisabled($disabled)
	{
		$this->_disabled = $disabled;
	}

	/**
	 * Get layout disabled flag.
	 *
	 * @return boolean
	 */
	public function getDisabled()
	{
		return $this->_disabled;
	}

	/**
	 * Sets the view output.
	 *
	 * @param string $content
	 */
	public function setContent($content)
	{
		$this->_content = $content;
	}

	/**
	 * Gets the view output.
	 *
	 * @param string $content
	 */
	public function getContent()
	{
		return $this->_content;
	}

	/**
	 * Loads the given file.
	 *
	 * @param string $file
	 * @param integer $noFile
	 */
	public function load($file, $noFile = 0)
	{
		if($noFile == 1)
		{
			echo $file;
		}
		elseif(file_exists(APPLICATION_PATH.'/layouts/'.$file.'.php'))
		{
			require_once APPLICATION_PATH.'/layouts/'.$file.'.php';
		}
	}
}