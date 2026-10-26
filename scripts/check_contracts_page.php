<?php
// Fetch the contracts index page and check for modal and delete-button markers
$url = 'http://127.0.0.1:8000/contracts';
echo "Checking: $url\n";
$opts = [
    'http' => [
        'method' => 'GET',
        'timeout' => 5,
    ],
];
$context = stream_context_create($opts);
$html = @file_get_contents($url, false, $context);
if ($html === false) {
    echo "ERROR: Unable to fetch page. Is the dev server running?\n";
    exit(1);
}
$hasModal = strpos($html, 'id="deleteContractModal"') !== false ? 'yes' : 'no';
$hasButton = strpos($html, 'open-delete-modal') !== false ? 'yes' : 'no';
echo "Found modal: $hasModal\n";
echo "Found delete button marker: $hasButton\n";
exit(0);
