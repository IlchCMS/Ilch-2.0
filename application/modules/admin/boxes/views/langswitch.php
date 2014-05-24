<form action="get">
    <select class="form-control languageInput" name="language">
        <?php
            foreach ($this->getTranslator()->getLocaleList() as $key => $value) {
                $sel = '';

                if ($this->get('language') == $key) {
                    $sel = 'selected="selected"';
                }
                echo '<option '.$sel.' value="'.$key.'">'.$this->escape($value).'</option>';
            }
        ?>
    </select>
</form>
<script>
    $('.languageInput').change
    (
        this,
        function () {
            top.location.href = '<?php echo $this->getUrl(array('action' => 'index')); ?>/language/'+$(this).val();
        }
    );
</script>