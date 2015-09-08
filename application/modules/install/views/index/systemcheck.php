<table class="table table-hover">
    <thead>
        <tr>
            <th></th>
            <th><?=$this->getTrans('required') ?></th>
            <th><?=$this->getTrans('available') ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?=$this->getTrans('phpVersion') ?></td>
            <td class="text-success">>= 5.4.0</td>
            <td class="<?php
                        if (version_compare(phpversion(), '5.4.0', '>=')) {
                            echo 'text-success';
                        } else {
                            echo 'text-danger';
                        }
                        ?>">
                <?=$this->get('phpVersion') ?>
            </td>
        </tr>
        <tr>
            <td><?=$this->getTrans('writable').' "/application/"' ?></td>
            <td class="text-success"><?=$this->getTrans('writable') ?></td>
            <td>
                <?php
                    if (is_writable(CONFIG_PATH)) {
                        echo '<span class="text-success">'.$this->getTrans('writable').'</span>';
                    } else {
                        echo '<span class="text-danger">'.$this->getTrans('notWritable').'</span>';
                    }
                ?>
            </td>
        </tr>
        <tr>
            <td><?=$this->getTrans('writable').' "/application/modules/media/static/upload/"' ?></td>
            <td class="text-success"><?=$this->getTrans('writable') ?></td>
            <td>
                <?php
                    if (is_writable(APPLICATION_PATH.'/modules/media/static/upload/')) {
                        echo '<span class="text-success">'.$this->getTrans('writable').'</span>';
                    } else {
                        echo '<span class="text-danger">'.$this->getTrans('notWritable').'</span>';
                    }
                ?>
            </td>
        </tr>
        <tr>
            <td><?=$this->getTrans('writable').' "/application/modules/user/static/upload/avatar/"' ?></td>
            <td class="text-success"><?=$this->getTrans('writable') ?></td>
            <td>
                <?php
                    if (is_writable(APPLICATION_PATH.'/modules/user/static/upload/avatar/')) {
                        echo '<span class="text-success">'.$this->getTrans('writable').'</span>';
                    } else {
                        echo '<span class="text-danger">'.$this->getTrans('notWritable').'</span>';
                    }
                ?>
            </td>
        </tr>
        <tr>
            <td><?=$this->getTrans('writable').' "/application/modules/events/static/upload/image/"' ?></td>
            <td class="text-success"><?=$this->getTrans('writable') ?></td>
            <td>
                <?php
                    if (is_writable(APPLICATION_PATH.'/modules/events/static/upload/image/')) {
                        echo '<span class="text-success">'.$this->getTrans('writable').'</span>';
                    } else {
                        echo '<span class="text-danger">'.$this->getTrans('notWritable').'</span>';
                    }
                ?>
            </td>
        </tr>
        <tr>
            <td><?=$this->getTrans('writable').' "/.htaccess"' ?></td>
            <td class="text-success"><?=$this->getTrans('writable') ?></td>
            <td>
                <?php
                    if (is_writable(APPLICATION_PATH.'/../.htaccess')) {
                        echo '<span class="text-success">'.$this->getTrans('writable').'</span>';
                    } else {
                        echo '<span class="text-danger">'.$this->getTrans('notWritable').'</span>';
                    }
                ?>
            </td>
        </tr>
    </tbody>
</table>
