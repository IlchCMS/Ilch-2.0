<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Rule\Controllers\Admin;

use Modules\Rule\Mappers\Rule as RuleMapper;
use Modules\Rule\Models\Rule as RuleModel;
use Modules\User\Mappers\Group as UserGroupMapper;
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
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'menuRules',
            $items
        );
    }

    public function indexAction()
    {
        $ruleMapper = new RuleMapper();
        $userGroupMapper = new UserGroupMapper();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuRules'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            if ($this->getRequest()->getPost('save')) {
                $sortItems = json_decode($this->getRequest()->getPost('hiddenMenu'));
                $items = $this->getRequest()->getPost('items');
                $oldItems = $ruleMapper->getRules();

                $oldItemsid = [];
                $Itemsparentcahnge = [];

                /*
                 * Deletes old entries from database.
                 */
                if (!empty($oldItems)) {
                    foreach ($oldItems as $oldItem) {
                        $oldItemsid[$oldItem->getId()] = $oldItem;
                        if (!isset($items[$oldItem->getId()])) {
                            $ruleMapper->delete($oldItem->getId());
                        }
                    }
                }

                if ($items) {
                    $sortArray = [];

                    foreach ($sortItems as $sortItem) {
                        if ($sortItem->item_id !== null) {
                            $sortArray[$sortItem->item_id] = (int)$sortItem->parent_id;
                        }
                    }
                    foreach ($items as $item) {
                        $validation = Validation::create($item, [
                            'paragraph' => 'required',
                            'title' => 'required',
                        ]);
                        if (!$validation->isValid()) {
                            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                            $this->redirect()
                                ->withErrors($validation->getErrorBag())
                                ->to(['action' => 'index']);
                        }
                        
                        $newItem = new RuleModel();

                        if (strpos($item['id'], 'tmp_') !== false) {
                            $tmpId = str_replace('tmp_', '', $item['id']);
                        } else {
                            $newItem->setId($item['id']);
                            if ($oldItemsid[$item['id']]->getParent_Id() != $item['parent']){
                                $newItem->setParent_Id($item['parent']);
                                $Itemsparentcahnge[$item['id']] = true;
                            } else {
                                $newItem->setParent_Id($oldItemsid[$oldItem->getId()]->getParent_Id());
                                $Itemsparentcahnge[$item['id']] = false;
                            }
                        }

                        $newItem->setParagraph($item['paragraph']);
                        $newItem->setTitle($item['title']);
                        $newItem->setAccess($item['access']);
                        // Don't try to store these values for a categorie. This avoids storing "undefined" from JS in the database.
                        if ($item['parent'] != 0) {
                            $newItem->setText($item['text']);
                        }
                        $newId = $ruleMapper->save($newItem);
                        if (isset($tmpId)) {
                            foreach ($sortArray as $id => $parentId) {
                                if ($id == $tmpId) {
                                    unset($sortArray[$id]);
                                    $sortArray[$newId] = $parentId;
                                    $Itemsparentcahnge[$newId] = false;
                                }

                                if ($parentId == $tmpId) {
                                    $sortArray[$id] = $newId;
                                }
                            }
                        }
                    }

                    $sort = 0;

                    foreach ($sortArray as $id => $parent) {
                        $newItem = new RuleModel();
                        $newItem->setId($id);
                        $newItem->setPosition($sort);
                        if (!$Itemsparentcahnge[$id]) $newItem->setParent_Id($parent);
                        $ruleMapper->save($newItem);
                        $sort += 10;
                    }
                }
            }

            $this->addMessage('saveSuccess');
            $this->redirect(['action' => 'index']);
        }

        $this->getView()->set('rules', $ruleMapper->getRulesItemsByParent(0));
        $this->getView()->set('rulesparents', $ruleMapper->getRules(['parent_id' => 0]));
        $this->getView()->set('rulesMapper', $ruleMapper);

        $userGroupList = $userGroupMapper->getGroupList();
        $this->getView()->set('userGroupList', $userGroupList);
    }
}
