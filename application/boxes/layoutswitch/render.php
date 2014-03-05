<form action="get">
    <select class="layoutInput" name="language">
        <?php
            foreach ($this->get('layouts') as $layout) {
                $name = basename($layout);
                $sel = '';
                
                if ((!isset($_SESSION['layout']) && $name == 'default')
                    ||
                    (isset($_SESSION['layout']) && $_SESSION['layout'] == $name)) {
                    $sel = 'selected="selected"';
                }

                echo '<option '.$sel.' value="'.$name.'">'.$this->escape($name).'</option>';
            }
        ?>
    </select>
</form>
<script>
    $('.layoutInput').change
    (
        this,
        function () {
            top.location.href = '<?php echo $this->getUrl(array('action' => 'index')); ?>/ilch_layout/'+$(this).val();
        }
    );
</script>