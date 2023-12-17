<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Mappers;

use Ilch\Mapper;
use Modules\Forum\Models\Prefix as PrefixModel;

class Prefixes extends Mapper
{
    /**
     * Get all prefixes
     *
     * @return PrefixModel[]|null
     */
    public function getPrefixes(): ?array
    {
        $prefixesRows = $this->db()->select('*')
            ->from('forum_prefixes')
            ->execute()
            ->fetchRows();

        if (empty($prefixesRows)) {
            return null;
        }

        $prefixes = [];
        foreach($prefixesRows as $prefixesRow) {
            $prefixModel = new PrefixModel();
            $prefixModel->setId($prefixesRow['id']);
            $prefixModel->setPrefix($prefixesRow['prefix']);
            $prefixes[$prefixesRow['id']] = $prefixModel;
        }

        return $prefixes;
    }

    /**
     * Get prefix by id.
     *
     * @param int $id
     * @return PrefixModel|null
     */
    public function getPrefixById(int $id): ?PrefixModel
    {
        $prefixesRow = $this->db()->select('*')
            ->from('forum_prefixes')
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

        if (empty($prefixesRow)) {
            return null;
        }

        $prefix = new PrefixModel();
        $prefix->setId($prefixesRow['id']);
        $prefix->setPrefix($prefixesRow['prefix']);

        return $prefix;
    }

    /**
     * Save new prefix or update existing prefix.
     *
     * @param PrefixModel $model
     * @return void
     */
    public function save(PrefixModel $model)
    {
        if ($model->getId()) {
            $this->db()->update('forum_prefixes')
                ->values([
                    'id' => $model->getId(),
                    'prefix' => $model->getPrefix()
                ])
                ->where(['id' => $model->getId()])
                ->execute();
        } else {
            $this->db()->insert('forum_prefixes')
                ->values([
                    'id' => $model->getId(),
                    'prefix' => $model->getPrefix()
                ])
                ->execute();
        }
    }

    /**
     * Delete prefix by id.
     *
     * @param int $id
     * @return void
     */
    public function deleteById(int $id)
    {
        $this->db()->delete('forum_prefixes', ['id' => $id])
            ->execute();
    }
}
