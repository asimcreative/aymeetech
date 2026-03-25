<?php
define('WP_USE_THEMES', false);
define('ABSPATH', __DIR__ . '/');
require_once __DIR__ . '/wp-config.php';
echo json_encode([
    'host' => DB_HOST,
    'name' => DB_NAME,
    'user' => DB_USER,
    'pass' => DB_PASSWORD,
]);
