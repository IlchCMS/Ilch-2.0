<?php
/**
 * @copyright Balthazar3k 2014
 * @package Eventplaner 2.0
 */

$config = $this->get('config');
?>
<form class="form-horizontal" method="POST" action="<?php echo $this->getUrl(array('action' => 'index')); ?>">
    <?php echo $this->getTokenField(); ?>
    <h3><?php echo $this->getTrans('eventSettings'); ?></h3>
    <br />

    <fieldset>
        <legend><?=$this->getTrans('eventSettingsPages');?></legend>
        <div class="form-group">
            <label for="eventConfig[event_admin_rowsperpage]" class="col-lg-3 control-label">
                <?php echo $this->getTrans('event_admin_rowsperpage'); ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       id=""
                       type="text"
                       name="eventConfig[event_admin_rowsperpage]"
                       placeholder="1-100"
                       value="<?=$config->get('event_admin_rowsperpage');?>" />
            </div>
        </div>
        <div class="form-group">
            <label for="eventConfig[event_index_rowsperpage]" class="col-lg-3 control-label">
                <?php echo $this->getTrans('event_index_rowsperpage'); ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       id=""
                       type="text"
                       name="eventConfig[event_index_rowsperpage]"
                       placeholder="1-100"
                       value="<?=$config->get('event_index_rowsperpage');?>" />
            </div>
        </div>
    </fieldset>
    <br />
    <fieldset>
        <legend><?=$this->getTrans('eventSettingsTime');?></legend>
        
        <div class="form-group">
            <label for="eventConfig[event_starting_time]" class="col-lg-3 control-label">
                <?php echo $this->getTrans('event_starting_time'); ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       id="event_starting_time"
                       type="text"
                       name="eventConfig[event_starting_time]"
                       placeholder="HH:MM"
                       max="5"
                       maxlength="5"
                       value="<?=$config->get('event_starting_time');?>" />
            </div>
        </div>
        
        <div class="form-group">
            <label for="eventConfig[event_ending_time]" class="col-lg-3 control-label">
                <?php echo $this->getTrans('event_ending_time'); ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       id="event_ending_time"
                       type="text"
                       name="eventConfig[event_ending_time]"
                       placeholder="HH:MM"
                       max="5"
                       maxlength="5"
                       value="<?=$config->get('event_ending_time');?>" />
            </div>
        </div>
        
        <div class="form-group">
            <label for="eventConfig[event_steps_time]" class="col-lg-3 control-label">
                <?php echo $this->getTrans('event_steps_time'); ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       id="event_steps_time"
                       type="text"
                       name="eventConfig[event_steps_time]"
                       placeholder="HH:MM"
                       max="5"
                       maxlength="5"
                       value="<?=$config->get('event_steps_time');?>" />
            </div>
        </div>
        
        <div class="form-group">
            <label for="eventConfig[event_close_time]" class="col-lg-3 control-label">
                <?php echo $this->getTrans('event_close_time'); ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       id="event_close_time"
                       type="text"
                       name="eventConfig[event_close_time]"
                       placeholder="HH:MM"
                       max="5"
                       maxlength="5"
                       value="<?=$config->get('event_close_time');?>" />
            </div>
        </div>
        
        <div class="form-group">
            <label for="eventConfig[event_start_time]" class="col-lg-3 control-label">
                <?php echo $this->getTrans('event_start_time'); ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       id="event_start_time"
                       type="text"
                       name="eventConfig[event_start_time]"
                       placeholder="HH:MM"
                       max="5"
                       maxlength="5"
                       value="<?=$config->get('event_start_time');?>" />
            </div>
        </div>
        
        <div class="form-group">
            <label for="eventConfig[event_ends_time]" class="col-lg-3 control-label">
                <?php echo $this->getTrans('event_ends_time'); ?>:
            </label>
            <div class="col-lg-4">
                <input class="form-control"
                       id="event_ends_time"
                       title="Teste die Titelfunkton"
                       type="text"
                       name="eventConfig[event_ends_time]"
                       placeholder="HH:MM"
                       max="5"
                       maxlength="5"
                       value="<?=$config->get('event_ends_time');?>" />
            </div>
        </div>
    </fieldset>
    <br /><br />
    <fieldset>
        <legend><?=$this->getTrans('eventSettingsStatus');?></legend>
    
        <?php foreach( json_decode($config->get('event_status'), true) as $id => $statusArray ): ?>
            <div class="status-box" style="<?=$statusArray['style']?>">
                <h4><b><?=$this->getTrans('status').' '.$id.'. '.$this->getTrans($statusArray['status']);?></b></h4>
                <?php foreach($statusArray as $input => $value ): ?>
                    <div class="form-group">
                        <label for="statusConfig[<?=$id;?>][<?=$input;?>]" class="col-lg-3 control-label">
                            <b><?php echo $this->getTrans($input); ?>:</b>
                        </label>
                        <div class="col-lg-4">
                        <?php if( $input == 'status' ): ?>
                            <input class="form-control"
                                   type="text"
                                   name="statusConfig[<?=$id;?>][<?=$input;?>]"
                                   value="<?=$value;?>"
                                   <?=( $input == 'status' ? 'readonly="readonly"' : '');?>
                                   />
                        <?php else: ?>
                            <textarea class="form-control"
                                   type="text"
                                   rows ="<?=substr_count($value, "\n")?>"
                                   name="statusConfig[<?=$id;?>][<?=$input;?>]"
                                   ><?=$value;?></textarea>
                        <?php endif; ?>    
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
        
    </fieldset>
    
    <?php echo $this->getSaveBar('saveButton'); ?>
</form>

<style type="text/css">
    .status-box {
        padding: 10px 10px 10px 20px;
        border-radius: 10px;
        box-shadow:  inset 1px 1px 0 rgba( 255, 255, 255, 0.8), inset -1px -1px 0 rgba( 0, 0, 0, 0.3);
        margin-bottom: 3px;
    }
    
    .status-box input, .status-box textarea{
        opacity: 0.7;
    }
</style>

<script type="text/javascript">
    $(document).ready(function(){    
        $('input[id*="time"]').keyup(function(){
            var time =  $(this).val();

            if(time.length === 2 && time.indexOf(':')){
                $(this).val(time+':'); 
            }

            var t = time.split(':');

            if(parseInt(t[1]) > 59){
                $(this).val(t[0] +':59');

            }else if(parseInt(t[0]) >= 24){
                $(this).val('23:59');
            } 
        });
    });
</script>


