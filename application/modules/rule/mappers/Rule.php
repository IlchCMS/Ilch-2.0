<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Rule\Mappers;

use Modules\Rule\Models\Entry as RuleModel;

class Rule extends \Ilch\Mapper
{
    /**
     * Gets the Rule entries.
     *
     * @param array $where
     * @return RuleModel[]|array
     */
    public function getEntries($where = array())
    {
        $entryArray = $this->db()->select('*')
            ->from('rules')
            ->where($where)
            ->order(array('paragraph' => 'ASC'))
            ->execute()
            ->fetchRows();

        if (empty($entryArray)) {
            return null;
        }

        $entry = array();

        foreach ($entryArray as $entries) {
            $entryModel = new RuleModel();
            $entryModel->setId($entries['id']);
            $entryModel->setParagraph($entries['paragraph']);
            $entryModel->setTitle($entries['title']);
            $entryModel->setText($entries['text']);
            $entry[] = $entryModel;

        }

        return $entry;
    }
    
    /**
     * Gets rules.
     *
     * @param array $where
     * @param array $orderBy
     * @return RuleModel[]|null
     */
    public function getRulesBy($where = array(), $orderBy = array('id' => 'ASC'))
    {
        $ruleArray = $this->db()->select('*')
            ->from('rules')
            ->where($where)
            ->order($orderBy)
            ->execute()
            ->fetchRows();

        if (empty($ruleArray)) {
            return null;
        }

        $rules = array();

        foreach ($ruleArray as $ruleRow) {
            $ruleModel = new RuleModel();
            $ruleModel->setId($ruleRow['id']);
            $ruleModel->setParagraph($ruleRow['paragraph']);
            $ruleModel->setTitle($ruleRow['title']);
            $ruleModel->setText($ruleRow['text']);
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
        $ruleRow = $this->db()->select('*')
            ->from('rules')
            ->where(array('id' => $id))
            ->execute()
            ->fetchAssoc();

        if (empty($ruleRow)) {
            return null;
        }

        $ruleModel = new RuleModel();
        $ruleModel->setId($ruleRow['id']);
        $ruleModel->setParagraph($ruleRow['paragraph']);
        $ruleModel->setTitle($ruleRow['title']);
        $ruleModel->setText($ruleRow['text']);

        return $ruleModel;
    }

    /**
     * Inserts or updates rule model.
     *
     * @param RuleModel $rule
     */
    public function save(RuleModel $rule)
    {
        $fields = array
        (
            'paragraph' => $rule->getParagraph(),
            'title' => $rule->getTitle(),
            'text' => $rule->getText(),
        );

        if ($rule->getId()) {
            $this->db()->update('rules')
                ->values($fields)
                ->where(array('id' => $rule->getId()))
                ->execute();
        } else {
            $this->db()->insert('rules')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Deletes rule with given id.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete('rules')
            ->where(array('id' => $id))
            ->execute();
    }
}
