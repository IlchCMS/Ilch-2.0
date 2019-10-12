<?php 
$rules = $this->get('rules');

function rec($item, $obj)
{
    $parentItem = null;
    $subItems = $obj->get('rulesMapper')->getRulesItemsByParent($item->getId());
    $adminAccess = null;
    if ($obj->getUser()) {
        $adminAccess = $obj->getUser()->isAdmin();
    }
    $subItemsFalse = false;
    if ($item->getParent_Id() === 0) {
        if (!empty($subItems)) {
            foreach ($subItems as $subItem) {
                if ($subItem->getAccess() == '' || is_in_array($obj->get('groupIdsArray'), explode(',', $subItem->getAccess())) || $adminAccess == true) {
                     $subItemsFalse = true;
                }
            }
        } else {
            $subItemsFalse = true;
        }
    }

    $paragraph = $obj->escape($item->getParagraph());

    if ($item->getParent_Id() === 0 and $subItemsFalse == true and ($item->getAccess() == '' || is_in_array($obj->get('groupIdsArray'), explode(',', $item->getAccess())) || $adminAccess == true)) {
    echo '<div class="card">
    <div class="card-header" id="paragraph'.$paragraph.'">
        <h3 class="mb-0" data-toggle="collapse" data-target="#paragraph0_'.$paragraph.'" aria-expanded="false" aria-controls="paragraph0_'.$paragraph.'">
            <a href="#paragraph'.$paragraph.'"><i class="fa fa-bookmark"></i></a> '.$obj->getTrans('art').' '.$paragraph.' : '.$obj->escape($item->getTitle()).'<span class="pull-right glyphicon glyphicon-'.($obj->get('showallonstart')?'minus':'plus').'"></span>
        </h3>
    </div>
    <div id="paragraph0_'.$paragraph.'" class="panel-collapse collapse" aria-labelledby="paragraph'.$paragraph.'" data-parent="#accordion">
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
    } elseif ($item->getParent_Id() != 0 and ($item->getAccess() == '' || is_in_array($obj->get('groupIdsArray'), explode(',', $item->getAccess())) || $adminAccess == true)) {
        $parentItem = $obj->get('rulesMapper')->getRuleById($item->getParent_Id());

        echo '
                <tr id="paragraph'.$obj->escape(($parentItem != ''? $obj->escape($parentItem->getParagraph()).'_' : '').$paragraph).'" tabindex="-1">
                    <th>
                        <a href="#paragraph'.$obj->escape($parentItem->getParagraph()).'_'.$paragraph.'"><i class="fa fa-bookmark"></i></a> '.$obj->getTrans('art').' '.$obj->escape($parentItem->getParagraph()).' '.$obj->getTrans('paragraphsign').' '.$obj->escape($paragraph).' : '.$obj->escape($item->getTitle()).'
                    </th>
                </tr>
                <tr>
                    <td>
                        '.$obj->purify($item->getText()).'
                    </td>
                </tr>';
    }
}
?>
<h1><?=$this->getTrans('menuRules') ?></h1>
<?php if ($rules != ''): ?>
    <div id="accordion">
        <?php foreach ($this->get('rules') as $rule): ?>
            <?php rec($rule, $this); ?>
        <?php endforeach; ?>
    </div>
    <script>
        $(document).ready(function() {
            <?php if ($this->get('showallonstart')): ?>
            $('#accordion .collapse').collapse('show');
            <?php endif; ?>
        });

        $('#accordion .collapse')
        .on('shown.bs.collapse', function() {
            $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        })
        .on('hidden.bs.collapse', function() {
            $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        });

        function openAnchorAccordion() {
            if (window.location.hash) {
                let paragraph0 = window.location.hash.split('_');
                let paragraph1 = paragraph0[0].split('paragraph');
                let jQuerytarget = jQuery('body').find('#paragraph0_'+paragraph1[1]);

                if (jQuerytarget.hasClass('collapse')) {
                    let jQuerytargetAccordion;
                    jQuerytarget.collapse('show');

                    if (paragraph0.length == 1) {
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
<?php else: ?>
    <?=$this->getTrans('noRules') ?>
<?php endif; ?>
