<?php
/**
 * Viewfile for user creation and editing.
 */
$user = $this->get('user');
?>
<script type="text/javascript" src="<?php echo $this->staticUrl('js/chosen/chosen.jquery.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo $this->staticUrl('js/chosen/chosen.proto.min.js') ?>"></script>
<link rel="stylesheet" href="<?php echo $this->staticUrl('css/chosen/bootstrap.css') ?>">
<link rel="stylesheet" href="<?php echo $this->staticUrl('css/chosen/bootstrap-chosen.css') ?>">
<form action="<?php echo $this->url(array('module' => 'user', 'controller' => 'index', 'action' => 'save', 'id' => $user->getId())); ?>" method="POST" class="form-horizontal">
    <input name="user[id]"
           type="hidden"
           value="<?php echo $user->getId(); ?>" />
    <div class="form-group">
        <label for="name"
               class="col-lg-2 control-label">
            <?php echo $this->trans('userName'); ?>
        </label>
        <div class="col-lg-4">
            <input name="user[name]"
                   type="text"
                   class="form-control"
                   placeholder="<?php echo $this->trans('userName'); ?>"
                   value="<?php echo $user->getName(); ?>" />
        </div>
    </div>
    <div class="form-group">
        <label for="email" class="col-lg-2 control-label">
            <?php echo $this->trans('userEmail'); ?>
        </label>
        <div class="col-lg-4">
            <input name="user[email]"
                   type="text"
                   class="form-control"
                   placeholder="<?php echo $this->trans('userEmail'); ?>"
                   value="<?php echo $user->getEmail(); ?>" />
        </div>
    </div>
    <?php
    if ($user->getId()) {
        $dateConfirmed = $user->getDateConfirmed();

        if ($dateConfirmed->getTimestamp() == 0) {
            $dateConfirmed = $this->trans('notConfirmedYet');
        }

        $dateLastActivity = $user->getDateLastActivity();

        if ($dateLastActivity->getTimestamp() == 0) {
            $dateLastActivity = $this->trans('neverLoggedIn');
        }
        ?>
        <div class="form-group">
            <label for="dateCreated" class="col-lg-2 control-label">
                <?php echo $this->trans('userDateCreated'); ?>
            </label>
            <div class="col-lg-4">
                <p class="form-control-static"><?php echo $user->getDateCreated(); ?></p>
            </div>
        </div>
        <div class="form-group">
            <label for="dateConfirmed" class="col-lg-2 control-label">
                <?php echo $this->trans('userDateConfirmed'); ?>
            </label>
            <div class="col-lg-4">
                <p class="form-control-static"><?php echo $dateConfirmed; ?></p>
            </div>
        </div>
        <div class="form-group">
            <label for="dateLastActivity" class="col-lg-2 control-label">
                <?php echo $this->trans('userDateLastActivity'); ?>
            </label>
            <div class="col-lg-4">
                <p class="form-control-static"><?php echo $dateLastActivity; ?></p>
            </div>
        </div>
        <?php
    }
    ?>
    <div class="form-group">
        <label for="assignedGroups" class="col-lg-2 control-label">
                <?php echo $this->trans('assignedGroups'); ?>
        </label>
        <div class="col-lg-4">
            <select id="assignedGroups"
                    class="chosen-select form-control"
                    data-placeholder="<?php echo $this->trans('selectAssignedGroups'); ?>"
                    multiple
                    name="user[groups][]">
                    <?php
                    foreach ($this->get('groupList') as $group) {
                        ?>
                        <option value="<?php echo $group->getId(); ?>"
                                <?php
                                foreach ($user->getGroups() as $assignedGroup) {
                                    if ($group->getId() === $assignedGroup->getId()) {
                                        echo 'selected="selected"';
                                        break;
                                    }
                                }
                                ?>>
                            <?php echo $group->getName(); ?>
                        </option>
                        <?php
                    }
                    ?>
            </select>
        </div>
    </div>
    <div class="content_savebox">
        <input id="formSubmit"
               type="submit"
               class="btn btn-default"
               value="<?php echo $this->trans('save'); ?>" />
    </div>
</form>
<script type="text/javascript">
    $('#assignedGroups').chosen();
</script>