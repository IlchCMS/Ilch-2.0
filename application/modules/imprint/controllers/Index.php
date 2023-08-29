<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Imprint\Controllers;

use Modules\Imprint\Mappers\Imprint as ImprintMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $imprintMapper = new ImprintMapper();

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuImprint'), ['action' => 'index']);

        $this->getView()->set('imprint', $imprintMapper->getImprintById(1));
    }
}
