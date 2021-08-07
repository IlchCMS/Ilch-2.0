<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Article\Mappers;

use Modules\Article\Models\Article as ArticleModel;

class Template extends \Ilch\Mapper
{
    /**
     * Get all templates
     *
     * @param string|null $locale
     * @param null $pagination
     * @return ArticleModel[]|[]
     */
    public function getTemplates(string $locale = null, $pagination = null): array
    {
        $select = $this->db()->select()
            ->fields(['id', 'author_id', 'description', 'keywords', 'title', 'teaser', 'perma', 'content', 'locale', 'img', 'img_source'])
            ->from('articles_templates');

        if ($locale !== null) {
            $select = $select->where(['locale' => $this->db()->escape($locale)]);
        }

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        $articleArray = $result->fetchRows();
        $articles = [];

        if (empty($articleArray)) {
            return $articles;
        }

        foreach ($articleArray as $articleRow) {
            $articleModel = new ArticleModel();
            $articleModel->setId($articleRow['id']);
            $articleModel->setAuthorId($articleRow['author_id']);
            $articleModel->setDescription($articleRow['description']);
            $articleModel->setKeywords($articleRow['keywords']);
            $articleModel->setLocale($articleRow['locale']);
            $articleModel->setTitle($articleRow['title']);
            $articleModel->setTeaser($articleRow['teaser']);
            $articleModel->setPerma($articleRow['perma']);
            $articleModel->setContent($articleRow['content']);
            $articleModel->setImage($articleRow['img']);
            $articleModel->setImageSource($articleRow['img_source']);
            $articles[] = $articleModel;
        }

        return $articles;
    }

    /**
     * Get template by id and locale.
     *
     * @param int $id
     * @return ArticleModel|null
     */
    public function getTemplateById(int $id)
    {
        $select = $this->db()->select()
            ->fields(['id', 'author_id', 'description', 'keywords', 'title', 'teaser', 'perma', 'content', 'locale', 'img', 'img_source'])
            ->from('articles_templates')
            ->where(['id' => $id]);

        $result = $select->execute();
        $articleRow = $result->fetchAssoc();

        if (empty($articleRow)) {
            return null;
        }

        $articleModel = new ArticleModel();
        $articleModel->setId($articleRow['id']);
        $articleModel->setAuthorId($articleRow['author_id']);
        $articleModel->setDescription($articleRow['description']);
        $articleModel->setKeywords($articleRow['keywords']);
        $articleModel->setLocale($articleRow['locale']);
        $articleModel->setTitle($articleRow['title']);
        $articleModel->setTeaser($articleRow['teaser']);
        $articleModel->setContent($articleRow['content']);
        $articleModel->setLocale($articleRow['locale']);
        $articleModel->setPerma($articleRow['perma']);
        $articleModel->setImage($articleRow['img']);
        $articleModel->setImageSource($articleRow['img_source']);

        return $articleModel;
    }

    /**
     * Takes an articleModel and saves it as template.
     *
     * @param ArticleModel $article
     * @return int id
     */
    public function save($article): int
    {
        $exists = $this->db()->select('id')
            ->from('articles_templates')
            ->where(['id' => $article->getId()])
            ->execute()
            ->fetchCell();

        if ($exists) {
            // Update existing template.
            $this->db()->update('articles_templates')
                ->values(['title' => $article->getTitle(),
                    'teaser' => $article->getTeaser(),
                    'description' => $article->getDescription(),
                    'keywords' => $article->getKeywords(),
                    'content' => $article->getContent(),
                    'perma' => $article->getPerma(),
                    'locale' => $article->getLocale(),
                    'img' => $article->getImage(),
                    'img_source' => $article->getImageSource()])
                ->where(['id' => $article->getId()])
                ->execute();

            $id = $article->getId();
        } else {
            // Insert new template
            $id = $this->db()->insert('articles_templates')
                ->values(['author_id' => $article->getAuthorId(),
                    'description' => $article->getDescription(),
                    'keywords' => $article->getKeywords(),
                    'title' => $article->getTitle(),
                    'teaser' => $article->getTeaser(),
                    'content' => $article->getContent(),
                    'perma' => $article->getPerma(),
                    'locale' => $article->getLocale(),
                    'img' => $article->getImage(),
                    'img_source' => $article->getImageSource()])
                ->execute();
        }

        return $id;
    }

    /**
     * Delete a template (with all language contents)
     *
     * @param int $id
     * @return int
     */
    public function delete(int $id): int
    {
        return (int)$this->db()->delete('articles_templates')
            ->where(['id' => $id])
            ->execute();
    }
}
