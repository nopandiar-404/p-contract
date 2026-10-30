<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Gmail OAuth Callback</title>
</head>
<body>
    <h1>Gmail OAuth Callback</h1>
    <p>Tokens saved to: <strong><?php echo e($path); ?></strong></p>
    <h2>Response</h2>
    <pre><?php echo e(json_encode($data, JSON_PRETTY_PRINT)); ?></pre>
    <p>If a <strong>refresh_token</strong> is present, copy it to your <code>.env</code> as <code>GMAIL_REFRESH_TOKEN</code> or keep the file at the path above and set <code>GMAIL_CREDENTIALS_PATH</code>.</p>
</body>
</html>
<?php /**PATH C:\xaampp8.2.12_NEW\htdocs\p-contract\resources\views\gmail\callback.blade.php ENDPATH**/ ?>