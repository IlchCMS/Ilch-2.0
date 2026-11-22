<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Search\Controllers\Admin;

use Ilch\Search\Indexers;
use Ilch\Search\Queue;
use Ilch\Search\Store;
use Modules\Admin\Mappers\Module as ModuleMapper;

/**
 * Admin overview for the search module.
 *
 * Shows the engine capability (FULLTEXT vs. LIKE fallback), the document
 * counts, the pending queue and the backfill frontiers. The per-module
 * "reindex" action deletes that module's documents and its persisted
 * frontier, so the scheduled search.backfill task rebuilds it from zero
 * within a few minutes.
 */
class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu(
            'menuSearch',
            [
                [
                    'name' => 'overview',
                    'active' => true,
                    'icon' => 'fa-solid fa-table-list',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
                ]
            ]
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuSearch'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('overview'), ['action' => 'index']);

        $store         = new Store();
        $queue         = new Queue();
        $modulesMapper = new ModuleMapper();

        $docs  = $store->countDocumentsByModule();
        $total = 0;
        foreach ($docs as $count) {
            $total += $count;
        }

        $modules = [];
        foreach (array_keys(Indexers::modules()) as $module) {
            $moduleModel = $modulesMapper->getModulesByKey($module, $this->getConfig()->get('locale'));
            $modules[] = [
                'key'      => $module,
                'name'     => $moduleModel !== null ? $moduleModel->getName() : $module,
                'docs'     => $docs[$module] ?? 0,
                'frontier' => $store->getFrontier($module),
            ];
        }

        $this->getView()->set('modules', $modules);
        $this->getView()->set('totalDocs', $total);
        $this->getView()->set('queueCount', $queue->count());
        $this->getView()->set('serverVersion', $store->serverVersion());
        $this->getView()->set('fulltextAvailable', $store->fulltextAvailable());
    }

    public function reindexAction()
    {
        $module = $this->getRequest()->getPost('module');

        if ($this->getRequest()->isSecure() && is_string($module) && Indexers::get($module) !== null) {
            $store = new Store();

            // Drop the module's documents and its persisted backfill frontier:
            // the scheduled search.backfill task rebuilds it from zero.
            $store->deleteByModule($module);
            $store->resetFrontier($module);

            $this->addMessage('reindexStarted');
        }

        $this->redirect()->to(['action' => 'index']);
    }
}
