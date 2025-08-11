<?php
// Central bootstrap to be included by all entry points
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/functions.php';
// Load payment config if available (not all pages need Stripe)
$paymentConfigPath = __DIR__ . '/../config/payment.php';
if (file_exists($paymentConfigPath)) {
    require_once $paymentConfigPath;
}
?> 