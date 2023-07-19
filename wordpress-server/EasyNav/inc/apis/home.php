<?php
// 首页分类列表数据
add_action('rest_api_init', 'register_custom_api_routes');
function register_custom_api_routes() {
    register_rest_route('api/', '/home/websites', [
        'methods' => 'GET',
        'callback' => 'get_home_websites'
    ]);
        // 注册获取新闻列表的路由
    register_rest_route('api/', '/home/sidebar-website', [
        'methods' => 'GET',
        'callback' => 'get_home__website_websites'
    ]);
}

// 获取网站列表的 API 回调函数
function get_home_websites() {return _nav('home_category_url')[0];}

// 获取网站列表的 API 回调函数
function get_home__website_websites() {return _nav('sidebar_category_website');}
