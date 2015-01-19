<link href="<?php echo $this->getStaticUrl('../application/modules/media/static/css/media.css'); ?>" rel="stylesheet">
<legend><?php echo $this->getTrans('media'); ?></legend>
<?php
if ($this->get('medias') != '') {
?>
<div id="filter-media" >
    <div id="filter-panel" class="collapse filter-panel">
        <form class="form-horizontal" method="POST" action="">
            <?=$this->getTokenField()?>
            <div class="form-group">
                <label class="col-lg-2 control-label" for="pref-perpage">Rows per page:</label>
                <div class="col-lg-2">
                    <select id="pref-perpage" class="form-control" name="rows">
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option selected="selected" value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="200">200</option>
                        <option value="300">300</option>
                        <option value="400">400</option>
                        <option value="500">500</option>
                        <option value="1000">1000</option>
                    </select>     
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-2 control-label" for="pref-orderby">Order by:</label>
                <div class="col-lg-2">
                    <select id="pref-orderby" class="form-control" name="order">
                        <option>Descendent</option>
                    </select>    
                </div>
            </div> 
            <div class="form-group">    
                <button type="submit" class="btn btn-default filter-col" name="search" value="search">
                    <span class="fa fa-search"></span> Search
                </button>  
            </div>
        </form>
    </div>    
    <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#filter-panel">
        <span class="fa fa-cogs"></span> Advanced Search
    </button>
</div>
<?=$this->get('pagination')->getHtml($this, $this->get('rows')); ?>
<form class="form-horizontal" method="POST" action="">
<?=$this->getTokenField()?>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
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
                                echo '<img src="'.$this->getStaticUrl('../application/modules/media/static/img/nomedia.png').'" class="img-preview" style="width:50px; height:auto;"  />';
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
                                    <a href="<?php echo $this->getUrl(array('controller' => 'cats', 'action' => 'setCat', 'catid' => $name->getId(), 'mediaid' => $media->getId())) ?>"><?php echo $name->getCatName(); ?></a>
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
    </div>
<?=$this->get('pagination')->getHtml($this, $this->get('rows')); ?>
<?=$this->getListBar(array('delete' => 'delete'))?>
</form>
<?php
} else {
    echo $this->getTrans('noMedias');
    }
?>
