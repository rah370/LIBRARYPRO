<?php
$dir = __DIR__ . '/assets/books';
$files = array_values(array_filter(scandir($dir), function($f){
    return preg_match('/\.(jpe?g|png|webp)$/i', $f);
}));
// dedupe and randomize
$files = array_unique($files);
shuffle($files);
$files = array_slice($files, 0, 8);
$urls = array_map(function($f){ return 'frontend/assets/books/' . rawurlencode($f); }, $files);
header('Content-Type: application/json');
echo json_encode(array_values($urls));
