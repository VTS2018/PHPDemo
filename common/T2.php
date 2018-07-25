<?php require ('VTSCommon.php');?>

<?php
// 使用方法
$post_data = array(
    'username' => 'stclai r2201',
    'password' => 'ha nd+a&n'
);

/*
 * $html = Utils::vts_request_by_get_contents('http://php-note.com/', $post_data);
 * echo $html;
 * var_dump($obj);
 */

/*
 * echo Utils::vts_create_post_data($post_data);
 * $post_string = Utils::vts_create_post_data($post_data);
 * $html = Utils::vts_request_by_socket('php-note.com', '/index.php', $post_string);
 * echo $html;
 */

/*
 * $post_string = Utils::vts_create_post_data($post_data);
 * $html = Utils::vts_request_by_curl('http://php-note.com/', $post_string);
 * echo $html;
 */

echo "\n";