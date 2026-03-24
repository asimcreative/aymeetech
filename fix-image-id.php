<?php
$db = new mysqli('67.217.48.162', 'aymeetech_website', 'website!@#$%54321', 'aymeetech_website');
if ($db->connect_error) die("DB Error: " . $db->connect_error);
$db->set_charset('utf8mb4');

echo "<pre>\n";

// Find the attachment we just created
$r = $db->query("SELECT ID, guid FROM wp_posts WHERE post_type='attachment' AND post_name='aymeetech-blog-featured' ORDER BY ID DESC LIMIT 1");
if ($r->num_rows === 0) {
    echo "Attachment not found - inserting fresh\n";

    $img_url = 'https://aymeetech.com/wp-content/uploads/2025/03/aymeetech-blog-featured.png';
    $date    = '2025-03-10 09:00:00';
    $db->query("INSERT INTO wp_posts
        (post_author, post_date, post_date_gmt, post_content, post_title, post_status,
         post_name, post_type, post_mime_type, post_modified, post_modified_gmt,
         to_ping, pinged, post_content_filtered, guid)
        VALUES
        (1, '$date', '$date', '', 'AymeeTech Blog Featured Image', 'inherit',
         'aymeetech-blog-featured', 'attachment', 'image/png', NOW(), NOW(),
         '', '', '', '$img_url')");
    $attach_id = $db->insert_id;
    echo "Inserted with ID: $attach_id\n";

    $meta = [
        ['_wp_attached_file',   '2025/03/aymeetech-blog-featured.png'],
        ['_wp_attachment_metadata', serialize([
            'width'  => 1200,
            'height' => 628,
            'file'   => '2025/03/aymeetech-blog-featured.png',
            'sizes'  => [],
            'image_meta' => [],
        ])],
    ];
    foreach ($meta as $m) {
        $k = $db->real_escape_string($m[0]);
        $v = $db->real_escape_string($m[1]);
        $db->query("INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES ($attach_id, '$k', '$v')");
    }
} else {
    $att = $r->fetch_assoc();
    $attach_id = $att['ID'];
    echo "Found attachment ID: $attach_id\n";
}

// Clean up bad 0 entries and set correct ID
$db->query("DELETE FROM wp_postmeta WHERE meta_key='_thumbnail_id' AND meta_value='0'");
echo "Cleared bad entries\n";

// Set correct featured image on all published posts
$result = $db->query("SELECT ID FROM wp_posts WHERE post_type='post' AND post_status='publish'");
$count = 0;
while ($row = $result->fetch_assoc()) {
    // Delete existing thumbnail first
    $db->query("DELETE FROM wp_postmeta WHERE post_id={$row['ID']} AND meta_key='_thumbnail_id'");
    // Set correct one
    $db->query("INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES ({$row['ID']}, '_thumbnail_id', '$attach_id')");
    $count++;
}

echo "Featured image ID $attach_id set on $count posts\n";

// Verify
$check = $db->query("SELECT meta_value FROM wp_postmeta WHERE meta_key='_thumbnail_id' LIMIT 1");
$val = $check->fetch_assoc();
echo "Verification - first post thumbnail ID: " . $val['meta_value'] . "\n";

echo "=== DONE ===\n";
echo "</pre>";
$db->close();
