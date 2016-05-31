<?php if ($this->get('smilies') != ''): ?>
    <?php foreach ($this->get('smilies') as $smilies): ?>
        <img class="image" src="<?=$this->getBaseUrl($this->escape($smilies->getUrl())) ?>"
             title="<?=$this->escape($smilies->getName()) ?>"
             data-url="<?=$smilies->getUrl() ?>"
             alt="<?=$smilies->getName() ?>"
        >
    <?php endforeach; ?>
<?php endif;?>

<script>
$(".image").click(function() {
    var dialog = window.top.CKEDITOR.instances;
    var url = $(this).data('url');
    var base = '<?=$this->getBaseUrl() ?>';
    var imgHtml = "<img src=" + base + url + " alt='' />";
    dialog.ck_1.insertHtml(imgHtml);

    window.top.$('#smiliesModal').modal('hide');
});
</script>
