<?php
/**
 * @copyright Balthazar3k 2014
 * @package Eventplaner 2.0
 */
$event = $this->get('event');
use User\Mappers\User as UserMapper;
$user = new UserMapper;
?>

<center>
    <h1>
        <?=(!empty($event->getTitle()) ? $event->getTitle() : $event->getEvent())?><br />
        <span class="small"><b><?=$this->getTrans(date('w', strtotime($event->getStart())));?>, <?=date('d.m.Y', strtotime($event->getStart()));?></b></span>
    </h1>
</center>

<table class="table table-hover table-striped">

    <tr valign="middle">
	
		
        <td width="33%">
            <div><b><?=(!empty($event->getTitle()) ? $event->getTitle() : $event->getEvent())?></b></div>
            <span class="small"><?=$event->getEvent().' '.$event->getRegistrations()?></span><br />
            <span class="small"><b><?=$this->getTrans(date('w', strtotime($event->getStart())));?>, <?=date('d.m.Y', strtotime($event->getStart()));?></b></span>

        </td>
        
        <td width="33%" align="center">
            <h4>
                <?=date('H:i', strtotime($event->getStart()));?> - 
                <?=date('H:i', strtotime($event->getEnds()));?><br />
                <span class="small">(<?=round( (strtotime($event->getEnds())-strtotime($event->getStart()))/60/60 , 1) . $this->getTrans('hours');?>)</span>
            </h4>
        </td>
		
        <td  width="33%" align="right">
            <div align="center" style="float: right;">
                <h4><?='2/'.$event->getRegistrations()?></h4>
                <div class="small"><?=$this->getTrans('registrations')?></div>
            </div>
        </td>

    </tr>
    
    <tr >
        <td align="left" colspan="2">
            <p><?=$event->getMessage()?></p>
        </td>
        
        <td align="right" valign="middle" colspan="2">
            <?=$this->getTrans('organizer');?> <b><?=$user->getUserById($event->getOrganizer())->getName();?></b><br />
            <div class="small">
                <?=$this->getTrans('created');?> <?=date('d.m.Y H:i', strtotime($event->getCreated()));?><br />
                <?=$this->getTrans('changed');?> <?=date('d.m.Y H:i', strtotime($event->getChanged()));?>
            </div>
        </td>
    </tr>
</table>

<div id="accordion">
    <h3><?php echo $this->getTrans('register'); ?></h3>
    <div>
        <form action="" class="form-horizontal" method="POST">
            <?php echo $this->getTokenField(); ?>
            <div class="form-group">
                <label class="col-lg-2 control-label">
                    <?=$this->getTrans('name'); ?>*
                </label>
                <div class="col-lg-8">
                    <select class="form-control" name="name">
                        <option value="<?=$_SESSION['user_id']?>"><?=$user->getUserById($_SESSION['user_id'])->getName();?></option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg-2 control-label">
                    <?=$this->getTrans('message'); ?>
                </label>
                <div class="col-lg-8">
                    <textarea id="ilch_bbcode"
                              class="form-control"
                              name="text"
                              required>
                    </textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-offset-2 col-lg-8">
                    <input type="submit" 
                           name="saveEntry" 
                           class="btn"
                           value="<?php echo $this->getTrans('register'); ?>" />
                </div>
            </div>
        </form>
    </div>
    <h3><?php echo $this->getTrans('registrations'); ?></h3>
    <div>
        Es sind zur Zeit keine Anmeldungen vorhanden!
    </div>
</div>


<script>
    $( "#accordion" ).accordion({
        active: 1,
        heightStyle: "content"
    });
</script>