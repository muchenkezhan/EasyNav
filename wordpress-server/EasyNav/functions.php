<?
// ini_set("error_reporting", "E_ALL & ~E_NOTICE");
// jwt

require get_template_directory() . '/jwt.php';
if (!function_exists('_nav')) {
 function _nav($option = '', $default = null)
 {
  $options = get_option('readpre_my_options');
  return (isset($options[$option])) ? $options[$option] : $default;
 }}
     require_once 'vendor/autoload.php';
//  控制面板
require get_template_directory() . '/inc/framework/readpressfk.php';
// require get_template_directory() . '/Favicon.php';
// 引入接口
require get_template_directory() . '/inc/apis/home.php';
// 引入接口- 用户注册
require get_template_directory() . '/inc/apis/user.php';
// ico图标
require get_template_directory() . '/wp-favicon.php';

// 创建用户的首页数据表
// 创建自定义数据库表
function create_nav_home_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'nav_home';

    // 检查表格是否已存在
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        // 指定表格的字符集和字符排序规则
        $charset_collate = $wpdb->get_charset_collate();

        // 定义要创建的表格的SQL语句
        $sql = "CREATE TABLE $table_name (
           id mediumint(9) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) UNSIGNED NOT NULL,
            title varchar(255) NOT NULL,
            url varchar(255) NOT NULL,
            ico varchar(255) NOT NULL,
            sort_order INT(11) DEFAULT 0,
            PRIMARY KEY  (id),
            INDEX user_id_index (user_id)
        ) $charset_collate;";

        // 执行SQL语句并检查是否成功
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
add_action('after_switch_theme', 'create_nav_home_table');


// 创建数据库表
function my_custom_tables_install() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();
    $table_name_category = $wpdb->prefix . 'nav_category';
    $table_name_url = $wpdb->prefix . 'nav_url_table';

    // 检查网址分类表是否已存在
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name_category'") != $table_name_category) {
        // 创建网址分类表
        $sql_category = "CREATE TABLE $table_name_category (
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            icon VARCHAR(255),
            user_id INT(11) UNSIGNED,
            sort_order INT(11) DEFAULT 0
        ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_category);
    }

    // 检查网址表是否已存在
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name_url'") != $table_name_url) {
        // 创建网址表
        $sql_url = "CREATE TABLE $table_name_url (
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            url VARCHAR(255) NOT NULL,
            icon VARCHAR(255),
            description TEXT,
            category_id INT(11) UNSIGNED,
            sort_order INT(11) DEFAULT 0,
            FOREIGN KEY (category_id) REFERENCES $table_name_category(id)
        ) $charset_collate;";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_url);
    }
}

// 在激活插件时创建表
add_action('after_setup_theme', 'my_custom_tables_install');

// 禁用缓存
// 在主题的 functions.php 文件中添加以下代码
function disable_rest_api_cache( $response ) {
    $response->header( 'Cache-Control', 'no-cache, must-revalidate' );
    return $response;
}
add_filter( 'rest_post_dispatch', 'disable_rest_api_cache', 10, 1 );

// 创建配置项表

function create_nav_settings_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'nav_settings';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id INT(11) NOT NULL AUTO_INCREMENT,
        title VARCHAR(255),
        code VARCHAR(255),
        data TEXT,
        user_id INT(11) UNSIGNED,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}
add_action( 'after_setup_theme', 'create_nav_settings_table' );
