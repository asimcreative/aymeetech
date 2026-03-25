<?php
/**
 * Fix: Remove Facebook Messenger contact from Social Chat (qlwapp) plugin
 * Run once, then DELETE this file
 */

define('WP_USE_THEMES', false);
require_once __DIR__ . '/wp-load.php';

echo '<h2>Fix: Social Chat Contacts</h2>';

// Get current contacts
$contacts = get_option('qlwapp_contacts');
echo '<h3>Current contacts in DB:</h3>';
echo '<pre>' . esc_html(print_r($contacts, true)) . '</pre>';

if (empty($contacts)) {
    echo '<p>No contacts found in option "qlwapp_contacts".</p>';

    // Try the ORM table format
    global $wpdb;
    $rows = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}options WHERE option_name LIKE '%qlwapp%'");
    echo '<h3>All qlwapp options:</h3>';
    echo '<pre>';
    foreach ($rows as $row) {
        echo esc_html($row->option_name . ': ' . substr($row->option_value, 0, 200)) . "\n";
    }
    echo '</pre>';
} else {
    // Filter out Messenger/Facebook contacts
    $original_count = is_array($contacts) ? count($contacts) : 0;

    if (is_array($contacts)) {
        $fixed = array_filter($contacts, function($contact) {
            $type = isset($contact['type']) ? strtolower($contact['type']) : '';
            $is_facebook = in_array($type, ['messenger', 'facebook', 'fb', 'facebook_messenger']);
            if ($is_facebook) {
                echo '<p style="color:orange">Removing contact type: ' . esc_html($type) . '</p>';
            }
            return !$is_facebook;
        });

        $fixed = array_values($fixed);

        if (count($fixed) < $original_count) {
            update_option('qlwapp_contacts', $fixed);
            echo '<p style="color:green"><strong>Done! Removed ' . ($original_count - count($fixed)) . ' Facebook/Messenger contact(s).</strong></p>';
            echo '<p>Remaining contacts: ' . count($fixed) . '</p>';
        } else {
            echo '<p>No Facebook/Messenger contacts found to remove.</p>';
            echo '<p>All contact types: ';
            foreach ($contacts as $c) {
                echo esc_html(isset($c['type']) ? $c['type'] : 'unknown') . ', ';
            }
            echo '</p>';
        }
    }
}

// Also check for any Facebook SDK options
global $wpdb;
$fb_options = $wpdb->get_results("SELECT option_name, option_value FROM {$wpdb->prefix}options WHERE option_value LIKE '%connect.facebook.net%' OR option_value LIKE '%facebook-jssdk%' OR option_value LIKE '%fbAsyncInit%'");
if ($fb_options) {
    echo '<h3>Found Facebook SDK in these options:</h3>';
    foreach ($fb_options as $opt) {
        echo '<p><strong>' . esc_html($opt->option_name) . '</strong>: ' . esc_html(substr($opt->option_value, 0, 300)) . '</p>';
    }
} else {
    echo '<p>No Facebook SDK code found in wp_options.</p>';
}

echo '<hr><p style="color:red"><strong>DELETE this file after running!</strong></p>';
