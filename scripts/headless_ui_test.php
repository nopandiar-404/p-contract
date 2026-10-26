<?php
// Headless UI test: bootstrap Laravel, authenticate, GET /contracts, then DELETE first contract via kernel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Contract;

// Ensure we have a user to authenticate as
$user = User::first();
if (!$user) {
    // Try to create a simple user (bypass passwords) if factory exists
    if (class_exists('Database\Factories\UserFactory')) {
        $user = User::factory()->create(['email' => 'dev+tester@example.test']);
    } else {
        echo "No user found. Please create a user to run this test.\n";
        exit(1);
    }
}

// Log in the user for the current process
Auth::loginUsingId($user->id);

// 1) Fetch contracts page via kernel
$req = Request::create('/contracts', 'GET');
$resp = $kernel->handle($req);
$html = $resp->getContent();

$hasModal = strpos($html, 'id="deleteContractModal"') !== false ? 'yes' : 'no';
$hasButton = strpos($html, 'open-delete-modal') !== false ? 'yes' : 'no';
echo "Contracts page - modal present: $hasModal, delete button present: $hasButton\n";

// 2) Attempt to delete first contract via kernel (simulate form submit)
$contract = Contract::first();
if (!$contract) {
    echo "No contract found to delete.\n";
    exit(1);
}

$id = $contract->id;
// Ensure CSRF token exists for the session
session()->regenerateToken();
$token = csrf_token();

$deleteUrl = route('contracts.destroy', $contract);
$delReq = Request::create($deleteUrl, 'POST', [
    '_method' => 'DELETE',
    '_token' => $token,
]);

$delResp = $kernel->handle($delReq);

// After handling, check if contract still exists
$still = Contract::find($id) ? 'yes' : 'no';
echo "Deleted contract {$id}? " . ($still === 'no' ? 'yes' : 'no') . "\n";

// Clean up: logout the test user
Auth::logout();

exit(0);
