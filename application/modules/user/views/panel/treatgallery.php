<div class="modal fade" id="MediaModal" tabindex="-1" role="dialog" aria-labelledby="MediaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" 
                        class="close" 
                        data-dismiss="modal" 
                        aria-hidden="true">&times;
                </button>
                <h4 class="modal-title" id="MediaModalLabel">Media</h4>
            </div>
            <div class="modal-body"><iframe frameborder="0"></iframe></div>
            <div class="modal-footer">
                <button type="button" 
                        class="btn btn-default" 
                        data-dismiss="modal">Close
                </button>
            </div>
        </div>
    </div>
</div>

<link href="<?=$this->getModuleUrl('static/css/user.css') ?>" rel="stylesheet">

<div id="panel">
    <div class="row">
        <div class="col-lg-2">
            <?php include APPLICATION_PATH.'/modules/user/views/panel/navi.php'; ?>
        </div>
        <div class="col-lg-10">
            <legend>
                <?=$this->getTrans('gallery') ?>: <?=$this->get('galleryTitle') ?>
                <a id="<?=$this->getRequest()->getParam('id') ?>" href="javascript:media()">
                    <i class="fa fa-cloud-upload"></i>
                </a>
            </legend>
            <?=$this->get('pagination')->getHtml($this, array('action' => 'treatgallery', 'id' => $this->getRequest()->getParam('id'))) ?>
            <?php if ($this->get('image')): ?>
                <form class="form-horizontal" method="POST" action="">
                    <?=$this->getTokenField() ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <colgroup>
                                <col class="icon_width" />
                                <col class="icon_width" />
                                <col class="icon_width" />
                                <col class="col-lg-2" />
                                <col class="col-lg-4" />
                                <col />
                            </colgroup>
                            <thead>
                                <tr>
                                    <th><?=$this->getCheckAllCheckbox('check_gallery') ?></th>
                                    <th></th>
                                    <th></th>
                                    <th><?=$this->getTrans('images') ?></th>
                                    <th><?=$this->getTrans('title') ?></th>
                                    <th><?=$this->getTrans('description') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($this->get('image') as $image): ?>
                                    <tr>
                                        <td><input value="<?=$image->getId() ?>" type="checkbox" name="check_gallery[]" /></td>
                                        <td><?=$this->getEditIcon(array('action' => 'treatgalleryimage', 'gallery' => $this->getRequest()->getParam('id'), 'id' => $image->getId())) ?></td>
                                        <td><?=$this->getDeleteIcon(array('action' => 'delgalleryimage', 'gallery' => $this->getRequest()->getParam('id'), 'id' => $image->getImageId())) ?></td>
                                        <td><img class="image thumbnail img-responsive" src="<?=$this->getUrl().'/'.$image->getImageThumb() ?>"/></td>
                                        <td><?=$image->getImageTitle() ?></td>
                                        <td><?=$image->getImageDesc() ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?=$this->getListBar(array('delete' => 'delete')) ?> 
                </form>
            <?php else: ?>
                <?=$this->getTrans('noImages') ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
<?=$this->getMedia()
        ->addActionButton($this->getUrl('user/panel/treatgallery/id/'.$this->getRequest()->getParam('id')))
        ->addMediaButton($this->getUrl('user/iframe/multi/type/multi/id/'))
        ->addUploadController($this->getUrl('user/panel/uploadgallery'))
?>

function reload(){
    setTimeout(function(){window.location.reload(1);}, 1000);
};
</script>
