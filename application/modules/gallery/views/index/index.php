<h3><?php echo $this->getTrans('gallery'); ?></h3>
<?php
$galleryMapper = $this->get('galleryMapper');
$galleryItems = $this->get('galleryItems');

function rec($item, $galleryMapper, $obj)
{
    $subItems = $galleryMapper->getGalleryItemsByParent('1', $item->getId());

    if ($item->getType() === 0){
        echo '<div class="page-header">
              <h4>Kategorie: '.$item->getTitle().'  <small>'.$item->getDesc().'</small>
              </h4><hr>';
    }
    if ($item->getType() != 0){
        echo '<div class="list-group">
                    <a href="'.$obj->getUrl(array('controller' => 'index', 'action' => 'show','id' => $item->getId())).'" class="list-group-item">
                        <h4 class="list-group-item-heading">'.$item->getTitle().'</h4>
                        <p class="list-group-item-text">'.$item->getDesc().'</p>
                    </a>
              </div>';
    }
    if (!empty($subItems)) {
        foreach ($subItems as $subItem) {
            rec($subItem, $galleryMapper, $obj);
        }
    }
}
?>
<div class="col-lg-12">
    <ul class="media-list">
        <?php
            if (!empty($galleryItems)) {
                foreach ($galleryItems as $item) {
                    rec($item, $galleryMapper, $this);
                }
            }
        ?>
    </ul>
</div>