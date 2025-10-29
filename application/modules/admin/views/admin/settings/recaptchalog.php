
<h1>reCAPTCHA Score-Log</h1>

<form method="post" action="<?= $this->getUrl(['action' => 'recaptchaLog']) ?>" onsubmit="return confirm('Wirklich löschen?');">
    <?=$this->getTokenField() ?>
    <input type="hidden" name="clear_log" value="1">
    <button class="btn btn-danger mb-3" type="submit">
        <i class="fa fa-trash"></i> Log löschen
    </button>
</form>

<?php
$total = count($this->get('entries'));
$suspicious = count(array_filter($this->get('entries'), function ($e) {
    return isset($e['score']) && $e['score'] < 0.3;
}));
$average = array_sum(array_column($this->get('entries'), 'score')) / max(1, $total);
?>

<div class="alert alert-info">
    <strong><?= $total ?></strong> Captcha-Prüfungen geladen |
    Durchschnittlicher Score: <strong><?= number_format($average, 2) ?></strong> |
    Verdächtige (Score &lt; 0.3): <strong><?= $suspicious ?></strong>
</div>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Datum</th>
            <th>Score</th>
            <th>Aktion</th>
            <th>IP</th>
            <th>Hostname</th>
            <th>Erfolg</th>
            <th>Fehler</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($this->get('entries') as $entry): ?>
            <tr>
                <td>
                    <?php
                    $dt = new \DateTime($entry['timestamp']);
                    echo $dt->format('d.m.Y H:i');
                    ?>
                </td>

                <td>
                    <?php
                        $score = $entry['score'];
                        if (!is_numeric($score)) {
                            echo '<span class="badge bg-secondary">n/a</span>';
                        } elseif ($score < 0.3) {
                            echo '<span class="badge bg-danger">' . $score . '</span>';
                        } elseif ($score < 0.7) {
                            echo '<span class="badge bg-warning text-dark">' . $score . '</span>';
                        } else {
                            echo '<span class="badge bg-success">' . $score . '</span>';
                        }
                    ?>
                </td>
                <td><?= htmlspecialchars($entry['action']) ?></td>
                <td><?= htmlspecialchars($entry['ip']) ?></td>
                <td><?= htmlspecialchars($entry['hostname']) ?></td>
                <td><?= $entry['success'] ? '✅' : '❌' ?></td>
                <td><?= isset($entry['errors']) && is_array($entry['errors']) ? implode(', ', $entry['errors']) : '-' ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
