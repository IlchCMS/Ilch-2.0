<?php

use Modules\User\Mappers\User;
use Modules\User\Models\Group;

/** @var Group $group */
$group = $this->get('group');
/** @var User $userMapper */
$userMapper = $this->get('userMapper');
/** @var UserModel[] $groupUsers Map of user id => user model for users assigned to this group. */
$groupUsers = $this->get('groupUsers');

if ($group->getId()) {
    $fieldsetLegend = $this->getTrans('editGroup');
} else {
    $fieldsetLegend = $this->getTrans('addGroup');
}
?>

<style>
    .connectedSortable {
        min-height: 50px;
    }

    #user_filter_count {
        color: #6c757d;
        font-size: .85rem;
    }
</style>

<h1><?=$fieldsetLegend ?></h1>
<form action="<?=$this->getUrl(['module' => 'user', 'controller' => 'group', 'action' => 'save']) ?>" method="POST" id="groupForm">
    <?=$this->getTokenField() ?>
    <input type="hidden"
           name="group[id]"
           value="<?=$group->getId() ?>" />
    <div class="row mb-3">
        <label for="groupName" class="col-xl-3 col-form-label">
            <?=$this->getTrans('groupName') ?>
        </label>
        <div class="col-xl-9">
            <input type="text"
                   class="form-control required"
                   id="groupName"
                   name="group[name]"
                   placeholder="<?=$this->getTrans('groupName') ?>"
                   value="<?=$this->escape($group->getName()) ?>" />
        </div>
    </div>
    <div class="row mb-3">
        <table class="table table-borderless">
            <colgroup><col class="col-xl-6">
                <col class="col-xl-6">
                <col>
            </colgroup>
            <thead>
            <tr>
                <th class="th-lg table-light"><?=$this->getTrans('users') ?></th>
                <th class="th-lg table-light"><?=$this->getTrans('assignedUsers') ?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <div class="unassigned_users_list" id="unassigned_users_list">
                        <input type="text"
                               class="form-control mb-2"
                               id="user_filter"
                               placeholder="<?=$this->getTrans('searchUsers') ?>" />
                        <div id="user_filter_count" class="mb-2"></div>
                        <ol id="unassigned_users" class="sortable connectedSortable"></ol>
                    </div>
                </td>
                <td>
                    <div class="assigned_users_list" id="assigned_users_list">
                        <ol id="assigned_users" class="sortable connectedSortable">
                            <?php foreach ($this->get('groupUsersList') as $user_Id) : ?>
                                <?php $user = $groupUsers[$user_Id] ?? $userMapper->getDummyUser(); ?>
                                <li class="handle_li" value="<?=$user_Id ?>"><div><span class="fa-solid fa-sort"></span> <?=$user->getName() ?></div></li>
                            <?php endforeach; ?>
                        </ol>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <input type="hidden" id="hiddenMenu" name="hiddenMenu" value="" />
    <?=$this->getSaveBar() ?>
</form>

<script>
    $('#groupForm').validate();

    $(function () {
        const searchUrl = "<?= $this->getUrl(['module' => 'user', 'controller' => 'group', 'action' => 'searchUsers']) ?>";
        const showingOf = <?= json_encode($this->getTrans('showingOf')) ?>;
        const noUsersFound = <?= json_encode($this->getTrans('noUsersFound')) ?>;

        // Local index of the users currently in the assigned list (string ids).
        const assignedIds = new Set(
            $.map($('#assigned_users .handle_li'), function (li) {
                return String($(li).attr('value'));
            })
        );

        let searchRequest = null;
        let filterTimer = null;

        function escapeHtml(text) {
            return String(text)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function refreshLists() {
            // Required after every manual DOM change (AJAX render, double-click)
            // so that moved/new items are recognized as drag handles.
            $('#unassigned_users, #assigned_users').sortable('refresh');
        }

        function renderList(users) {
            const $list = $('#unassigned_users').empty();
            $.each(users, function (_, user) {
                $list.append(
                    $('<li class="handle_li"></li>')
                        .attr('value', String(user.id))
                        .html('<div><span class="fa-solid fa-sort"></span> ' + escapeHtml(user.name) + '</div>')
                );
            });
            refreshLists();
        }

        function updateCount(shown, total) {
            if (shown === 0 && total === 0) {
                $('#user_filter_count').text(noUsersFound);
            } else if (shown < total) {
                $('#user_filter_count').text(showingOf.replace('%1', shown).replace('%2', total));
            } else {
                $('#user_filter_count').text('');
            }
        }

        function searchUsers(query) {
            if (searchRequest) {
                searchRequest.abort(); // stale response from fast typing
            }

            // Ilch 2 reads parameters from the URL path:
            // index.php/admin/user/group/searchUsers/query/<value>
            const url = query === ''
                ? searchUrl
                : searchUrl + '/query/' + encodeURIComponent(query);

            searchRequest = $.getJSON(url, function (data) {
                // Assigned users never show up in the unassigned list.
                const matches = (data.users || []).filter(function (user) {
                    return !assignedIds.has(String(user.id));
                });
                renderList(matches);
                updateCount(matches.length, data.total || 0);
            });
        }

        // Enter in the search field must not submit the group form;
        // it just runs the (possibly debounced) search immediately.
        $('#user_filter').on('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                if (filterTimer) {
                    clearTimeout(filterTimer);
                    filterTimer = null;
                }
                searchUsers(this.value);
            }
        });

        $('#user_filter').on('input', function () {
            const value = this.value;
            if (filterTimer) {
                clearTimeout(filterTimer);
            }
            filterTimer = setTimeout(function () {
                filterTimer = null;
                searchUsers(value);
            }, 250);
        });

        $('#assigned_users').sortable({
            connectWith: '.connectedSortable',
            dropOnEmpty: true,
            placeholder: 'placeholder',
            receive: function (event, ui) {
                assignedIds.add(String(ui.item.attr('value')));
                refreshLists();
            },
            remove: function (event, ui) {
                assignedIds.delete(String(ui.item.attr('value')));
                refreshLists();
            }
        });

        $('#unassigned_users').sortable({
            connectWith: '.connectedSortable',
            dropOnEmpty: true,
            placeholder: 'placeholder',
            receive: function (event, ui) {
                assignedIds.delete(String(ui.item.attr('value')));
                refreshLists();
            },
            remove: function (event, ui) {
                assignedIds.add(String(ui.item.attr('value')));
                refreshLists();
            }
        });

        $('#unassigned_users, #assigned_users').disableSelection();

        $(document).on('dblclick', '.connectedSortable .handle_li', function () {
            const $item = $(this);
            const id = String($item.attr('value'));
            if ($item.parent().attr('id') === 'unassigned_users') {
                $item.detach().appendTo('#assigned_users');
                assignedIds.add(id);
            } else {
                $item.detach().appendTo('#unassigned_users');
                assignedIds.delete(id);
            }
            refreshLists();
        });

        $('#groupForm').on('submit', function () {
            $('#hiddenMenu').val(JSON.stringify($('#assigned_users').sortable('toArray', { attribute: 'value' })));
        });

        // Initial load: first 50 users, same first impression as before.
        searchUsers('');
    });
</script>
