<h3><?php echo $this->getTrans('gallery'); ?></h3>
<?php
$galleryMapper = $this->get('galleryMapper');
$galleryItems = $this->get('galleryItems');
$imageMapper = $this->get('imageMapper');

function rec($item, $galleryMapper, $obj, $imageMapper)
{
    $subItems = $galleryMapper->getGalleryItemsByParent('1', $item->getId());
    $image = $imageMapper->getCountImageById($item->getId());

    if ($item->getType() === 0){
        echo '<div class="page-header">
              <h4>Kategorie: '.$item->getTitle().'  <small>'.$item->getDesc().'</small>
              </h4><hr>';
    }
    if ($item->getType() != 0){
        echo '<div class="list-group">
                    <a href="'.$obj->getUrl(array('controller' => 'index', 'action' => 'show','id' => $item->getId())).'" class="list-group-item">
                        <h4 class="list-group-item-heading">'.$item->getTitle().'
                        <span class="pull-right">Bilder: '. count($image).'</span></h4>
                        <p class="list-group-item-text">'.$item->getDesc().'</p>
                    </a>
                    
              </div>';
    }
    if (!empty($subItems)) {
        foreach ($subItems as $subItem) {
            rec($subItem, $galleryMapper, $obj, $imageMapper);
        }
    }
}
?>
<div class="col-lg-12">
    <ul class="media-list">
        <?php
            if (!empty($galleryItems)) {
                foreach ($galleryItems as $item) {
                    rec($item, $galleryMapper, $this, $imageMapper);
                }
            }
        ?>
    </ul>
</div>