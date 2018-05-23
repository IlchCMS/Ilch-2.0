<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Imprint\Controllers\Admin;

use Modules\Imprint\Mappers\Imprint as ImprintMapper;
use Modules\Imprint\Models\Imprint as ImprintModel;
use Ilch\Validation;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => true,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'menuImprint',
            $items
        );
    }

    public function indexAction()
    {
        $imprintMapper = new ImprintMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuImprint'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $model = new ImprintModel();
            $model->setId(1);
            $model->setImprint($this->getRequest()->getPost('imprint'));
            $imprintMapper->save($model);

            $this->redirect()
                ->withMessage('saveSuccess')
                ->to(['action' => 'index']);
        }

        $this->getView()->set('imprint', $imprintMapper->getImprintById(1));
    }
}
