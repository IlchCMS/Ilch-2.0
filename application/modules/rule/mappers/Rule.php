<?php
/**
 * @copyright Ilch 2.0
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
        $rulesArray = $this->db()->select('*')
            ->from('rules')
            ->where($where)
            ->order(['paragraph' => 'ASC'])
            ->execute()
            ->fetchRows();

        if (empty($rulesArray)) {
            return null;
        }

        $rules = [];
        foreach ($rulesArray as $rule) {
            $ruleModel = new RuleModel();
            $ruleModel->setId($rule['id']);
            $ruleModel->setParagraph($rule['paragraph']);
            $ruleModel->setTitle($rule['title']);
            $ruleModel->setText($rule['text']);
            $rules[] = $ruleModel;
        }

        return $rules;
    }

    /**
     * Gets rules.
     *
     * @param array $where
     * @param array $orderBy
     * @return RuleModel[]|null
     */
    public function getRulesBy($where = [], $orderBy = ['id' => 'ASC'])
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

        $rules = [];
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
            ->where(['id' => $id])
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
        $fields =
            [
            'paragraph' => $rule->getParagraph(),
            'title' => $rule->getTitle(),
            'text' => $rule->getText(),
            ];

        if ($rule->getId()) {
            $this->db()->update('rules')
                ->values($fields)
                ->where(['id' => $rule->getId()])
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
            ->where(['id' => $id])
            ->execute();
    }
}
