<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Faq\Mappers;

use Modules\Faq\Models\Faq as FaqModel;

class Faq extends \Ilch\Mapper
{
    /**
     * Gets faqs.
     *
     * @param array $where
     * @return FaqModel[]|[]
     */
    public function getFaqs($where = [])
    {
        $faqArray = $this->db()->select('*')
            ->from('faqs')
            ->where($where)
            ->execute()
            ->fetchRows();

        if (empty($faqArray)) {
            return [];
        }

        $faqs = [];
        foreach ($faqArray as $faqRow) {
            $faqModel = new FaqModel();
            $faqModel->setId($faqRow['id']);
            $faqModel->setCatId($faqRow['cat_id']);
            $faqModel->setQuestion($faqRow['question']);
            $faqModel->setAnswer($faqRow['answer']);

            $faqs[] = $faqModel;
        }

        return $faqs;
    }

    /**
     * Gets faq by id.
     *
     * @param integer $id
     * @return FaqModel|null
     */
    public function getFaqById($id)
    {
        $faqs = $this->getFaqs(['id' => $id]);
        return reset($faqs);
    }

    /**
     * Gets faqs by catId.
     *
     * @param integer $catId
     * @return FaqModel[]|[]
     */
    public function getFaqsByCatId($catId)
    {
        $faqs = $this->getFaqs(['cat_id' => $catId]);
        return reset($faqs);
    }

    /**
     * Inserts or updates faq model.
     *
     * @param FaqModel $faq
     */
    public function save(FaqModel $faq)
    {
        $fields = [
            'cat_id' => $faq->getCatId(),
            'question' => $faq->getQuestion(),
            'answer' => $faq->getAnswer()
        ];

        if ($faq->getId()) {
            $this->db()->update('faqs')
                ->values($fields)
                ->where(['id' => $faq->getId()])
                ->execute();
        } else {
            $this->db()->insert('faqs')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Deletes faq with given id.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete('faqs')
            ->where(['id' => $id])
            ->execute();
    }
}
