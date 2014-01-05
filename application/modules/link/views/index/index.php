<?php if ($this->get('categorys') != '') { ?>
<table class="table table-bordered table-striped table-responsive">
    <colgroup>
        <col />
        <col class="col-lg-1" />
    </colgroup>
    <thead>
        <tr>
            <th><?php echo $this->trans('category'); ?></th>
            <th style="text-align:center"><?php echo $this->trans('links'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php if ($this->get('categorys') != '') {
            foreach ($this->get('categorys') as $category) {
                echo '<tr>';
                $getDesc = $this->escape($category->getDesc());
                
                if ($getDesc != '') {
                    $getDesc = '&raquo; '.$this->escape($category->getDesc());
                }else{
                    $getDesc = '';
                }
                
                echo '<td><a href='.$this->url(array('action' => 'index', 'cat' => $category->getId())).' target="_blank" title="'.$this->escape($category->getName()).'">'.$this->escape($category->getName()).'</a><br>'.$getDesc.'</td>';    
                echo '<td align="center" style="vertical-align:middle">'.$this->escape($category->getCatId()).'</td>';
                echo '</tr>';
            }
        } 
        ?>
    </tbody>
</table>
<br />
<?php } ?>
        
<table class="table table-bordered table-striped table-responsive">
    <colgroup>
        <col />
        <col class="col-lg-1" />
    </colgroup>
    <thead>
        <tr>
            <th><?php echo $this->trans('links'); ?></th>
            <th align="center"><?php echo $this->trans('show'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php if ($this->get('links') != '') {
            foreach ($this->get('links') as $link) {
                echo '<tr>';
                $getBanner = $this->escape($link->getBanner());
                $getDesc = $this->escape($link->getDesc());
                
                if (!empty($getDesc)) {
                    $getDesc = '&raquo; '.$this->escape($link->getDesc());
                }else{
                    $getDesc = '';
                }
                
                if (!empty($getBanner)) {
                    $getBanner = '<img src="'.$this->escape($link->getBanner()).'">';
                }else{
                    $getBanner = $this->escape($link->getName());
                }
                
                echo '<td><a href='.$this->escape($link->getLink()).' target="_blank" title="'.$this->escape($link->getName()).'">'.$getBanner.'</a><br />'.$getDesc.'</td>';    
                echo '<td align="center" style="vertical-align:middle">'.$this->escape($link->getHits()).'</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr>';
            echo '<td colspan="2">'.$this->trans('noLinks').'</td>';
            echo '</tr>';
        }
        ?>
    </tbody>
</table>