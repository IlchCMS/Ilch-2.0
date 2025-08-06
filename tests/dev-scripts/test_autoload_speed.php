<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

$start = microtime(true);

// Beispiel: viele Klassen laden (z. B. 100 Klassen)
$classes = [
    Ilch\Request::class,
    Ilch\Layout\Base::class,
    Ilch\Database\Mysql\QueryBuilder::class,
    Ilch\Upload::class,
    Ilch\Validation\Validators\Timezone::class,
    // ... weitere Klassen, die existieren
];

foreach ($classes as $class) {
    if (!class_exists($class)) {
        echo "❌ Klasse nicht gefunden: $class\n";
    }
}

$end = microtime(true);
echo "⏱️ Autoload-Zeit: " . round(($end - $start) * 1000, 2) . " ms\n";
