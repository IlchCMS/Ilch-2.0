<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Rule\Mappers;

use Modules\Rule\Models\Rule as RuleModel;

class Rule extends \Ilch\Mapper
{
    /**
     * Gets the Rule entries.
     *
     * @param array $where
     * @return RuleModel[]|array
     */
    public function getRules($where = [])
    {
        $rulesArray = $this->db()->select()
            ->fields(['r.id', 'r.paragraph', 'r.title', 'r.text', 'r.position', 'r.parent_id', 'r.access'])
            ->from(['r' => 'rules'])
            ->join(['p' => 'rules'], ['r.parent_id = p.id'], 'LEFT', ['parent_title' => 'p.title'])
            ->where($where)
            ->order(['position' => 'ASC'])
            ->execute()
            ->fetchRows();

        if (empty($rulesArray)) {
            return null;
        }

        $rules = [];
        foreach ($rulesArray as $rule) {
            $ruleModel = new RuleModel();
            $ruleModel->setId($rule['id'])
                ->setParagraph($rule['paragraph'])
                ->setTitle($rule['title'])
                ->setText($rule['text'])
                ->setPosition($rule['position'])
                ->setParent_Id($rule['parent_id'])
                ->setParentTitle($rule['parent_title'])
                ->setAccess($rule['access']);
            $rules[] = $ruleModel;
        }

        return $rules;
    }

    /**
     * Gets rule.
     *
     * @param integer $id
     * @return RuleModel|null
     */
    public function getRuleById($id)
    {
        $ruleRow = $this->db()->select()
            ->fields(['r.id', 'r.paragraph', 'r.title', 'r.text', 'r.position', 'r.parent_id', 'r.access'])
            ->from(['r' => 'rules'])
            ->join(['p' => 'rules'], ['r.parent_id = p.id'], 'LEFT', ['parent_title' => 'p.title'])
            ->where(['r.id' => $id])
            ->execute()
            ->fetchAssoc();

        if (empty($ruleRow)) {
            return null;
        }

        $ruleModel = new RuleModel();
        $ruleModel->setId($ruleRow['id'])
            ->setParagraph($ruleRow['paragraph'])
            ->setTitle($ruleRow['title'])
            ->setText($ruleRow['text'])
            ->setPosition($ruleRow['position'])
            ->setParent_Id($ruleRow['parent_id'])
            ->setParentTitle($ruleRow['parent_title'])
            ->setAccess($ruleRow['access']);

        return $ruleModel;
    }

    /**
     * Gets all Rules items by parent item id.
     * @param $itemId
     * @return array|RuleModel[]
     */
    public function getRulesItemsByParent($itemId)
    {
        return $this->getRules(['r.parent_id' => $itemId]);
    }

    /**
     * Sort rules.
     *
     * @param int $id
     * @param int $key
     */
    public function sort($id, $key)
    {
        $this->db()->update('rules')
            ->values(['position' => $key])
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Inserts or updates rule model.
     *
     * @param RuleModel $rule
     * @return int
     */
    public function save(RuleModel $rule)
    {
        $fields = [
            'paragraph' => $rule->getParagraph(),
            'title' => $rule->getTitle(),
            'text' => $rule->getText(),
            'parent_id' => $rule->getParent_Id(),
            'position' => $rule->getPosition(),
            'access' => $rule->getAccess()
        ];

        if ($rule->getId()) {
            $itemId = $rule->getId();
            $this->db()->update('rules')
                ->values($fields)
                ->where(['id' => $rule->getId()])
                ->execute();
        } else {
            if ($fields['parent_id'] == 0) {
                // New category. Add to the end (max+1 position)
                $lastPosition = $this->db()->select('MAX(`position`) as lastPosition')
                    ->from('rules')
                    ->execute()
                    ->fetchAssoc();

                $fields['position'] = $lastPosition['lastPosition'] + 1;
            } else {
                // New rule. Add at the end of it's category.
                $lastPosition = $this->db()->select('position as lastPosition')
                    ->from('rules')
                    ->where(['parent_id' => $fields['parent_id']])
                    ->order(['position' => 'DESC'])
                    ->limit(1)
                    ->execute()
                    ->fetchAssoc();

                $fields['position'] = $lastPosition['lastPosition'];
            }

            $itemId = $this->db()->insert('rules')
                ->values($fields)
                ->execute();
        }
        
        return $itemId;
    }

    /**
     * Deletes rule with given id.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete('rules')
            ->where(['id' => $id])
            ->execute();
    }
}
