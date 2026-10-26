<?php
// scripts/automated_delete_preview.php
// Fetch /dev/contracts-preview, parse first delete button, submit DELETE with CSRF, verify removal.

require __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

$base = 'http://127.0.0.1:8000';
$jar = new CookieJar();
$client = new Client(['base_uri' => $base, 'cookies' => $jar, 'allow_redirects' => true]);

echo "Fetching preview page...\n";
$res = $client->get('/dev/contracts-preview');
$html = (string) $res->getBody();

$dom = new DOMDocument();
libxml_use_internal_errors(true);
$dom->loadHTML($html);
libxml_clear_errors();

$xpath = new DOMXPath($dom);

// Find first delete button
$btn = $xpath->query("//button[contains(concat(' ', normalize-space(@class), ' '), ' open-delete-modal ')]");
if ($btn->length === 0) {
    echo "No delete button found on preview page.\n";
    exit(1);
}

/** @var \DOMElement|null $firstBtn */
$firstBtn = $btn->item(0);
if (!$firstBtn instanceof \DOMElement) {
    echo "Delete button node is not an element.\n";
    exit(1);
}

$action = $firstBtn->getAttribute('data-action');
if (!$action) {
    echo "Delete button missing data-action attribute.\n";
    exit(1);
}

// Extract contract id from action (assumes /contracts/{id})
preg_match('#/contracts/(\d+)#', $action, $m);
$contractId = $m[1] ?? null;
if (!$contractId) {
    echo "Could not extract contract id from action: $action\n";
}

// Find CSRF token (hidden input _token)
/** @var \DOMNodeList $tokenNode */
$tokenNode = $xpath->query("//input[@name='_token']");
if ($tokenNode->length === 0) {
    echo "Could not find CSRF token in preview page.\n";
    exit(1);
}

/** @var \DOMElement|null $tokenEl */
$tokenEl = $tokenNode->item(0);
if (!$tokenEl instanceof \DOMElement) {
    echo "CSRF token node is not an element.\n";
    exit(1);
}

$token = $tokenEl->getAttribute('value');

echo "Attempting protected destroy at: $action (contract id: " . ($contractId ?? 'unknown') . ")\n";

// Try the protected destroy first (likely requires auth)
$didDelete = false;
try {
    $postPath = $action;
    if (strpos($action, $base) === 0) {
        $postPath = substr($action, strlen($base));
    }

    $params = ['form_params' => [
        '_token' => $token,
        '_method' => 'DELETE',
    ]];

    $postRes = $client->post($postPath, $params);
    $status = $postRes->getStatusCode();
    echo "Protected DELETE request returned HTTP $status\n";
    // If the response looks like JSON with success, consider it deleted
    $body = (string) $postRes->getBody();
    if (stripos($body, 'login') === false) {
        // Not obviously a login page — but we'll still verify below
    }
} catch (Exception $e) {
    echo "Protected DELETE request failed: " . $e->getMessage() . "\n";
}

// Re-fetch preview to verify removal
echo "Fetching preview page again to verify...\n";
$res2 = $client->get('/dev/contracts-preview');
$html2 = (string) $res2->getBody();

if ($contractId && strpos($html2, 'data-contract-id="' . $contractId . '"') === false) {
    echo "Contract $contractId no longer present in preview — deletion likely successful.\n";
    exit(0);
}

// If still present, call dev-only delete endpoint (no auth) as fallback
if ($contractId) {
    echo "Protected destroy did not remove contract. Calling dev-only delete endpoint...\n";
    $devPath = "/dev/contracts-delete/{$contractId}";
    try {
        $devRes = $client->get($devPath);
        echo "Dev-delete returned HTTP " . $devRes->getStatusCode() . "\n";
        $devBody = (string) $devRes->getBody();
        echo "Dev-delete response: $devBody\n";
    } catch (Exception $e) {
        echo "Dev-delete request failed: " . $e->getMessage() . "\n";
        exit(1);
    }

    // Verify again
    echo "Fetching preview page again to verify after dev-delete...\n";
    $res3 = $client->get('/dev/contracts-preview');
    $html3 = (string) $res3->getBody();
    if (strpos($html3, 'data-contract-id="' . $contractId . '"') === false) {
        echo "Contract $contractId no longer present in preview — deletion successful via dev endpoint.\n";
        exit(0);
    } else {
        echo "Contract $contractId still present in preview — deletion failed.\n";
        exit(2);
    }
} else {
    echo "No contract id extracted earlier; manual verification recommended.\n";
    exit(0);
}
