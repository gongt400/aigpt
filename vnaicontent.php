<?php

/**
 * Plugin Name: VnAIContent
 * Plugin URI: https://vngpt.pro
 * Description: Tạo bài viết tự động theo keyword bằng Gemini, Open AI, Claude...
 * Version: 7.9
 * Author: thienvt
 * Author URI: https://www.facebook.com/thienvt36/
 * License: GPLv2
 */

if (!defined('ABSPATH')) {
    exit;
}
define('VNAICONTENT_VERSION', '7.9');
function vnaicontent_set_timezone()
{
    date_default_timezone_set('Asia/Ho_Chi_Minh');
}
add_action('plugins_loaded', 'vnaicontent_set_timezone');

$dir_vnaicontent_data = ABSPATH . 'wp-content/uploads/vnaicontent';
if (!file_exists($dir_vnaicontent_data)) {
    mkdir($dir_vnaicontent_data);
}
define('VNAICONTENT_DATA', $dir_vnaicontent_data . '/');
define('VNAICONTENT_PATH', plugin_dir_path(__FILE__) . '/');
define('VNAICONTENT_URL', plugins_url('/', __FILE__));
define('VNAICONTENT_ADMIN_PAGE', admin_url('options-general.php?page=vnaicontent'));

require_once VNAICONTENT_PATH . 'admin.php';
require_once VNAICONTENT_PATH . 'function/cron.php';
require_once VNAICONTENT_PATH . 'function/ajax.php';
require_once VNAICONTENT_PATH . 'setting/config.php';
require_once VNAICONTENT_PATH . 'setting/disable-image-size.php';
require_once VNAICONTENT_PATH . 'setting/edit-content.php';
require_once VNAICONTENT_PATH . 'setting/edit-feed.php';
require_once VNAICONTENT_PATH . 'setting/update-model.php';
require_once VNAICONTENT_PATH . 'setting/wp-config-modifi.php';
require_once VNAICONTENT_PATH . 'setting/edit-list-post.php';
require_once VNAICONTENT_PATH . 'setting/edit-post.php';

require_once VNAICONTENT_PATH . 'lib/plugin-update-checker/plugin-update-checker.php';
$options = vnaicontent_get_options();

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

if (!empty($options['user_key'])) {
    $myUpdateChecker = PucFactory::buildUpdateChecker(
        'https://vngpt.pro/update/?token=' . $options['user_key'],
        __FILE__,
        'vnaicontent'
    );
}
