<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\Config;

use Ilch\Registry;

class Install extends \Ilch\Mapper
{
    /**
     * @var \Ilch\Translator|null
     */
    private $translator;

    /**
     * @param null|\Ilch\Translator $translator
     */
    public function __construct(?\Ilch\Translator $translator = null)
    {
        $this->translator = $translator;
        parent::__construct();
    }

    /**
     * @return \Ilch\Translator
     */
    public function getTranslator(): \Ilch\Translator
    {
        if (!$this->translator) {
            $this->translator = Registry::get('translator');
        }
        return $this->translator;
    }
}
