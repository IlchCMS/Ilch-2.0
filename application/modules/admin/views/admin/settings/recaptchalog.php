<?php
$protokollAktiv = !empty($this->get('captcha_logging'));

$eintraege = $this->get('entries');
if (!is_array($eintraege)) {
    $eintraege = [];
}
?>

<h1><?= $this->getTrans('captchaLogTable') ?></h1>

<?php if (!$protokollAktiv): ?>
    <div class="alert alert-warning">
        <?= $this->getTrans('captchaWarning') ?>
    </div>
<?php else: ?>
    <form method="post"
          action="<?= $this->getUrl(['action' => 'recaptchaLog']) ?>"
          onsubmit="return confirm('Wirklich löschen?');">
        <?= $this->getTokenField() ?>
        <input type="hidden" name="clear_log" value="1">
        <button class="btn btn-danger mb-3" type="submit">
            <i class="fa fa-trash"></i><?= $this->getTrans('captchaDeleteLog') ?>
        </button>
    </form>
<?php endif; ?>

<?php if ($protokollAktiv): ?>
    <?php
    $gesamt = count($eintraege);

    $nurScores = array_values(array_filter(
        array_column($eintraege, 'score'),
        static function ($s) { return is_numeric($s); }
    ));

    $durchschnitt = $nurScores ? array_sum($nurScores) / count($nurScores) : 0.0;

    $verdaechtig = count(array_filter($eintraege, static function ($e) {
        return isset($e['score']) && is_numeric($e['score']) && $e['score'] < 0.3;
    }));
    ?>

    <div class="alert alert-info">
        <strong><?= (int)$gesamt ?></strong> Captcha-Prüfungen geladen |
        Durchschnittlicher Score: <strong><?= number_format((float)$durchschnitt, 2) ?></strong> |
        Verdächtige (Score &lt; 0.3): <strong><?= (int)$verdaechtig ?></strong>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th><?= $this->getTrans('captchaDate') ?></th>
            <th><?= $this->getTrans('captchaScore') ?></th>
            <th><?= $this->getTrans('captchaAction') ?></th>
            <th><?= $this->getTrans('captchaIp') ?></th>
            <th><?= $this->getTrans('captchaHostname') ?></th>
            <th><?= $this->getTrans('captchaSuccess') ?></th>
            <th><?= $this->getTrans('captchaErrors') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($eintraege as $eintrag): ?>
            <tr>
                <td>
                    <?php
                    // Datum sicher formatieren
                    try {
                        $dtRoh = isset($eintrag['timestamp']) ? (string)$eintrag['timestamp'] : 'now';
                        $dt = new \DateTime($dtRoh);
                        echo htmlspecialchars($dt->format('d.m.Y H:i'), ENT_QUOTES, 'UTF-8');
                    } catch (\Exception $ex) {
                        echo '<span class="text-muted">unbekannt</span>';
                    }
                    ?>
                </td>

                <td>
                    <?php
                    // Score farblich kennzeichnen
                    $score = $eintrag['score'] ?? null;
                    if (!is_numeric($score)) {
                        echo '<span class="badge bg-secondary">n/a</span>';
                    } elseif ($score < 0.3) {
                        echo '<span class="badge bg-danger">' . htmlspecialchars((string)$score, ENT_QUOTES, 'UTF-8') . '</span>';
                    } elseif ($score < 0.7) {
                        echo '<span class="badge bg-warning text-dark">' . htmlspecialchars((string)$score, ENT_QUOTES, 'UTF-8') . '</span>';
                    } else {
                        echo '<span class="badge bg-success">' . htmlspecialchars((string)$score, ENT_QUOTES, 'UTF-8') . '</span>';
                    }
                    ?>
                </td>

                <td><?= htmlspecialchars((string)($eintrag['action'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string)($eintrag['ip'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string)($eintrag['hostname'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= !empty($eintrag['success']) ? '✅' : '❌' ?></td>
                <td>
                    <?php
                    $fehler = $eintrag['errors'] ?? null;
                    if (is_array($fehler) && $fehler) {
                        // Fehlerliste sicher ausgeben
                        echo htmlspecialchars(implode(', ', array_map('strval', $fehler)), ENT_QUOTES, 'UTF-8');
                    } else {
                        echo '-';
                    }
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php if ($gesamt === 0): ?>
        <div class="alert alert-secondary">Keine Einträge vorhanden.</div>
    <?php endif; ?>

<?php else: ?>
    <div class="alert alert-secondary mt-3">
        <?= $this->getTrans('captchaNoLogEntries') ?>
    </div>
<?php endif; ?>
