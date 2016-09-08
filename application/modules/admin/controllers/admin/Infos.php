<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Controllers\Admin;

use Modules\Admin\Mappers\Infos as InfosMapper;

class Infos extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'menuPHPInfo',
                'active' => false,
                'icon' => 'fa fa-info-circle',
                'url' => $this->getLayout()->getUrl(['controller' => 'infos', 'action' => 'index'])
            ],
            [
                'name' => 'menuPHPExtensions',
                'active' => false,
                'icon' => 'fa fa-cubes',
                'url' => $this->getLayout()->getUrl(['controller' => 'infos', 'action' => 'phpextensions'])
            ],
            [
                'name' => 'menuFolderRights',
                'active' => false,
                'icon' => 'fa fa-folder-open',
                'url' => $this->getLayout()->getUrl(['controller' => 'infos', 'action' => 'folderrights'])
            ],
            [
                'name' => 'menuCertificate',
                'active' => false,
                'icon' => 'fa fa-key',
                'url' => $this->getLayout()->getUrl(['controller' => 'infos', 'action' => 'certificate'])
            ],
            [
                'name' => 'menuKeyboardShortcuts',
                'active' => false,
                'icon' => 'fa fa-keyboard-o',
                'url' => $this->getLayout()->getUrl(['controller' => 'infos', 'action' => 'shortcuts'])
            ]
        ];

        if ($this->getRequest()->getActionName() == 'phpextensions') {
            $items[1]['active'] = true; 
        } elseif ($this->getRequest()->getActionName() == 'folderrights') {
            $items[2]['active'] = true; 
        } elseif ($this->getRequest()->getActionName() == 'certificate') {
            $items[3]['active'] = true;
        } elseif ($this->getRequest()->getActionName() == 'shortcuts') {
            $items[4]['active'] = true; 
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
                ->add($this->getTranslator()->trans('hmenuInfos'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('hmenuPHPInfo'), ['action' => 'index']);

        ob_start();
        phpinfo();
        $phpinfo = ob_get_clean();

        # Body-Content entfernen
        $phpinfo = preg_replace('#^.*<body>(.*)</body>.*$#s', '$1', $phpinfo);

        # Div center entfernen
        $phpinfo = preg_replace('#^.*<div class="center">(.*)</div>.*$#s', '$1', $phpinfo);

        # <font> durch <span> ersetzen
        $phpinfo = str_replace('<font', '<span', $phpinfo);
        $phpinfo = str_replace('</font>', '</span>', $phpinfo);

        # <hr> entfernen
        $phpinfo = preg_replace('/<hr \/>/', '', $phpinfo);

        # Schlüsselwörter grün oder rot einfärben
        $phpinfo = preg_replace('#>(on|enabled|active)#i', '><span class="text-success">$1</span>', $phpinfo);
        $phpinfo = preg_replace('#>(off|disabled)#i', '><span class="text-danger">$1</span>', $phpinfo);

        $this->getView()->set('phpinfo', $phpinfo);
    }

    public function folderrightsAction()
    {
        $InfosMapper = new InfosMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('hmenuInfos'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('hmenuFolderRights'), ['action' => 'folderrights']);

        $this->getView()->set('folderrights', $InfosMapper->getModulesFolderRights());
    }

    public function phpextensionsAction()
    {
        $InfosMapper = new InfosMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('hmenuInfos'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuPHPExtensions'), ['action' => 'phpextensions']);

        $this->getView()->set('phpExtensions', $InfosMapper->getModulesPHPExtensions());
    }

    public function certificateAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('hmenuInfos'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('hmenuCertificate'), ['action' => 'certificate']);

        if (!is_file(ROOT_PATH.'/certificate/Certificate.crt')) {
            $this->getView()->set('certificateMissing', true);
            return;
        }

        $certificate = file_get_contents(ROOT_PATH.'/certificate/Certificate.crt');
        $pubkey = openssl_pkey_get_public($certificate);
        $publicKeyArray = openssl_pkey_get_details($pubkey);
        $keyType = '';
        $keyType = $publicKeyArray['type']==OPENSSL_KEYTYPE_RSA ? 'RSA' : $keyType;
        $keyType = $publicKeyArray['type']==OPENSSL_KEYTYPE_DSA ? 'DSA' : $keyType;
        $keyType = $publicKeyArray['type']==OPENSSL_KEYTYPE_DH ?  'DH'  : $keyType;

        $this->getView()->set('certificate', openssl_x509_parse($certificate));
        // Strip off begin- and end certificate-lines and base64-decode the rest before calling openssl_digest
        // to get the same fingerprint as displayed in e.g. Microsoft Windows.
        $certificate = str_replace('-----BEGIN CERTIFICATE-----', '', $certificate);
        $certificate = str_replace('-----END CERTIFICATE-----', '', $certificate);
        $this->getView()->set('certificateDigest', openssl_digest(base64_decode($certificate), 'SHA1'));
        $this->getView()->set('certificateKeySize', isset($publicKeyArray['bits'])? $publicKeyArray['bits'] : 0);
        $this->getView()->set('certificateKeyType', $keyType);
    }

    public function shortcutsAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('hmenuInfos'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('hmenuKeyboardShortcuts'), ['action' => 'shortcuts']);

    }
}
