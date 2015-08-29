<table class="table table-hover">
    <thead>
        <tr>
            <th></th>
            <th><?php echo $this->getTrans('required'); ?></th>
            <th><?php echo $this->getTrans('available'); ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?php echo $this->getTrans('phpVersion'); ?></td>
            <td class="text-success">>= 5.4.0</td>
            <td class="<?php
                        if (version_compare(phpversion(), '5.4.0', '>=')) {
                            echo 'text-success';
                        } else {
                            echo 'text-danger';
                        }
                        ?>">
                <?php echo $this->get('phpVersion'); ?>
            </td>
        </tr>
        <tr>
            <td><?php echo $this->getTrans('writable').' "/application/"' ?></td>
            <td class="text-success">writable</td>
            <td>
                <?php
                    if (is_writable(CONFIG_PATH)) {
                        echo '<span class="text-success">writable</span>';
                    } else {
                        echo '<span class="text-danger">not writable</span>';
                    }
                ?>
            </td>
        </tr>
        <tr>
            <td><?php echo $this->getTrans('writable').' "/application/modules/media/static/upload/"' ?></td>
            <td class="text-success">writable</td>
            <td>
                <?php
                    if (is_writable(APPLICATION_PATH.'/modules/media/static/upload/')) {
                        echo '<span class="text-success">writable</span>';
                    } else {
                        echo '<span class="text-danger">not writable</span>';
                    }
                ?>
            </td>
        </tr>
        <tr>
            <td><?php echo $this->getTrans('writable').' "/application/modules/user/static/upload/avatar/"' ?></td>
            <td class="text-success">writable</td>
            <td>
                <?php
                    if (is_writable(APPLICATION_PATH.'/modules/user/static/upload/avatar/')) {
                        echo '<span class="text-success">writable</span>';
                    } else {
                        echo '<span class="text-danger">not writable</span>';
                    }
                ?>
            </td>
        </tr>
        <tr>
            <td><?php echo $this->getTrans('writable').' "/application/modules/events/static/upload/image/"' ?></td>
            <td class="text-success">writable</td>
            <td>
                <?php
                    if (is_writable(APPLICATION_PATH.'/modules/events/static/upload/image/')) {
                        echo '<span class="text-success">writable</span>';
                    } else {
                        echo '<span class="text-danger">not writable</span>';
                    }
                ?>
            </td>
        </tr>
        <tr>
            <td><?php echo $this->getTrans('writable').' "/.htaccess"' ?></td>
            <td class="text-success">writable</td>
            <td>
                <?php
                    if (is_writable(APPLICATION_PATH.'/../.htaccess')) {
                        echo '<span class="text-success">writable</span>';
                    } else {
                        echo '<span class="text-danger">not writable</span>';
                    }
                ?>
            </td>
        </tr>
    </tbody>
</table>
