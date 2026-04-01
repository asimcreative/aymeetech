<?php
if (empty($_GET['token']) || $_GET['token'] !== 'aymee-purge-2026') {
    http_response_code(403); die('Forbidden');
}
require_once __DIR__ . '/wp-load.php';
do_action('litespeed_purge_all');
if (class_exists('LiteSpeed\Purge')) \LiteSpeed\Purge::purge_all();
echo 'Cache purged OK';
