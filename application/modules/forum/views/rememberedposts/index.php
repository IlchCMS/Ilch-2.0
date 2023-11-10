<?php

/** @var \Ilch\View $this */

/** @var \Modules\Forum\Models\ForumPost[]|null $rememberedPosts */
$rememberedPosts = $this->get('rememberedPosts');
?>
<link href="<?=$this->getModuleUrl('static/css/forum.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans('rememberedPosts') ?></h1>
<?php if (!empty($rememberedPosts)) : ?>
    <form class="form-horizontal" method="POST">
        <?=$this->getTokenField() ?>
        <div id="rememberedPosts" class="table-responsive">
            <table class="table table-hover table-striped">
                <colgroup>
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col class="icon_width" />
                    <col>
                    <col>
                    <col class="col-xl-2">
                </colgroup>
                <thead>
                <tr class="ilch-head">
                    <th><?=$this->getCheckAllCheckbox('check_rememberedPosts') ?></th>
                    <th></th>
                    <th></th>
                    <th><?=$this->getTrans('topicTitleNote') ?></th>
                    <th><?=$this->getTrans('rememberedPostNote') ?></th>
                    <th><?=$this->getTrans('rememberedPostAddedOn') ?></th>
                </tr>
                </thead>
                <tbody>
                    <?php foreach ($rememberedPosts as $post) : ?>
                        <tr>
                            <td><?=$this->getDeleteCheckbox('check_rememberedPosts', $post->getId()) ?></td>
                            <td><?=$this->getEditIcon(['action' => 'treat', 'id' => $post->getId()]) ?></td>
                            <td><?=$this->getDeleteIcon(['action' => 'delete', 'id' => $post->getId()]) ?></td>
                            <td><a href="<?=$this->getUrl(['module' => 'forum', 'controller' => 'showposts', 'action' => 'index', 'topicid' => $post->getTopicId() . '#' . $post->getPostId()], '') ?>" target="_blank"><?=$this->escape($post->getTopicTitle()) ?></a></td>
                            <td><?=$this->escape($post->getNote()) ?></td>
                            <td><?=$post->getDate() ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?=$this->getListBar(['delete' => 'delete']) ?>
    </form>
<?php else : ?>
    <p><?=$this->getTrans('noRememberedPosts') ?></p>
<?php endif; ?>

<script>
    var deleteSelectedEntries = <?=json_encode($this->getTrans('deleteSelectedEntries')) ?>;
</script>
