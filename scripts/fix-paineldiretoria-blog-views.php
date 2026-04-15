<?php

$dir = __DIR__.'/../Modules/Blog/resources/views/paineldiretoria';
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

foreach ($it as $f) {
    if (! $f->isFile() || ! str_ends_with($f->getFilename(), '.blade.php')) {
        continue;
    }
    $p = $f->getPathname();
    $c = file_get_contents($p);
    $n = str_replace("@extends('admin::layouts.admin')", "@extends('paineldiretoria::components.layouts.app')", $c);
    $n = str_replace("route('admin.dashboard')", "route('diretoria.dashboard')", $n);
    if ($n !== $c) {
        file_put_contents($p, $n);
        echo "Updated: $p\n";
    }
}
