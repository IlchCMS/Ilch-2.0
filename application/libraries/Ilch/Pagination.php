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
        if ($page == null) {
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
     * @param integer $rows
     */
    public function setRowsPerPage($rowsPerPage)
    {
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
     * @return string
     */
    public function getHtml($view, $urlArray)
    {
        if (empty($this->rows)) {
            return;
        }

        $page = $this->page;
        $adj = '1';
        $prev = $page - 1;
        $next = $page + 1;
        $lastPage = ceil($this->rows/$this->rowsPerPage);
        $lpm1 = $lastPage - 1;
        $html = '';
        if($lastPage > 1)
        {
            $html .= '<ul class="pagination">';
            if ($page > 1) {
                $urlArray['page'] = $prev;
                $html .= '<li><a href="' . $view->getUrl($urlArray).'">Previous</a></li>';
            } else {
                $html .= '<span class="hide">Previous</span>';
            }
            if ($lastPage < 1 + ($adj * 2))
            {
                for ($counter = 1; $counter <= $lastPage; $counter++)
                {
                    if ($counter == $page) {
                        $html.= '<li><span class="current">'.$counter.'</span></li>';
                    } else {
                        $urlArray['page'] = $counter;
                        $html.= '<li><a href="'.$view->getUrl($urlArray).'">'.$counter.'</a></li>';
                    }
                }
            }
            elseif($lastPage > 1 + ($adj * 2))
            {
                if($page < 1 + ($adj * 2))
                {
                    for ($counter = 1; $counter < 1 + ($adj * 2); $counter++)
                    {
                        if ($counter == $page){
                            $html.= '<li><span class="current">'.$counter.'</span></li>';
                        } else {
                            $urlArray['page'] = $counter;
                            $html.= '<li><a href="'.$view->getUrl($urlArray).'">'.$counter.'</a></li>';
                        }

                    }
                    $urlArray['page'] = $lpm1;
                    $html.= '<li><a href="'.$view->getUrl($urlArray).'">'.$lpm1.'</a></li>';
                    $urlArray['page'] = $lastPage;
                    $html.= '<li><a href="'.$view->getUrl($urlArray).'">'.$lastPage.'</a></li>';
                }
                elseif($lastPage - ($adj * 2) > $page && $page > ($adj * 2))
                {
                    $urlArray['page'] = 1;
                    $html.= '<li><a href="'.$view->getUrl($urlArray).'">1</a></li>';
                    $urlArray['page'] = 2;
                    $html.= '<li><a href="'.$view->getUrl($urlArray).'">2</a></li>';
                    for ($counter = $page - $adj; $counter <= $page + $adj; $counter++)
                    {
                        if ($counter == $page) {
                            $html.= '<li class="active"><a href="#">'.$counter.' <span class="sr-only"></span></a></li>';
                        } else {
                            $urlArray['page'] = $counter;
                            $html.= '<li><a href="'.$view->getUrl($urlArray).'">'.$counter.'</a></li>';
                        }
                    }
                    $urlArray['page'] = $lpm1;
                    $html.= '<li><a href="'.$view->getUrl($urlArray).'">'.$lpm1.'</a></li>';
                    $urlArray['page'] = $lastPage;
                    $html.= '<li><a href="'.$view->getUrl($urlArray).'">'.$lastPage.'</a></li>';
                }
                else
                {
                    $urlArray['page'] = 1;
                    $html.= '<li><a href="'.$view->getUrl($urlArray).'">1</a></li>';
                    $urlArray['page'] = 2;
                    $html.= '<li><a href="'.$view->getUrl($urlArray).'">2</a></li>';
                    for ($counter = $lastPage - (1 + ($adj * 2)); $counter <= $lastPage; $counter++)
                    {
                        if ($counter == $page) {
                            $html.= '<li class="active"><a href="#">'.$counter.' <span class="sr-only"></span></a></li>';
                        } else {
                            $urlArray['page'] = $counter;
                            $html.= '<li><a href="'.$view->getUrl($urlArray).'">'.$counter.'</a></li>';
                        }
                    }
                }
            }
            if ($page < $counter - 1) {
                $urlArray['page'] = $next;
                $html.= '<li><a href="'.$view->getUrl($urlArray).'">Next</a></li>';
            } else {
                $html.= '<li><span class="hide">Next</span></li>';
            }
            $html.= '</ul>';
        }
        return $html;
    }
}
