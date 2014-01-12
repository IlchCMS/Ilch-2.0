<table class="table table-hover">
    <thead>
        <tr>
            <th></th>
            <th><?php echo $this->trans('required'); ?></th>
            <th><?php echo $this->trans('available'); ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?php echo $this->trans('phpVersion'); ?></td>
            <td class="text-success">>= 5.4.0</td>
            <td class="<?php
                        if (version_compare(phpversion(), '5.4.0', '>')) {
                            echo 'text-success';
                        } else {
                            echo 'text-danger';
                        }
                        ?>">
                <?php echo $this->get('phpVersion'); ?>
            </td>
        </tr>
        <tr>
            <td><?php echo $this->trans('writable').' "application"' ?></td>
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
    </tbody>
</table>
