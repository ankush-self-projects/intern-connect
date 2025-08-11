<?php


function envOrDefault(string $key, string $default = ''): string {
    $value = getenv($key);
    return $value !== false && $value !== '' ? $value : $default;
}

define('STRIPE_SECRET_KEY', envOrDefault('STRIPE_SECRET_KEY', 'sk_test_51RutQxR9zpoadhqvyXiUfVLVSoQPpDZRuoNpsEFacyXna5CAXvN7hDgxQe2caKVG8yOmI7yIi1VIHG1baJQBeeKJ00fLvyFrwG'));
define('STRIPE_PUBLISHABLE_KEY', envOrDefault('STRIPE_PUBLISHABLE_KEY', 'pk_test_51RutQxR9zpoadhqvHCyWgNx0iVyXiZw4AKIpywyUJR9NKTbzTEIJdGHDfTNVnIqigP2K5dSB57yk1cK9xzJxuRwr00Z2baBsT9'));


define('APP_CURRENCY', 'eur');
?> 