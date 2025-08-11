<?php
// Stripe test mode configuration
// Set your Stripe test keys here or via environment variables
// export STRIPE_SECRET_KEY=sk_test_xxx
// export STRIPE_PUBLISHABLE_KEY=pk_test_xxx

function envOrDefault(string $key, string $default = ''): string {
    $value = getenv($key);
    return $value !== false && $value !== '' ? $value : $default;
}

define('STRIPE_SECRET_KEY', envOrDefault('STRIPE_SECRET_KEY', 'sk_test_your_secret_key'));
define('STRIPE_PUBLISHABLE_KEY', envOrDefault('STRIPE_PUBLISHABLE_KEY', 'pk_test_your_publishable_key'));

// Application currency
define('APP_CURRENCY', 'eur');
?> 