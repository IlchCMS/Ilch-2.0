<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\Layout\Helper;

use Ilch\Design\Base;
use Ilch\Layout\Helper\ModalDialog\Model as ModalDialogModel;

/**
 * Class for usage in conjunction with the ModalDialogModel in getModalDialog() to offer a more flexible way of getting a modal dialog.
 *
 * @since Ilch 2.2.0
 */
class GetModalDialog
{
    protected $model = null;
    protected $base = null;

    public function __construct(ModalDialogModel $model, Base $base)
    {
        $this->model = $model;
        $this->base = $base;
    }

    public function __toString()
    {
        $html = '<div class="modal fade" id="' . $this->model->getId() . '"' . ($this->model->getClass() ? ' class="' . $this->model->getClass() . '"' : '') . '>
            <div class="modal-dialog' . ($this->model->getInnerClass() ? ' ' . $this->model->getInnerClass() : '') . '">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modalLabel">' . $this->model->getTitle() . '</h4>
                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        ' . $this->model->getContent() . '
                    </div>
                    <div class="modal-footer">';
        if ($this->model->isSubmit() != null) {
            $html .= '<button type="button"
                                 class="btn btn-primary"
                                 id="modalButton">' . $this->base->getTrans('ack') . '
                            </button>
                            <button type="button"
                                    class="btn btn-outline-secondary"
                                    data-bs-dismiss="modal">' . $this->base->getTrans('cancel') . '
                            </button>';
        } else {
            $html .= '<button type="button"
                                class="btn btn-primary"
                                data-bs-dismiss="modal">
                            ' . $this->base->getTrans('close') . '
                            </button>';
        }
        $html .= '</div>
                </div>
            </div>
        </div>';

        return $html;
    }
}
