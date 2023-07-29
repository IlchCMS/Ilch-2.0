<h1><?=$this->getTrans('menuConvert') ?></h1>

<div class="table-responsive">
    <table id="sortTable" class="table table-hover table-striped">
        <colgroup>
            <col>
            <col>
        </colgroup>
        <thead>
        <tr>
            <th class="sort"><?=$this->getTrans('module') ?>/<?=$this->getTrans('layout') ?></th>
            <th><?=$this->getTrans('progress') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($_SESSION['bbcodeconvert_toConvert'] as $moduleOrLayout) : ?>
            <tr class="filter">
                <td><?=$this->getTrans($moduleOrLayout['key']) ?></td>
                <td>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: <?=($moduleOrLayout['progress']/$moduleOrLayout['count']) * 100 ?>%" aria-valuenow="<?=$moduleOrLayout['progress'] ?>" aria-valuemin="0" aria-valuemax="<?=$moduleOrLayout['count'] ?>"></div>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<p id="result"><?=($this->get('workDone')) ? $this->getTrans('workDone') : '' ?></p>
<?php if (!$this->get('workDone')) : ?>
    <button id="cancelConversion" class="btn btn-default" onclick="cancel(this)"><?=$this->getTrans('cancel') ?></button>
<?php endif; ?>

<script>
    let interval;

    function redirect () {
        interval = setInterval(myURL, 3000);
        let result = document.getElementById("result");
        result.innerHTML = "<?=$this->getTrans('redirectAfterPause') ?>";
    }

    function myURL() {
        document.location.href = '<?=$this->getUrl(['action' => 'convert'], null, true) ?>';
        clearInterval(interval);
    }

    if (<?=($this->get('redirectAfterPause')) ?? 0 ?>) {
        redirect();
    }

    function cancel(event) {
        clearInterval(interval);
        result.innerHTML = "<?=$this->getTrans('cancelled') ?>";
        event.remove();
    }
</script>
