<?php

namespace Modules\Matches\Models;

/**
 * Default opponent model
 *
 * @copyright Ilch 2.0
 * @package ilch
 */
class Opponent extends \Ilch\Model
{
    /**
     * @var integer The id of the team.
     */
    protected $id = 0;

    /**
     * @var string The name of the team
     */
    protected $name;

    /**
     * @var string The abbreviated name of the team
     */
    protected $short_name;

    /**
     * @var string The logos filepath
     */
    protected $logo;

    /**
     * @var string The website url
     */
    protected $website;

    /**
     * Sets the id of the team
     *
     * @param integer $id The id of the team
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Gets the id of the team
     *
     * @return integer The id of the team
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the name of the team
     *
     * @param string $name The name of the team
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets the name of the team
     *
     * @return string The name of the team
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the abbreviated version of the teams name
     *
     * @param $short_name string The abbreviated version of the teams name
     *
     * @return $this
     */
    public function setShortName($short_name)
    {
        $this->short_name = $short_name;
        return $this;
    }

    /**
     * Gets the abbreviated version of the teams name
     *
     * @return string The abbreviated version of the teams name
     */
    public function getShortName()
    {
        return $this->short_name;
    }

    /**
     * Sets the filepath to the teams logo
     *
     * @param string $logo_file The filepath to the teams logo
     *
     * @return $this
     */
    public function setLogo($logo_file)
    {
        $this->logo = $logo_file;
        return $this;
    }

    /**
     * Gets the filepath to the teams logo
     *
     * @return string The filepath to the teams logo
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Sets the Website URL
     *
     * @param string $url The URL
     *
     * @return $this
     */
    public function setWebsite($url)
    {
        $this->website = $url;
        return $this;
    }

    /**
     * Gets the Website URL
     *
     * @return string The URL
     */
    public function getWebsite()
    {
        return $this->website;
    }
}
