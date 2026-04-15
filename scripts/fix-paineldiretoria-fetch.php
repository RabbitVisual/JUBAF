<?php

$dir = __DIR__.'/../Modules/Blog/resources/views/paineldiretoria';
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

foreach ($it as $f) {
    if (! $f->isFile() || ! str_ends_with($f->getFilename(), '.blade.php')) {
        continue;
    }
    $p = $f->getPathname();
    $c = file_get_contents($p);
    $n = str_replace("`/admin/blog/", "`/diretoria/blog/", $c);
    $n = str_replace("'/admin/blog/", "'/diretoria/blog/", $n);
    if ($n !== $c) {
        file_put_contents($p, $n);
        echo "Updated: $p\n";
    }
}
