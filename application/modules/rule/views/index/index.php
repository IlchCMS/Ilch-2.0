<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<?php

/** @var \Ilch\View $this */

/** @var \Modules\Rule\Models\Rule[]|null $rules */
$rules = $this->get('rules');

function rec(\Modules\Rule\Models\Rule $item, \Ilch\View $obj)
{
    /** @var \Modules\Rule\Mappers\Rule $rulesMapper */
    $rulesMapper = $obj->get('rulesMapper');

    $subItems = $rulesMapper->getRulesItemsByParent($item->getId(), $obj->get('groupIdsArray'));
    $subItemsFalse = false;
    if ($item->getParentId() === 0) {
        if (!$subItems) {
            $subItemsFalse = true;
        }
    }

    $paragraph = $obj->escape($item->getParagraph());

    if ($item->getParentId() === 0 && !$subItemsFalse) {
        echo '<div class="card">
    <div class="card-header" id="paragraph' . $paragraph . '">
        <h3 class="mb-0" data-bs-toggle="collapse" data-bs-target="#paragraph0_'.$paragraph.'" aria-expanded="false" aria-controls="paragraph0_'.$paragraph.'">
            <a href="#paragraph'.$paragraph.'"><i class="fa fa-bookmark"></i></a> '.$obj->getTrans('art').' '.$paragraph.' : '.$obj->escape($item->getTitle()).'<span class="pull-right bi bi-'.($obj->get('showallonstart')?'dash':'plus').'"></span>
        </h3>
    </div>
    <div id="paragraph0_'.$paragraph.'" class="panel-collapse collapse" aria-labelledby="paragraph'.$paragraph.'" data-bs-parent="#accordion">
        <div class="card-body">
            <table class="table table-striped table-responsive">';

        if (!empty($subItems)) {
            foreach ($subItems as $subItem) {
                rec($subItem, $obj);
            }
        }

        echo '
            </table>
        </div>
    </div>
</div>';
    } elseif ($item->getParentId() != 0) {
        $parentItem = $rulesMapper->getRuleById($item->getParentId());

        echo '
                <tr id="paragraph' . $obj->escape(($parentItem != '' ? $obj->escape($parentItem->getParagraph()) . '_' : '') . $paragraph) . '" tabindex="-1">
                    <th>
                        <a href="#paragraph' . $obj->escape($parentItem->getParagraph()) . '_' . $paragraph . '"><i class="fa-solid fa-bookmark"></i></a> ' . $obj->getTrans('art') . ' ' . $obj->escape($parentItem->getParagraph()) . ' ' . $obj->getTrans('paragraphsign') . ' ' . $obj->escape($paragraph) . ' : ' . $obj->escape($item->getTitle()) . '
                    </th>
                </tr>
                <tr>
                    <td>
                        ' . $obj->purify($item->getText()) . '
                    </td>
                </tr>';
    }
}
?>
<h1><?=$this->getTrans('menuRules') ?></h1>
<?php if ($rules) : ?>
    <div id="accordion">
        <?php foreach ($rules as $rule) : ?>
            <?php rec($rule, $this); ?>
        <?php endforeach; ?>
    </div>
    <script>
        $(document).ready(function() {
            <?php if ($this->get('showallonstart')) : ?>
            $('#accordion .collapse').collapse('show');
            <?php endif; ?>
        });

        $('#accordion .collapse')
        .on('shown.bs.collapse', function() {
            $(this).parent().find(".bi-plus").removeClass("bi-plus").addClass("bi-dash");
        })
        .on('hidden.bs.collapse', function() {
            $(this).parent().find(".bi-dash").removeClass("bi-dash").addClass("bi-plus");
        });

        function openAnchorAccordion() {
            if (window.location.hash) {
                let paragraph0 = window.location.hash.split('_');
                let paragraph1 = paragraph0[0].split('paragraph');
                let jQuerytarget = jQuery('body').find('#paragraph0_'+paragraph1[1]);

                if (jQuerytarget.hasClass('collapse')) {
                    let jQuerytargetAccordion;
                    jQuerytarget.collapse('show');

                    if (paragraph0.length === 1) {
                        jQuerytargetAccordion = jQuery('body').find('#paragraph'+paragraph1[1]);
                    } else {
                        jQuerytargetAccordion = jQuery('body').find(window.location.hash);
                    }
                    ('html, body').animate({ scrollTop: (jQuerytargetAccordion.offset().top)}, 20000);
                }
            }
        }
        openAnchorAccordion();
    </script>
<?php else : ?>
    <?=$this->getTrans('noRules') ?>
<?php endif; ?>
