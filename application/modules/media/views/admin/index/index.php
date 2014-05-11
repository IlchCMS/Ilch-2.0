<link href="<?php echo $this->getStaticUrl('../application/modules/media/static/css/media.css'); ?>" rel="stylesheet">
<legend><?php echo $this->getTrans('media'); ?></legend>
<?php
if ($this->get('medias') != '') {
?>
<form class="form-horizontal" method="POST" action="">
<?=$this->getTokenField()?>
<table class="table table-hover">
    <colgroup>
            <col class="icon_width" />
            <col class="icon_width" />
            <col class="col-xs-1"/>
            <col class="col-lg-2" />
            <col class="col-lg-2" />
            <col />
            <col class="col-lg-2" />
    </colgroup>
    <thead>
        <tr>
            <th><?=$this->getCheckAllCheckbox('check_medias')?></th>
            <th></th>
            <th><?php echo $this->getTrans('type'); ?></th>
            <th></th>
            <th><?php echo $this->getTrans('name'); ?></th>
            <th><?php echo $this->getTrans('date'); ?></th>
            <th>Kategorie</th>
        </tr>
    </thead>
    <tbody><?php foreach ($this->get('medias') as $media) : ?>
        <tr>
            <td><input value="<?=$media->getId()?>" type="checkbox" name="check_medias[]" /></td>
            <td><?=$this->getDeleteIcon(array('action' => 'del', 'id' => $media->getId()))?></td>
            <td><?php echo $media->getEnding(); ?></td>
            <td><?php if(in_array($media->getEnding() , explode(' ',$this->get('media_ext_img')))){
                echo '<a href="'.$this->getStaticUrl().'../'.$media->getUrl().'" title="'.$media->getName().'"><img class="img-preview" src="'.$this->getStaticUrl().'../'.$media->getUrlThumb().'" alt=""></a>';
                    }  else {
                        echo '<img src="'.$this->getStaticUrl('../application/modules/media/static/img/nomedia.jpg').'" class="img-preview" style="width:50px; height:auto;"  />';
                    }
                ?>
            </td>
            <td><?php echo $media->getName(); ?></td>
            <td><?php echo $media->getDatetime(); ?></td>
            <td><div class="btn-group dropdown">
                    <button class="btn btn-default dropdown-toggle" 
                            data-toggle="dropdown" 
                            type="button"><?php echo $media->getCatName(); ?>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu listChooser" role="menu">
                        <?php
                        if ($this->get('catnames') != '') {
                        ?>
                        <?php foreach ($this->get('catnames') as $name) : ?>
                        <li>
                            <a href="<?php echo $this->getUrl(array('action' => 'setCat', 'catid' => $name->getId(), 'mediaid' => $media->getId())) ?>"><?php echo $name->getCatName(); ?></a>
                        </li>
                        <?php endforeach; ?>
                        <?php
                            }
                        ?>
                    </ul>
                </div>
            </td>
        </tr><?php endforeach; ?>
    </tbody>
</table>
<?=$this->getListBar(array('delete' => 'delete'))?>
</form>
<?php
} else {
    echo $this->getTrans('noMedias');
    }
?>