<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch Pluto
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

abstract class Ilch_Layout_Base extends Ilch_Design_Base
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
	 * File of the layout.
	 *
	 * @var string
	 */
	protected $_file;

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
	 * Loads a view script.
	 *
	 * @param string $loadScript
	 * @return string
	 */
	public function loadScript($loadScript)
	{
		if(file_exists($loadScript))
		{
			include_once($loadScript);
		}
	}

	/**
	 * Sets the file of the layout.
	 *
	 * @param string $file
	 */
	public function setFile($file)
	{
		$this->_file = $file;
	}

	/**
	 * Gets the file of the layout.
	 *
	 * @return string
	 */
	public function getFile()
	{
		return $this->_file;
	}
}