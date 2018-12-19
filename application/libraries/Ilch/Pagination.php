<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;

class Pagination
{
    /**
     * @var integer
     */
    protected $page = 1;

    /**
     * @var integer
     */
    protected $rowsPerPage = 20;

    /**
     * @var integer
     */
    protected $rows;

    /**
     * @param integer $page
     */
    public function setPage($page)
    {
        if ($page == null || !is_numeric($page)) {
            $page = 1;
        }

        $this->page = $page;
    }

    /**
     * @param integer $rows
     */
    public function setRows($rows)
    {
        $this->rows = $rows;
    }

    /**
     * @param $rowsPerPage
     * @intern param int $rowsPerPage
     */
    public function setRowsPerPage($rowsPerPage)
    {
        if ($rowsPerPage == null) {
            $rowsPerPage = 20;
        }
        $this->rowsPerPage = $rowsPerPage;
    }

    /**
     * @return array
     */
    public function getLimit()
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
    public function getHtml($view, $urlArray)
    {
        if (empty($this->rows)) {
            return '';
        }

        $links = 7;
        $last = ceil($this->rows/$this->rowsPerPage);

        if ($last == 1){
            return '';
        }

        $start = (($this->page - $links) > 0) ? $this->page - $links : 1;
        $end = (($this->page + $links) < $last) ? $this->page + $links : $last;

        $html = '<ul class="pagination">';

        if ($this->page > 1) {
            $urlArray['page'] = $this->page - 1;
            $html .= '<li><a href="'.$view->getUrl($urlArray).'">&laquo;</a></li>';
        }

        if ($start > 1) {
            $urlArray['page'] = 1;
            $html .= '<li><a href="'.$view->getUrl($urlArray).'">1</a></li>';
            $html .= '<li class="disabled"><span>...</span></li>';
        }

        for ($i = $start; $i <= $end; $i++) {
            $class  = ( $this->page == $i ) ? "active" : "";
            $urlArray['page'] = $i;
            $html .= '<li class="'.$class.'"><a href="'.$view->getUrl($urlArray).'">'.$i.'</a></li>';
        }

        if ($end < $last) {
            $urlArray['page'] = $last;
            $html .= '<li class="disabled"><span>...</span></li>';
            $html .= '<li><a href="'.$view->getUrl($urlArray).'">'.$last.'</a></li>';
        }

        if ($last > $this->page) {
            $urlArray['page'] = $this->page + 1;
            $html .= '<li><a href="'.$view->getUrl($urlArray).'">&raquo;</a></li>';
        }

        $html .= '</ul>';

        return $html;
    }
}
