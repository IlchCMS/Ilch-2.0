<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Controllers\Admin;

use Modules\User\Mappers\Group as GroupMapper;
use Modules\Forum\Mappers\GroupRanking as GroupRankingMapper;

class Settings extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'forum',
                'active' => false,
                'icon' => 'fa fa-th',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'menuRanks',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'ranks', 'action' => 'index'])
            ],
            [
                'name' => 'menuReports',
                'active' => false,
                'icon' => 'fas fa-flag',
                'url' => $this->getLayout()->getUrl(['controller' => 'reports', 'action' => 'index'])
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index']),
                [
                    'name' => 'menuGroupAppearance',
                    'active' => false,
                    'icon' => 'fa fa-cogs',
                    'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'groupappearance'])
                ]
            ]
        ];

        if ($this->getRequest()->getActionName() == 'groupappearance') {
            $items[3][0]['active'] = true;
        } else {
            $items[3]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'forum',
            $items
        );
    }

    public function indexAction()
    {
        $groupMapper = new GroupMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('forum'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('forum_threadsPerPage', $this->getRequest()->getPost('threadsPerPage'));
            $this->getConfig()->set('forum_postsPerPage', $this->getRequest()->getPost('postsPerPage'));
            $this->getConfig()->set('forum_floodInterval', $this->getRequest()->getPost('floodInterval'));
            $this->getConfig()->set('forum_excludeFloodProtection', implode(',', ($this->getRequest()->getPost('groups')) ? $this->getRequest()->getPost('groups') : []));
            $this->getConfig()->set('forum_postVoting', $this->getRequest()->getPost('postVoting'));
            $this->getConfig()->set('forum_topicSubscription', $this->getRequest()->getPost('topicSubscription'));
            $this->getConfig()->set('forum_boxForumLimit', $this->getRequest()->getPost('boxForumLimit'));
            $this->getConfig()->set('forum_reportingPosts', $this->getRequest()->getPost('reportingPosts'));
            $this->getConfig()->set('forum_reportNotificationEMail', $this->getRequest()->getPost('reportNotificationEMail'));
            $this->getConfig()->set('forum_DESCPostorder', $this->getRequest()->getPost('DESCPostorder'));
            $this->addMessage('saveSuccess');
        }

        $this->getView()->set('threadsPerPage', $this->getConfig()->get('forum_threadsPerPage'));
        $this->getView()->set('postsPerPage', $this->getConfig()->get('forum_postsPerPage'));
        $this->getView()->set('floodInterval', $this->getConfig()->get('forum_floodInterval'));
        $this->getView()->set('excludeFloodProtection', explode(',', $this->getConfig()->get('forum_excludeFloodProtection')));
        $this->getView()->set('postVoting', $this->getConfig()->get('forum_postVoting'));
        $this->getView()->set('topicSubscription', $this->getConfig()->get('forum_topicSubscription'));
        $this->getView()->set('boxForumLimit', $this->getConfig()->get('forum_boxForumLimit'));
        $this->getView()->set('groupList', $groupMapper->getGroupList());
        $this->getView()->set('reportingPosts', $this->getConfig()->get('forum_reportingPosts'));
        $this->getView()->set('reportNotificationEMail', $this->getConfig()->get('forum_reportNotificationEMail'));
        $this->getView()->set('DESCPostorder', $this->getConfig()->get('forum_DESCPostorder'));
    }

    public function groupappearanceAction()
    {
        $groupRankingMapper = new GroupRankingMapper();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('forum'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('settings'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuGroupAppearance'), ['action' => 'groupappearance']);

        if ($this->getRequest()->isPost()) {
            $appearances = $this->getRequest()->getPost('appearances');
            $groups = array_keys($appearances);
            $groupRankingMapper->saveGroupRanking($groups);
            $filename = $this->writeCSSFile($appearances);

            if ($filename !== false) {
                $appearances = json_encode($appearances);
                $this->getConfig()->set('forum_groupAppearance', $appearances);
                $this->getConfig()->set('forum_filenameGroupappearanceCSS', $filename);

                $this->addMessage('saveSuccess');
                $this->redirect(['action' => 'groupappearance']);
            } else {
                $this->addMessage('errorWritingGroupAppearanceCSSFile', 'danger');
                $this->redirect(['action' => 'groupappearance']);
            }
        }

        $appearances = json_decode($this->getConfig()->get('forum_groupAppearance'), true);
        $this->getView()->set('appearances', $appearances);
        $this->getView()->set('groupList', $groupRankingMapper->getUserGroupsSortedByRank());
    }

    /**
     * Write the CSS file for the appearance of the user groups with a "unique" file name.
     *
     * @param array $appearances Appearance settings
     * @return string Filename of the css file on success
     */
    private function writeCSSFile($appearances)
    {
        $content = '';
        foreach($appearances as $key => $value) {
            if (!isset($value['active'])) {
                continue;
            }

            $content .= '#forum .appearance'.$key.' {'.PHP_EOL;
            $content .= 'color: '.$value['textcolor'].';'.PHP_EOL;

            if (isset($value['bold'])) {
                $content .= 'font-weight: bold;'.PHP_EOL;
            }

            if (isset($value['italic'])) {
                $content .= 'font-style: italic;'.PHP_EOL;
            }
            $content .= '}'.PHP_EOL;
        }

        // Delete old stylesheets
        $files = glob(APPLICATION_PATH.'/modules/forum/static/css/groupappearance/*');
        foreach($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        if (empty($content)) {
            return '';
        }

        $filename = uniqid().'.css';
        $returnValue = file_put_contents(APPLICATION_PATH.'/modules/forum/static/css/groupappearance/'.$filename, $content);
        if ($returnValue !== false && $returnValue != 0) {
            return $filename;
        }
        return false;
    }
}
