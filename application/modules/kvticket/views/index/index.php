<?php $userMapper = $this->get('userMapper'); ?>
<?php $catMapper = $this->get('catMapper'); ?>

<h1><?=$this->getTrans('menuTickets') ?></h1>
<?php if ($this->get('tickets')): ?>
    <div class="row">
        <div class="col-xl-12">
            <div class="searchfield">
                <input class="form-control" id="system-search" name="q" placeholder="<?=$this->getTrans('search') ?>" />
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-striped table-list-search">
                    <colgroup>
                        <col />
                        <col class="col-xl-2" />
                        <col class="col-xl-2" />
                        <col class="col-xl-2" />
                        <col class="col-xl-2" />
                        <col class="col-xl-2" />
                        <col class="col-xl-2" />
                    </colgroup>
                    <thead>
                        <tr>
                            <th>
                                <a href="<?=$this->getUrl(['column' => 'title', 'order' => $this->get('sort_order') == 'ASC'  ? 'desc' : 'asc']) ?>" title="<?=$this->getTrans('title') ?>">
                                    <?=$this->getTrans('title') ?> <i class="fa-solid fa-sort<?=$this->get('sort_column') == 'title' ? '-' . str_replace(['ASC','DESC'],['up','down'], $this->get('sort_order')) : ''; ?>"></i>
                                </a>
                            </th>
                            <th>
                                <a href="<?=$this->getUrl(['column' => 'cat', 'order' => $this->get('sort_order') == 'ASC'  ? 'desc' : 'asc']) ?>" title="<?=$this->getTrans('cat') ?>">
                                    <?=$this->getTrans('cat') ?> <i class="fa-solid fa-sort<?=$this->get('sort_column') == 'cat' ? '-' . str_replace(['ASC','DESC'], ['up','down'], $this->get('sort_order')) : ''; ?>"></i>
                                </a>
                            </th>
                            <th>
                                <a href="<?=$this->getUrl(['column' => 'status', 'order' => $this->get('sort_order') == 'ASC'  ? 'desc' : 'asc']) ?>" title="<?=$this->getTrans('status') ?>">
                                    <?=$this->getTrans('status') ?> <i class="fa-solid fa-sort<?=$this->get('sort_column') == 'status' ? '-' . str_replace(['ASC','DESC'], ['up','down'], $this->get('sort_order')) : ''; ?>"></i>
                                </a>
                            </th>
                            <th>
                                <a href="<?=$this->getUrl(['column' => 'creator', 'order' => $this->get('sort_order') == 'ASC'  ? 'desc' : 'asc']) ?>" title="<?=$this->getTrans('creator') ?>">
                                    <?=$this->getTrans('creator') ?> <i class="fa-solid fa-sort<?=$this->get('sort_column') == 'creator' ? '-' . str_replace(['ASC','DESC'], ['up','down'], $this->get('sort_order')) : ''; ?>"></i>
                                </a>
                            </th>
                            <th>
                                <a href="<?=$this->getUrl(['column' => 'editor', 'order' => $this->get('sort_order') == 'ASC'  ? 'desc' : 'asc']) ?>" title="<?=$this->getTrans('editor') ?>">
                                    <?=$this->getTrans('editor') ?> <i class="fa-solid fa-sort<?=$this->get('sort_column') == 'editor' ? '-' . str_replace(['ASC','DESC'], ['up','down'], $this->get('sort_order')) : ''; ?>"></i>
                                </a>
                            </th>
                            <th>
                                <a href="<?=$this->getUrl(['column' => 'created_at', 'order' => $this->get('sort_order') == 'ASC'  ? 'desc' : 'asc']) ?>" title="<?=$this->getTrans('createdAt') ?>">
                                    <?=$this->getTrans('createdAt') ?> <i class="fa-solid fa-sort<?=$this->get('sort_column') == 'created_at' ? '-' . str_replace(['ASC','DESC'], ['up','down'], $this->get('sort_order')) : ''; ?>"></i>
                                </a>
                            </th>
                            <th>
                                <a href="<?=$this->getUrl(['column' => 'updated_at', 'order' => $this->get('sort_order') == 'ASC'  ? 'desc' : 'asc']) ?>" title="<?=$this->getTrans('updatedAt') ?>">
                                    <?=$this->getTrans('updatedAt') ?> <i class="fa-solid fa-sort<?=$this->get('sort_column') == 'updated_at' ? '-' . str_replace(['ASC','DESC'], ['up','down'], $this->get('sort_order')) : ''; ?>"></i>
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->get('tickets') as $ticket): ?>
                            <?php
                            $creator = $userMapper->getUserById($ticket->getCreator());
                            $editor = $userMapper->getUserById($ticket->getEditor());
                            $createdAt = new \Ilch\Date($ticket->getCreatedAt());
                            $updatedAt = new \Ilch\Date($ticket->getUpdatedAt());
                            $cat = $catMapper->getCategoryById($ticket->getCat());

                            if ($ticket->getStatus() == 1) {
                                $ticketCSS = 'bg-info';
                                $ticketStatus = $this->getTrans('editTickets');
                                $linkcolor = 'text-white';
                            } elseif ($ticket->getStatus() == 2) {
                                $ticketCSS = 'bg-success';
                                $ticketStatus = $this->getTrans('compTickets');
                                $linkcolor = 'text-white';
                            } elseif ($ticket->getStatus() == 3) {
                                $ticketCSS = 'bg-danger';
                                $ticketStatus = $this->getTrans('closeTickets');
                                $linkcolor = 'text-white';
                            } else {
                                $ticketCSS = '';
                                $ticketStatus = $this->getTrans('openTickets');
                                $linkcolor = '';
                            } ?>

                            <tr <?=($ticketCSS) ? 'class="'.$ticketCSS.'"' : '' ?>>
                                <td class="<?=($ticketCSS) ? $ticketCSS : '' ?><?=($this->get('sort_column') == 'title'?' table-active':'') ?>">
                                    <a href="<?=$this->getUrl(['action' => 'show', 'id' => $ticket->getId()]) ?>" class="<?=$linkcolor ?>" title="<?=$this->escape($ticket->getTitle()) ?>">
                                        <?=$this->escape($ticket->getTitle()) ?>
                                    </a>
                                </td>
                                <td class="<?=($ticketCSS) ? $ticketCSS : '' ?><?=($this->get('sort_column') == 'cat'?' table-active':'') ?>">
                                    <?=($cat ? $this->escape($cat->getTitle()) : '') ?>
                                </td>
                                <td class="<?=($ticketCSS) ? $ticketCSS : '' ?><?=($this->get('sort_column') == 'status'?' table-active':'') ?>">
                                    <?=$ticketStatus ?>
                                </td>
                                <td class="<?=($ticketCSS) ? $ticketCSS : '' ?><?=($this->get('sort_column') == 'creator'?' table-active':'') ?>">
                                    <?=($creator) ? $this->escape($creator->getName()) : '' ?>
                                </td>
                                <td class="<?=($ticketCSS) ? $ticketCSS : '' ?><?=($this->get('sort_column') == 'editor'?' table-active':'') ?>">
                                    <?=($editor) ? $this->escape($editor->getName()) : '' ?>
                                </td>
                                <td class="<?=($ticketCSS) ? $ticketCSS : '' ?><?=($this->get('sort_column') == 'created_at'?' table-active':'') ?>">
                                    <?=$createdAt->format('d.m.Y H:i') ?>
                                </td>
                                <td class="<?=($ticketCSS) ? $ticketCSS : '' ?><?=($this->get('sort_column') == 'updated_at'?' table-active':'') ?>">
                                    <?=$updatedAt->format('d.m.Y H:i') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php else: ?>
    <?=$this->getTrans('noTickets') ?>
<?php endif; ?>

<div class="btn btn-outline-secondary float-end">
    <a href="<?=$this->getUrl(['action' => 'new']) ?>">
        <?=$this->getTrans('entry') ?>
    </a>
</div>
