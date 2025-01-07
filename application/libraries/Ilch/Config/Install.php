<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\Config;

use Ilch\Registry;
use Ilch\Translator;

class Install extends \Ilch\Mapper
{
    /**
     * @var Translator|null
     */
    private $translator;

    /**
     * @param null|Translator $translator
     */
    public function __construct(?Translator $translator = null)
    {
        $this->setTranslator($translator);
        parent::__construct();
    }

    /**
     * @return Translator|null
     */
    public function getTranslator(): ?Translator
    {
        if (!$this->translator) {
            $this->setTranslator(Registry::get('translator'));
        }
        return $this->translator;
    }

    /**
     * @param Translator|null $translator
     * @return $this
     * @since V2.2.7
     */
    public function setTranslator(?Translator $translator): Install
    {
        if ($translator) {
            $this->translator = $translator;
        }
        return $this;
    }

    /**
     * Gets the config object.
     *
     * @return Database|null
     * @since 2.2.7
     */
    public function getConfig(): ?Database
    {
        $config = Registry::get('config');
        if (!$config) {
            $config = new Database($this->db());
        }

        return $config;
    }
}
