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
    protected $rows;

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

        $this->page = intval($page);
    }

    /**
     * @param int $rows
     */
    public function setRows(int $rows)
    {
        $this->rows = $rows;
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
        if ($rowsPerPage == null) {
            $rowsPerPage = 20;
        }
        $this->rowsPerPage = $rowsPerPage;
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
     * @param $view
     * @param $urlArray
     * @return string
     */
    public function getHtml($view, $urlArray): string
    {
        if (empty($this->rows)) {
            return '';
        }

        $links = 7;
        $last = ceil($this->rows / $this->rowsPerPage);

        if ($last == 1) {
            return '';
        }

        $start = (($this->page - $links) > 0) ? $this->page - $links : 1;
        $end = (($this->page + $links) < $last) ? $this->page + $links : $last;

        $html = '<ul class="pagination">';

        if ($this->page > 1) {
            $urlArray['page'] = $this->page - 1;
            $html .= '<li><a href="' . $view->getUrl($urlArray) . '">&laquo;</a></li>';
        }

        if ($start > 1) {
            $urlArray['page'] = 1;
            $html .= '<li><a href="' . $view->getUrl($urlArray) . '">1</a></li>';
            $html .= '<li class="disabled"><span>...</span></li>';
        }

        for ($i = $start; $i <= $end; $i++) {
            $class = ($this->page == $i) ? 'active' : '';
            $urlArray['page'] = $i;
            $html .= '<li class="' . $class . '"><a href="' . $view->getUrl($urlArray) . '">' . $i . '</a></li>';
        }

        if ($end < $last) {
            $urlArray['page'] = $last;
            $html .= '<li class="disabled"><span>...</span></li>';
            $html .= '<li><a href="' . $view->getUrl($urlArray) . '">' . $last . '</a></li>';
        }

        if ($last > $this->page) {
            $urlArray['page'] = $this->page + 1;
            $html .= '<li><a href="' . $view->getUrl($urlArray) . '">&raquo;</a></li>';
        }

        $html .= '</ul>';

        return $html;
    }
}
