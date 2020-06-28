<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Article\Mappers;

use Modules\Article\Models\Article as ArticleModel;

class Template extends \Ilch\Mapper
{
    /**
     * Get all templates
     *
     * @param string $locale
     * @param null $pagination
     * @return ArticleModel[]|[]
     */
    public function getTemplates($locale = '', $pagination = null)
    {
        $select = $this->db()->select()
            ->fields(['id', 'author_id', 'description', 'keywords', 'title', 'teaser', 'perma', 'content', 'locale', 'img', 'img_source'])
            ->from('articles_templates')
            ->where(['locale' => $this->db()->escape($locale)]);

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
     * @param $id
     * @param string $locale
     * @return ArticleModel|null
     */
    public function getTemplateByIdLocale($id, $locale = '')
    {
        $select = $this->db()->select()
            ->fields(['id', 'author_id', 'description', 'keywords', 'title', 'teaser', 'perma', 'content', 'locale', 'img', 'img_source'])
            ->from('articles_templates')
            ->where(['id' => $id, 'locale' => $this->db()->escape($locale)]);

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
    public function save(ArticleModel $article)
    {
        if ($article->getId()) {
            // Existing template
            if ($this->getTemplateByIdLocale($article->getId(), $article->getLocale())) {
                // Update existing template with specific id and locale
                $this->db()->update('articles_templates')
                    ->values
                    (
                        [
                            'title' => $article->getTitle(),
                            'teaser' => $article->getTeaser(),
                            'description' => $article->getDescription(),
                            'keywords' => $article->getKeywords(),
                            'content' => $article->getContent(),
                            'perma' => $article->getPerma(),
                            'img' => $article->getImage(),
                            'img_source' => $article->getImageSource()
                        ]
                    )
                    ->where
                    (
                        [
                            'id' => $article->getId(),
                            'locale' => $article->getLocale()
                        ]
                    )
                    ->execute();
            } else {
                // Insert content with a new locale for an existing template
                $this->db()->insert('articles_templates')
                    ->values
                    (
                        [
                            'id' => $article->getId(),
                            'author_id' => $article->getAuthorId(),
                            'description' => $article->getDescription(),
                            'keywords' => $article->getKeywords(),
                            'title' => $article->getTitle(),
                            'teaser' => $article->getTeaser(),
                            'content' => $article->getContent(),
                            'perma' => $article->getPerma(),
                            'locale' => $article->getLocale(),
                            'img' => $article->getImage(),
                            'img_source' => $article->getImageSource()
                        ]
                    )
                    ->execute();
            }

            $id = $article->getId();
        } else {
            // Insert new template
            $id = $this->db()->insert('articles_templates')
                ->values
                (
                    [
                        'author_id' => $article->getAuthorId(),
                        'description' => $article->getDescription(),
                        'keywords' => $article->getKeywords(),
                        'title' => $article->getTitle(),
                        'teaser' => $article->getTeaser(),
                        'content' => $article->getContent(),
                        'perma' => $article->getPerma(),
                        'locale' => $article->getLocale(),
                        'img' => $article->getImage(),
                        'img_source' => $article->getImageSource()
                    ]
                )
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
    public function delete($id)
    {
        return $this->db()->delete('articles_templates')
            ->where(['id' => $id])
            ->execute();
    }
}
