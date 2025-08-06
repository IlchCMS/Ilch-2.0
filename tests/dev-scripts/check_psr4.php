<?php
declare(strict_types=1);

$baseNamespace = 'Ilch';
$baseDir = __DIR__ . '/application/libraries/Ilch';
$ignoreFiles = [
    'Functions.php',
];

$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($baseDir));

echo "🔍 Starte PSR-4-Prüfung für $baseDir\n\n";

foreach ($rii as $file) {
    if (!$file->isFile() || $file->getExtension() !== 'php') {
        continue;
    }

    $filePath = $file->getPathname();
    $relativePath = str_replace($baseDir . DIRECTORY_SEPARATOR, '', $filePath);

    if (in_array($relativePath, $ignoreFiles)) {
        continue;
    }

    $expectedClass = $baseNamespace . '\\' . str_replace(['/', '.php'], ['\\', ''], $relativePath);

    $content = file_get_contents($filePath);

    if (preg_match('/namespace\s+([^;]+);/', $content, $matches)) {
        $actualNamespace = trim($matches[1]);
        $expectedNamespace = dirname(str_replace(['/', '\\'], '/', $expectedClass));
        $expectedNamespace = str_replace('/', '\\', $expectedNamespace);

        if ($actualNamespace !== $expectedNamespace) {
            echo "⚠️  Namespace stimmt nicht in: $relativePath\n";
            echo "    Erwartet:   namespace $expectedNamespace;\n";
            echo "    Gefunden:   namespace $actualNamespace;\n\n";
        }
    } else {
        echo "❌ Kein Namespace gefunden in: $relativePath\n\n";
    }
}
echo "✅ Fertig.\n";
