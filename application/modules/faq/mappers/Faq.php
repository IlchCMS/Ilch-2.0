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
     * @return FaqModel[]|null
     */
    public function getFaqs($where = array())
    {
        $faqArray = $this->db()->select('*')
            ->from('faqs')
            ->where($where)
            ->execute()
            ->fetchRows();

        if (empty($faqArray)) {
            return null;
        }

        $faqs = array();

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
        $faqs = $this->getFaqs(array('id' => $id));
        return reset($faqs);
    }

    /**
     * Gets faq by catId.
     *
     * @param integer $catId
     * @return FaqModel|null
     */
    public function getFaqsByCatId($catId)
    {
        $faqArray = $this->db()->select('*')
            ->from('faqs')
            ->where(array('cat_id' => $catId))
            ->execute()
            ->fetchRows();

        if (empty($faqArray)) {
            return null;
        }

        $faqs = array();

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
     * Inserts or updates faq model.
     *
     * @param FaqModel $faq
     */
    public function save(FaqModel $faq)
    {
        $fields = array
        (
            'cat_id' => $faq->getCatId(),
            'question' => $faq->getQuestion(),
            'answer' => $faq->getAnswer()
        );

        if ($faq->getId()) {
            $this->db()->update('faqs')
                ->values($fields)
                ->where(array('id' => $faq->getId()))
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
            ->where(array('id' => $id))
            ->execute();
    }
}
