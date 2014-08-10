<?php $inbox = $this->get('inbox');  ?>
<div class="panel-body" id="boxscroll">
    <ul class="chat">
    <?php foreach ($inbox as $key){ ?>
        <li class="left clearfix">
            <span class="chat-img pull-left">
                <img class="avatar" src="<?php echo $this->getUrl().'/'.$key->getAvatar(); ?>" alt="User Avatar" class="img-circle">
            </span>
            <div class="chat-body clearfix">
                <div class="header">
                    <strong class="primary-font"><?php echo $key->getName() ?></strong> 
                    <small class="pull-right text-muted">
                        <span class="glyphicon glyphicon-time"></span><?php echo $key->getTime() ?>
                    </small>
                </div>
                <p><?php echo nl2br($this->getHtmlFromBBCode($key->getText())) ?></p>
            </div>
        </li>
    <?php }?>
    </ul>
</div>

<style>
    .chat
    {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .chat li
    {
        margin-bottom: 10px;
        padding-bottom: 5px;
        border-bottom: 1px dotted #B3A9A9;
    }

    .chat li.left .chat-body
    {
        margin-left: 60px;
    }

    .chat li.right .chat-body
    {
        margin-right: 60px;
    }
    .chat li .chat-body p
    {
        margin: 0;
        padding-top: 10px;
        color: #777777;
    }
</style>