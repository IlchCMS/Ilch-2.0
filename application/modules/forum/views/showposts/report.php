<link href="<?=$this->getModuleUrl('static/css/forum.css') ?>" rel="stylesheet">

<div id="forum">
    <div class="row">
        <div class="col-lg-12">
            <div class="new-post-head ilch-head">
                <?=$this->getTrans('reportPost') ?>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="new-topic ilch-bg ilch-border">
                <form class="form-horizontal" method="POST">
                    <?=$this->getTokenField() ?>
                    <div class="row mb-3 <?=$this->validation()->hasError('reason') ? 'has-error' : '' ?>">
                        <label for="reason" class="col-lg-2 control-label">
                            <?=$this->getTrans('reason') ?>
                        </label>
                        <div class="col-lg-10">
                            <select class="form-control" name="reason" id="reason">
                                <option value="1"><?=$this->getTrans('illegalContent') ?></option>
                                <option value="2"><?=$this->getTrans('spam') ?></option>
                                <option value="3"><?=$this->getTrans('wrongTopic') ?></option>
                                <option value="4"><?=$this->getTrans('other') ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="details" class="col-lg-2 control-label">
                            <?=$this->getTrans('details') ?>
                        </label>
                        <div class="col-lg-10">
                            <textarea class="form-control"
                                      id="details"
                                      name="details"
                                      rows="10"></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="offset-lg-2 col-lg-8">
                            <input type="submit"
                                   class="btn btn-primary"
                                   name="reportPost"
                                   value="<?=$this->getTrans('send') ?>" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
