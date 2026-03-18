<?php
/**
 * GitHub Auto-Deploy Script for dev.aymeetech.com
 * Pure PHP - NO exec(), NO git, NO shell needed!
 * Works on ANY shared hosting.
 *
 * === SETUP ===
 * 1. Upload deploy-webhook.php + deploy-token.txt to document root
 * 2. deploy-token.txt should contain ONLY your secret token
 * 3. Visit: deploy-webhook.php?token=YOUR_TOKEN&action=deploy
 *
 * === USAGE ===
 * Deploy:     ?token=YOUR_TOKEN&action=deploy
 * Diagnose:   ?token=YOUR_TOKEN&action=diagnose
 * Log:        ?token=YOUR_TOKEN&action=log
 */

// === CONFIGURATION ===
$config = [
    'branch'       => 'main',
    'github_user'  => 'asimcreative',
    'github_repo'  => 'aymeetech',
    'deploy_dir'   => '/home2/aymeetech/dev.aymeetech.com',
    'log_file'     => '/home2/aymeetech/deploy.log',
    'token_file'   => __DIR__ . '/deploy-token.txt',
    // Files/dirs to NEVER overwrite during deploy
    'preserve'     => [
        'wp-config.php',
        'wp-content/uploads',
        'deploy-webhook.php',
        'deploy-token.txt',
        '.htaccess',
        'deploy.log',
    ],
];

// === HELPER FUNCTIONS ===
function deploy_log($msg) {
    global $config;
    $ts = date('Y-m-d H:i:s');
    @file_put_contents($config['log_file'], "[$ts] $msg\n", FILE_APPEND);
}

function html_page($title, $body, $ok = true) {
    $c = $ok ? '#27ae60' : '#e74c3c';
    $i = $ok ? '&#10004;' : '&#10008;';
    echo "<!DOCTYPE html><html><head><title>$title</title><meta charset='utf-8'>
    <style>
    body{font-family:'Courier New',monospace;max-width:900px;margin:40px auto;padding:20px;background:#1a1a2e;color:#eee}
    .box{background:#16213e;border-left:4px solid $c;padding:15px 20px;margin:10px 0;border-radius:4px}
    .box.ok{border-left-color:#27ae60} .box.err{border-left-color:#e74c3c} .box.info{border-left-color:#3498db}
    pre{background:#0f3460;padding:15px;overflow-x:auto;border-radius:4px;font-size:13px;white-space:pre-wrap}
    h1{color:$c} h2{color:#4fc3f7;margin-top:25px} a{color:#4fc3f7}
    .btn{display:inline-block;padding:10px 20px;background:#27ae60;color:#fff;text-decoration:none;border-radius:4px;margin:5px}
    .btn:hover{background:#2ecc71} .btn.danger{background:#e74c3c} .btn.danger:hover{background:#c0392b}
    </style></head><body><h1>$i $title</h1>$body</body></html>";
    exit;
}

function step_html($name, $detail, $ok = true) {
    $c = $ok ? 'ok' : 'err';
    $i = $ok ? '&#10004;' : '&#10008;';
    return "<div class='box $c'><strong>$i $name</strong><pre>$detail</pre></div>";
}

// === TOKEN AUTH ===
$valid_token = '';
if (file_exists($config['token_file'])) {
    $valid_token = trim(file_get_contents($config['token_file']));
}
if (empty($valid_token)) {
    html_page('Token Missing', '<p>Create <strong>deploy-token.txt</strong> in this directory with your secret token.</p>', false);
}

// Determine request type
$is_webhook = ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_HUB_SIGNATURE_256']));
$is_manual = ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['token']));

if (!$is_webhook && !$is_manual) {
    html_page('Deploy Script', '<p>Usage: <code>?token=YOUR_TOKEN&action=deploy|diagnose|log</code></p>');
}

// Authenticate
if ($is_webhook) {
    $payload = file_get_contents('php://input');
    $sig = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
    $expected = 'sha256=' . hash_hmac('sha256', $payload, $valid_token);
    if (!hash_equals($expected, $sig)) {
        http_response_code(403);
        die(json_encode(['error' => 'Invalid signature']));
    }
    $data = json_decode($payload, true);
    if (($data['ref'] ?? '') !== "refs/heads/{$config['branch']}") {
        die(json_encode(['status' => 'ignored', 'message' => 'Not target branch']));
    }
    $action = 'deploy';
} else {
    if (!hash_equals($valid_token, $_GET['token'] ?? '')) {
        html_page('Access Denied', 'Invalid token.', false);
    }
    $action = $_GET['action'] ?? 'deploy';
}

// === ACTIONS ===
switch ($action) {

    case 'diagnose':
        $html = '<h2>Server Diagnostics</h2>';
        $html .= "<div class='box info'><strong>PHP Version:</strong> " . phpversion() . "</div>";
        $html .= "<div class='box info'><strong>Server:</strong> " . php_sapi_name() . "</div>";
        $html .= "<div class='box info'><strong>Deploy Dir:</strong> " . $config['deploy_dir'] . "</div>";
        $html .= "<div class='box info'><strong>Dir Exists:</strong> " . (is_dir($config['deploy_dir']) ? 'YES' : 'NO') . "</div>";
        $html .= "<div class='box info'><strong>Dir Writable:</strong> " . (is_writable($config['deploy_dir']) ? 'YES' : 'NO') . "</div>";

        // Check disabled functions
        $disabled = explode(',', ini_get('disable_functions'));
        $disabled = array_map('trim', $disabled);
        $shell_funcs = ['exec', 'shell_exec', 'system', 'passthru', 'proc_open', 'popen'];
        foreach ($shell_funcs as $fn) {
            $avail = !in_array($fn, $disabled) && function_exists($fn);
            $html .= step_html("Function: $fn", $avail ? 'Available' : 'DISABLED', $avail);
        }

        // Check cURL
        $html .= step_html('cURL', function_exists('curl_init') ? 'Available' : 'Not available', function_exists('curl_init'));

        // Check ZipArchive
        $html .= step_html('ZipArchive', class_exists('ZipArchive') ? 'Available' : 'Not available', class_exists('ZipArchive'));

        // Check allow_url_fopen
        $html .= step_html('allow_url_fopen', ini_get('allow_url_fopen') ? 'Enabled' : 'Disabled', (bool)ini_get('allow_url_fopen'));

        // Disk space
        $free = @disk_free_space($config['deploy_dir']);
        if ($free !== false) {
            $html .= "<div class='box info'><strong>Free Disk Space:</strong> " . round($free / 1024 / 1024) . " MB</div>";
        }

        $html .= "<p><a class='btn' href='?token={$_GET['token']}&action=deploy'>Deploy Now</a></p>";
        html_page('Server Diagnostics', $html);
        break;

    case 'deploy':
        deploy_log("=== DEPLOY STARTED ===");
        $html = '';
        $all_ok = true;
        $start_time = microtime(true);

        // Step 1: Download zip from GitHub
        $zip_url = "https://github.com/{$config['github_user']}/{$config['github_repo']}/archive/refs/heads/{$config['branch']}.zip";
        $tmp_zip = sys_get_temp_dir() . '/aymeetech-deploy-' . time() . '.zip';
        $tmp_dir = sys_get_temp_dir() . '/aymeetech-deploy-' . time();

        deploy_log("Downloading: $zip_url");
        $html .= step_html('Download URL', $zip_url);

        // Try cURL first, fall back to file_get_contents
        $downloaded = false;
        if (function_exists('curl_init')) {
            $ch = curl_init($zip_url);
            $fp = fopen($tmp_zip, 'w');
            curl_setopt_array($ch, [
                CURLOPT_FILE => $fp,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 5,
                CURLOPT_TIMEOUT => 120,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_USERAGENT => 'AymeeTech-Deploy/1.0',
            ]);
            $result = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curl_err = curl_error($ch);
            curl_close($ch);
            fclose($fp);

            if ($result && $http_code == 200 && filesize($tmp_zip) > 1000) {
                $downloaded = true;
                $size_mb = round(filesize($tmp_zip) / 1024 / 1024, 2);
                $html .= step_html('Download (cURL)', "OK - {$size_mb} MB downloaded (HTTP $http_code)");
                deploy_log("Downloaded via cURL: {$size_mb} MB");
            } else {
                $html .= step_html('Download (cURL)', "Failed: HTTP $http_code - $curl_err", false);
                deploy_log("cURL download failed: HTTP $http_code - $curl_err");
                @unlink($tmp_zip);
            }
        }

        if (!$downloaded && ini_get('allow_url_fopen')) {
            $ctx = stream_context_create([
                'http' => [
                    'follow_location' => true,
                    'timeout' => 120,
                    'user_agent' => 'AymeeTech-Deploy/1.0',
                ],
                'ssl' => ['verify_peer' => false],
            ]);
            $data = @file_get_contents($zip_url, false, $ctx);
            if ($data && strlen($data) > 1000) {
                file_put_contents($tmp_zip, $data);
                $downloaded = true;
                $size_mb = round(strlen($data) / 1024 / 1024, 2);
                $html .= step_html('Download (fopen)', "OK - {$size_mb} MB");
                deploy_log("Downloaded via file_get_contents: {$size_mb} MB");
            } else {
                $html .= step_html('Download (fopen)', 'Failed', false);
                deploy_log("file_get_contents download failed");
            }
        }

        if (!$downloaded) {
            $html .= step_html('Download', 'All methods failed! Check cURL and allow_url_fopen.', false);
            deploy_log("DEPLOY FAILED: Could not download zip");
            html_page('Deploy FAILED', $html, false);
        }

        // Step 2: Extract zip
        if (!class_exists('ZipArchive')) {
            @unlink($tmp_zip);
            $html .= step_html('Extract', 'ZipArchive not available on server!', false);
            deploy_log("DEPLOY FAILED: ZipArchive not available");
            html_page('Deploy FAILED', $html, false);
        }

        $zip = new ZipArchive();
        $res = $zip->open($tmp_zip);
        if ($res !== true) {
            @unlink($tmp_zip);
            $html .= step_html('Extract', "Failed to open zip (code: $res)", false);
            deploy_log("DEPLOY FAILED: Cannot open zip - code $res");
            html_page('Deploy FAILED', $html, false);
        }

        @mkdir($tmp_dir, 0755, true);
        $zip->extractTo($tmp_dir);
        $zip->close();
        @unlink($tmp_zip);

        // Find the extracted folder (GitHub adds repo-branch/ prefix)
        $extracted_dirs = @scandir($tmp_dir);
        $source_dir = $tmp_dir;
        if ($extracted_dirs) {
            foreach ($extracted_dirs as $d) {
                if ($d !== '.' && $d !== '..' && is_dir("$tmp_dir/$d")) {
                    $source_dir = "$tmp_dir/$d";
                    break;
                }
            }
        }

        $file_count = count_files($source_dir);
        $html .= step_html('Extract', "OK - $file_count files extracted to temp directory");
        deploy_log("Extracted $file_count files");

        // Step 3: Sync files to deploy directory
        $stats = ['copied' => 0, 'skipped' => 0, 'dirs' => 0, 'errors' => 0];
        sync_directory($source_dir, $config['deploy_dir'], $config['preserve'], $stats, $source_dir);

        $html .= step_html('Sync Files',
            "Copied: {$stats['copied']} files\nDirs created: {$stats['dirs']}\nPreserved: {$stats['skipped']} items\nErrors: {$stats['errors']}",
            $stats['errors'] === 0
        );
        deploy_log("Sync: copied={$stats['copied']}, dirs={$stats['dirs']}, preserved={$stats['skipped']}, errors={$stats['errors']}");

        if ($stats['errors'] > 0) $all_ok = false;

        // Step 4: Cleanup temp files
        delete_directory($tmp_dir);
        $html .= step_html('Cleanup', 'Temp files removed');

        // Summary
        $elapsed = round(microtime(true) - $start_time, 1);
        $status = $all_ok ? 'SUCCESS' : 'PARTIAL (some errors)';
        $html .= "<div class='box " . ($all_ok ? 'ok' : 'err') . "'><strong>Deploy $status</strong> in {$elapsed}s</div>";
        $html .= "<p><a class='btn' href='?token={$_GET['token']}&action=deploy'>Re-deploy</a> ";
        $html .= "<a class='btn' href='?token={$_GET['token']}&action=log'>View Log</a></p>";

        deploy_log("=== DEPLOY $status in {$elapsed}s ===");

        if ($is_webhook) {
            header('Content-Type: application/json');
            echo json_encode(['status' => $status, 'files' => $stats['copied'], 'time' => $elapsed]);
        } else {
            html_page("Deploy $status", $html, $all_ok);
        }
        break;

    case 'log':
        $log = file_exists($config['log_file']) ? file_get_contents($config['log_file']) : 'No log found.';
        $lines = explode("\n", $log);
        $last = implode("\n", array_slice($lines, -60));
        $html = "<pre>" . htmlspecialchars($last) . "</pre>";
        $html .= "<p><a class='btn' href='?token={$_GET['token']}&action=deploy'>Deploy</a> ";
        $html .= "<a class='btn' href='?token={$_GET['token']}&action=diagnose'>Diagnose</a></p>";
        html_page('Deploy Log', $html);
        break;

    default:
        html_page('Unknown Action', '<p>Valid: <code>deploy</code>, <code>diagnose</code>, <code>log</code></p>', false);
}

// === FILE SYNC FUNCTIONS ===
function sync_directory($src, $dst, $preserve, &$stats, $base_src) {
    $items = @scandir($src);
    if (!$items) return;

    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;

        $src_path = "$src/$item";
        $rel_path = ltrim(str_replace($base_src, '', $src_path), '/');
        $dst_path = "$dst/$rel_path";

        // Check if this path should be preserved
        $skip = false;
        foreach ($preserve as $p) {
            if ($rel_path === $p || strpos($rel_path, $p . '/') === 0) {
                $skip = true;
                break;
            }
        }

        if ($skip) {
            $stats['skipped']++;
            continue;
        }

        if (is_dir($src_path)) {
            if (!is_dir($dst_path)) {
                if (@mkdir($dst_path, 0755, true)) {
                    $stats['dirs']++;
                } else {
                    $stats['errors']++;
                }
            }
            sync_directory($src_path, $dst, $preserve, $stats, $base_src);
        } else {
            $dir = dirname($dst_path);
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
            if (@copy($src_path, $dst_path)) {
                $stats['copied']++;
            } else {
                $stats['errors']++;
            }
        }
    }
}

function count_files($dir) {
    $count = 0;
    $items = @scandir($dir);
    if (!$items) return 0;
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        $path = "$dir/$item";
        if (is_dir($path)) {
            $count += count_files($path);
        } else {
            $count++;
        }
    }
    return $count;
}

function delete_directory($dir) {
    if (!is_dir($dir)) return;
    $items = @scandir($dir);
    if (!$items) return;
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        $path = "$dir/$item";
        if (is_dir($path)) {
            delete_directory($path);
        } else {
            @unlink($path);
        }
    }
    @rmdir($dir);
}
