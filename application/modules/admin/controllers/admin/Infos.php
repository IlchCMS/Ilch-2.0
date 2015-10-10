<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Controllers\Admin;

defined('ACCESS') or die('no direct access');

class Infos extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = array
        (
            array
            (
                'name' => 'menuPHPInfo',
                'active' => false,
                'icon' => 'fa fa-info-circle',
                'url' => $this->getLayout()->getUrl(array('controller' => 'infos', 'action' => 'index'))
            ),
            array
            (
                'name' => 'menuFolderRights',
                'active' => false,
                'icon' => 'fa fa-folder-open',
                'url' => $this->getLayout()->getUrl(array('controller' => 'infos', 'action' => 'folderrights'))
            ),
        );

        if ($this->getRequest()->getActionName() == 'folderrights') {
            $items[1]['active'] = true; 
        } else {
            $items[0]['active'] = true; 
        }

        $this->getLayout()->addMenu
        (
            'menuInfos',
            $items
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('hmenuInfos'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('hmenuPHPInfo'), array('action' => 'index'));

        ob_start();
        phpinfo();
        $phpinfo = ob_get_clean();

        # Body-Content entfernen
        $phpinfo = preg_replace('#^.*<body>(.*)</body>.*$#s', '$1', $phpinfo);

        # <font> durch <span> ersetzen
        $phpinfo = str_replace('<font', '<span', $phpinfo);
        $phpinfo = str_replace('</font>', '</span>', $phpinfo);

        # <hr> entfernen
        $phpinfo = preg_replace('/<hr \/>/', '', $phpinfo);

        # Schlüsselwörter grün oder rot einfärben
        $phpinfo = preg_replace('#>(on|enabled|active)#i', '><span class="green">$1</span>', $phpinfo);
        $phpinfo = preg_replace('#>(off|disabled)#i', '><span class="red">$1</span>', $phpinfo);

        $this->getView()->set('phpinfo', $phpinfo);
    }

    public function folderrightsAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('hmenuInfos'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('hmenuFolderRights'), array('action' => 'folderrights'));

        $this->getView();
    }
}
