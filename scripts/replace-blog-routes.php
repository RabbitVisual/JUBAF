<?php

$dir = __DIR__.'/../Modules/Blog/resources/views';
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

foreach ($it as $f) {
    if (! $f->isFile() || ! str_ends_with($f->getFilename(), '.blade.php')) {
        continue;
    }
    $p = $f->getPathname();
    $c = file_get_contents($p);
    $n = str_replace("route('admin.blog.", "blog_admin_route('", $c);
    if ($n !== $c) {
        file_put_contents($p, $n);
        echo "Updated: $p\n";
    }
}
