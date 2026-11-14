<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch;

class Pagination
{
    /**
     * @var int
     */
    protected $page = 1;

    /**
     * @var int
     */
    protected $rowsPerPage = 20;

    /**
     * @var int
     */
    protected $rows = 0;

    /**
     * @param mixed $page
     */
    public function setPage($page)
    {
        // Don't change the type to int to avoid errors as $page typically comes from an URL parameter and can be non-numeric.
        // Changing this would require every usage of this function to be guarded against passing non-numeric values.
        if ($page == null || !is_numeric($page)) {
            $page = 1;
        }

        $this->page = max(1, intval($page));
    }

    /**
     * @param int $rows
     */
    public function setRows(int $rows)
    {
        $this->rows = max(0, $rows);
    }

    /**
     * Get the number of rows.
     *
     * @return int
     */
    public function getRows(): int
    {
        return $this->rows;
    }

    /**
     * @param int|null $rowsPerPage
     */
    public function setRowsPerPage(?int $rowsPerPage)
    {
        if ($rowsPerPage == null || $rowsPerPage < 1) {
            $rowsPerPage = 20;
        }
        $this->rowsPerPage = $rowsPerPage;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getRowsPerPage(): int
    {
        return $this->rowsPerPage;
    }

    /**
     * Get the number of pages (the last page number).
     *
     * @return int
     */
    public function getLastPage(): int
    {
        if (empty($this->rows)) {
            return 0;
        }

        return (int) ceil($this->rows / $this->rowsPerPage);
    }

    /**
     * @return int[]
     */
    public function getLimit(): array
    {
        return [($this->page - 1) * $this->rowsPerPage, $this->rowsPerPage];
    }

    /**
     * Return HTML needed to display the pagination.
     *
     * @param mixed $view
     * @param array $urlArray
     * @param string $pageKey Query parameter name used for the page number
     * @return string
     */
    public function getHtml($view, $urlArray, string $pageKey = 'page'): string
    {
        if (empty($this->rows)) {
            return '';
        }

        $links = 7;
        $last = $this->getLastPage();

        if ($last <= 1) {
            return '';
        }

        $start = (($this->page - $links) > 0) ? $this->page - $links : 1;
        $end = (($this->page + $links) < $last) ? $this->page + $links : $last;

        $html = '<ul class="pagination">';

        if ($this->page > 1) {
            $urlArray[$pageKey] = $this->page - 1;
            $html .= '<li class="page-item"><a href="' . $view->getUrl($urlArray) . '" class="page-link">&laquo;</a></li>';
        }

        if ($start > 1) {
            $urlArray[$pageKey] = 1;
            $html .= '<li class="page-item"><a href="' . $view->getUrl($urlArray) . '" class="page-link">1</a></li>';
            $html .= '<li class="page-item disabled"><span>...</span></li>';
        }

        for ($i = $start; $i <= $end; $i++) {
            $class = ($this->page == $i) ? 'active' : '';
            $urlArray[$pageKey] = $i;
            $html .= '<li class="page-item ' . $class . '"><a href="' . $view->getUrl($urlArray) . '" class="page-link">' . $i . '</a></li>';
        }

        if ($end < $last) {
            $urlArray[$pageKey] = $last;
            $html .= '<li class="page-item disabled"><span>...</span></li>';
            $html .= '<li class="page-item"><a href="' . $view->getUrl($urlArray) . '" class="page-link">' . $last . '</a></li>';
        }

        if ($last > $this->page) {
            $urlArray[$pageKey] = $this->page + 1;
            $html .= '<li class="page-item"><a href="' . $view->getUrl($urlArray) . '" class="page-link">&raquo;</a></li>';
        }

        $html .= '</ul>';

        return $html;
    }
}
