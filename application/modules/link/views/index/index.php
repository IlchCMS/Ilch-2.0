<?php
$categories = $this->get('categorys');
$links = $this->get('links');

if (!empty($categories)) {
?>
<table class="table table-striped table-responsive">
    <colgroup>
        <col />
        <col class="col-lg-1" />
    </colgroup>
    <thead>
        <tr>
            <th><?php echo $this->getTrans('category'); ?></th>
            <th style="text-align:center"><?php echo $this->getTrans('links'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($this->get('categorys') as $category) {
                echo '<tr>';
                $getDesc = $this->escape($category->getDesc());
                
                if ($getDesc != '') {
                    $getDesc = '&raquo; '.$this->escape($category->getDesc());
                }else{
                    $getDesc = '';
                }
                
                echo '<td><a href='.$this->getUrl(array('action' => 'index', 'cat_id' => $category->getId())).' title="'.$this->escape($category->getName()).'">'.$this->escape($category->getName()).'</a><br>'.$getDesc.'</td>';    
                echo '<td align="center" style="vertical-align:middle">'.$category->getLinksCount().'</td>';
                echo '</tr>';
            }
        ?>
    </tbody>
</table>
<br />
<?php } ?>


<table class="table table-striped table-responsive">
    <colgroup>
        <col />
        <col class="col-lg-1" />
    </colgroup>
    <thead>
        <tr>
            <th><?php echo $this->getTrans('links'); ?></th>
            <th style="text-align:center"><?php echo $this->getTrans('hits'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($links)) {
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
                
                echo '<td><a href='.$this->getUrl(array('action' => 'redirect', 'link_id' => $link->getId())).' target="_blank" title="'.$this->escape($link->getName()).'">'.$getBanner.'</a><br />'.$getDesc.'</td>';    
                echo '<td align="center" style="vertical-align:middle">'.$this->escape($link->getHits()).'</td>';
                echo '</tr>';
            }

        }  else {
            echo '<td colspan="2">'.$this->getTrans('noLinks').'</td>';
        } ?>
    </tbody>
</table>
