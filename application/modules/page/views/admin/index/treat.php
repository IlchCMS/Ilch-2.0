<div class="modal fade" id="MediaModal" tabindex="-1" role="dialog"  aria-labelledby="MediaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="MediaModalLabel">Media</h4>
            </div>
            <div class="modal-body">
                <iframe frameborder="0"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<form class="form-horizontal" method="POST" action="">
    <?php echo $this->getTokenField(); ?>
    <legend>
    <?php
        if ($this->get('page') != '') {
            echo $this->getTrans('editPage');
        } else {
            echo $this->getTrans('addPage');
        }
    ?>
    </legend>
    <div class="form-group">
        <label for="pageTitleInput" class="col-lg-2 control-label">
            <?php echo $this->getTrans('pageTitle'); ?>:
        </label>
        <div class="col-lg-2">
            <input class="form-control"
                   type="text"
                   name="pageTitle"
                   id="pageTitleInput"
                   value="<?php if ($this->get('page') != '') { echo $this->escape($this->get('page')->getTitle()); } ?>" />
        </div>
    </div>
    <div class="form-group">
        <textarea class="form-control" id="ilch_html" name="pageContent"><?php if ($this->get('page') != '') { echo $this->get('page')->getContent(); } ?></textarea>
    </div>
    <?php
        if ($this->get('multilingual') && $this->getRequest()->getParam('locale') != '') {
    ?>
    <div class="form-group">
        <label for="pageLanguageInput" class="col-lg-2 control-label">
            <?php echo $this->getTrans('pageLanguage'); ?>:
        </label>
        <div class="col-lg-2">
            <select class="form-control" name="pageLanguage" id="pageLanguageInput">
                <?php
                foreach ($this->get('languages') as $key => $value) {
                    $selected = '';

                    if ($key == $this->get('contentLanguage')) {
                        continue;
                    }

                    if ($this->getRequest()->getParam('locale') == $key) {
                        $selected = 'selected="selected"';
                    }

                    echo '<option '.$selected.' value="'.$key.'">'.$this->escape($value).'</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <?php
    }
    ?>
    <div class="form-group">
        <label for="pagePerma" class="col-lg-2 control-label">
            <?php echo $this->getTrans('permaLink'); ?>:
        </label>
        <div class="col-lg-5">
            <?php echo $this->getUrl(); ?>/index.php/<input
                   type="text"
                   name="pagePerma"
                   id="pagePerma"
                   value="<?php if ($this->get('page') != '') { echo $this->escape($this->get('page')->getPerma()); } ?>" />
        </div>
    </div>
    <?php
    if ($this->get('page') != '') {
        echo $this->getSaveBar('editButton');
    } else {
        echo $this->getSaveBar('addButton');
    }
    ?>
</form>
<script>
<?php
$pageID = '';

if ($this->get('page') != '') {
    $pageID = $this->get('page')->getId();
}
?>
$('#pageTitleInput').change
(
    function () {
        $('#pagePerma').val
        (
            $(this).val()
            .toLowerCase()
            .replace(/ /g,'-')+'.html'
        );
    }
);

$('#pageLanguageInput').change
(
    this,
    function () {
        top.location.href = '<?php echo $this->getUrl(array('id' => $pageID)); ?>/locale/'+$(this).val();
    }
);

var iframeUrlImage = "<?=$this->getUrl('admin/media/iframe/index/type/image/');?>";
var iframeUrlFile = "<?=$this->getUrl('admin/media/iframe/index/type/file/');?>";
var iframeUrlMedia = "<?=$this->getUrl('admin/media/iframe/index/type/media/');?>";
</script>
