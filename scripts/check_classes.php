<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

$classes = [
    '\\Maatwebsite\\Excel\\Facades\\Excel',
    '\\Maatwebsite\\Excel\\Excel',
    '\\Barryvdh\\DomPDF\\Facade\\Pdf',
];

foreach ($classes as $c) {
    echo $c . ': ' . (class_exists($c) ? 'YES' : 'NO') . PHP_EOL;
}
