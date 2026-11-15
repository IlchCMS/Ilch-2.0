<?php

/** @var \Ilch\View $this */

/** @var Modules\Kvteam\Models\Team $team */
$team = $this->get('team');

/** @var \Modules\User\Models\User[] $userList */
$userList = $this->get('userList') ?? [];
?>
<link href="<?=$this->getModuleUrl('static/css/team.css') ?>" rel="stylesheet">

<h1><?=$this->getTrans($team->getId() ? 'edit' : 'add') ?></h1>
<form method="POST" action="" enctype="multipart/form-data">
    <?=$this->getTokenField() ?>
    <div class="row mb-3<?=$this->validation()->hasError('title') ? ' has-error' : '' ?>">
        <label for="title" class="col-xl-2 col-form-label">
            <?=$this->getTrans('title') ?>
        </label>
        <div class="col-xl-4">
            <input type="text"
                   class="form-control"
                   id="title"
                   name="title"
                   value="<?=$this->escape($this->originalInput('title', $team->getTitle())) ?>" />
        </div>
    </div>
    <div class="row mb-3<?=$this->validation()->hasError('userIds') ? ' has-error' : '' ?>">
        <label for="userIds" class="col-xl-2 col-form-label">
            <?=$this->getTrans('members') ?>
        </label>
        <div class="col-xl-4">
            <select class="choices-select form-control"
                    id="userIds"
                    name="userIds[]"
                    data-placeholder="<?=$this->getTrans('selectMembers') ?>"
                    multiple>
                <?php
                /** @var \Modules\User\Models\User $userList */
                ?>
                <?php foreach ($userList as $userList) : ?>
                    <option value="<?=$userList->getId() ?>" selected="selected">
                        <?=$this->escape($userList->getName()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <?=$this->getSaveBar($team->getId() ? 'edit' : 'add') ?>
</form>

<script>
    $(document).ready(function() {
        // The members list is not pre-loaded with all users.
        // Only the currently selected users are pre-rendered as <option>s;
        // everything else is loaded on demand by choices.js through usersAction().
        const select = document.getElementById('userIds');
        const choices = new Choices('#userIds', {
            ...choicesOptions,
            searchEnabled: true
        });

        const searchUrl = '<?=$this->get('usersSearchUrl') ?>' + '/query/';

        let searchTimer;
        select.addEventListener('search', (event) => {
            const query = event.detail.value;
            if (query === '') {
                return;
            }
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                choices.setChoices(
                    () => fetch(searchUrl + encodeURIComponent(query), {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    }).then(response => {
                        if (!response.ok) {
                            throw new Error('users/query request failed: ' + response.status);
                        }
                        return response.json();
                    }),
                    'id',
                    'name',
                    true
                );
            }, 250);
        });
    });
</script>
