<?php

function add_cors_http_header(){
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
}
add_action('init','add_cors_http_header');


// 用户注册接口  正常

add_action( 'rest_api_init', function() {
    register_rest_route( 'jwt-auth/v1', '/register', array(
        'methods'  => 'POST',
        'callback' => 'myplugin_user_register',
    ) );
} );
function myplugin_user_register( WP_REST_Request $request ) {
    $username = sanitize_text_field( $request['username'] );
    $email = sanitize_email( $request['email'] );
    $password = $request['password']; // 不做任何过滤原样保存

    // 检查是否已经存在该用户名或邮箱的用户
    if ( username_exists( $username ) || email_exists( $email ) ) {
        return new WP_Error( '400', __( '用户名或电子邮件已存在', 'myplugin' ), array( 'status' => 400 ) );
    }

    // 创建新用户
    $user_id = wp_create_user( $username, $password, $email );

    if ( is_wp_error( $user_id ) ) {
        return new WP_Error( '500', __( '用户注册失败', 'myplugin' ), array( 'status' => 500 ) );
    }

    // 返回成功响应
    return array( 'message' => __( '注册成功', 'myplugin' ) );
    
}

// 修改用户昵称
add_action( 'rest_api_init', 'custom_update_user_nickname' );
function custom_update_user_nickname() {
    // 注册一个更新用户昵称的路由
    register_rest_route( 'jwt-auth/v1', '/update-nickname', array(
        'methods'  => 'POST',
        'callback' => 'custom_handle_update_nickname',
        'permission_callback' => 'xxzhuti_rest_permission_check'
    ) );
}

// 处理更新用户昵称的请求
function custom_handle_update_nickname( $request ) {
    // 获取参数中的用户 ID 和新昵称
    $nickname = $request->get_param( 'nickname' );
    $user_id = get_current_user_id();
    // 使用 WordPress 提供的函数更新用户昵称
    wp_update_user( array(
        'ID' => $user_id,
        'display_name' => $nickname,
        'nickname' => $nickname
    ) );
    // 返回成功的响应
    return array(
        'code'=>'200',
        'success' => true,
        'message' => $nickname
    );
}
function xxzhuti_rest_permission_check() {
    // 验证用户是否已登录
    if ( ! is_user_logged_in() ) {
        return new WP_Error( 'rest_forbidden', '您必须登录后才能访问接口', array( 'status' => 401 ) );
    }
    return true;
}

// 修改邮箱
function custom_update_email_callback($request) {
    // 获取当前用户的 ID
    $user_id = get_current_user_id();

    // 获取新的邮箱地址
    $new_email = sanitize_email($request['email']);
    
    // 检查新邮箱地址是否为空
    if (empty($new_email)) {
        return new WP_Error('400', '新邮箱地址不能为空', array('status' => 401));
    }
    
    // 检查新邮箱地址的格式是否正确
    if (!is_email($new_email)) {
        return new WP_Error('400', '新邮箱地址格式不正确', array('status' => 401));
    }

    // 更新用户邮箱地址
    $user = get_userdata($user_id);
    $user->user_email = $new_email;
    wp_update_user($user);

    // 返回成功响应
    return array('code' => 200, 'message' => '邮箱地址更新成功');
}

// 注册 REST API 路由
function custom_register_update_email_route() {
    register_rest_route('jwt-auth/v1', 'update_email', array(
        'methods' => 'POST',
        'callback' => 'custom_update_email_callback',
        'permission_callback' => 'is_user_logged_in',
    ));
}
add_action('rest_api_init', 'custom_register_update_email_route');
// 修改邮箱






function custom_register_login_route() {
    register_rest_route( 'jwt-auth/v1', '/login', array(
        'methods'  => 'POST',
        'callback' => 'custom_login_callback',
    ) );
}
add_action( 'rest_api_init', 'custom_register_login_route' );
function custom_login_callback(WP_REST_Request $request ) {
    // 获取请求中的用户名和密码参数
    $username = $request->get_param( 'username' );
    $password = $request->get_param( 'password' );

    // 使用 wp_signon() 函数进行用户登录验证
    $creds = array(
        'user_login'    => $username,
        'user_password' => $password,
        'remember'      => true,
    );
    $user = wp_signon( $creds, false );

    // 检查登录是否成功
    if ( is_wp_error( $user ) ) {
        return new WP_Error( 'rest_login_failed', $user->get_error_message(), array( 'status' => 401 ) );
    }

    // 生成并返回 JWT token
    $token = JWT::encode(['user_id' => $user->ID], 'your_secret_key', 'HS256');

    return array(
        'token' => $token,
        'user'  => $user,
    );
}





// 获取用户首页网址
function register_get_home_url_data_route() {
    register_rest_route( 'jwt-auth/v1', 'get-nav-home-data', array(
        'methods'  => 'POST',
        'callback' => 'get_home_url_data_callback',
        'permission_callback' => 'jwt_authenticate_requests'
    ) );
}
add_action( 'rest_api_init', 'register_get_home_url_data_route' );
function get_home_url_data_callback( $request ) {
    // 获取当前用户的 ID
    $user_id = get_current_user_id();

    global $wpdb;
    $table_name = $wpdb->prefix . 'nav_home';

    // 构建 SQL 查询语句，仅查询 title 和 src 字段
    $sql = $wpdb->prepare("SELECT id,title, url FROM $table_name WHERE user_id = %d", $user_id);

    // 执行查询
    $results = $wpdb->get_results($sql);
    if(!is_user_logged_in()){
        return array('code' => 200,
            'message' => '网站默认分类数据',
            'identity' => '未登录，默认数据','data'=> _nav('home_category_url')[0]['child']);
    }

    if (empty($results)) {
       
        return array(
        'code' => 200,
        'message' => '用户登录，未找到指定数据',
    );
    
        
    }

    // 判断用户是否登录
    if (is_user_logged_in()) {
        // 用户已登录，返回具体的数据结果
        $data = array();
        foreach ($results as $result) {
            $data[] = array(
                'title' => $result->title,
                'url' => $result->url,
                'id' => $result->id,
            );
        }

        return array(
            'code' => 200,
            'message' => '查询成功',
            'data' => $data,
        );
    } else {
        // 用户未登录，返回默认数据
        return array(
            'code' => 200,
            'message' => '网站默认分类数据',
            'identity' => '未登录，默认数据',
            'data' => _nav('home_category_url')[0]['child'],
        );
    }

}
// 新建网址
function register_add_nav_home_data_route() {
    register_rest_route( 'jwt-auth/v1', 'add-nav-home-data', array(
        'methods'  => 'POST',
        'callback' => 'add_nav_home_data_callback',
        'permission_callback' => 'jwt_authenticate_request'
    ) );
}
add_action( 'rest_api_init', 'register_add_nav_home_data_route' );
function add_nav_home_data_callback( $request ) {
    // 获取当前用户的 ID
    $user_id = get_current_user_id();
    global $wpdb;
    $title = $request->get_param('title');
    $url = $request->get_param('url');

    // 获取当前用户 ID
    $user_id = get_current_user_id();

    global $wpdb;
    $table_name = $wpdb->prefix . 'nav_home';

    // 构建要插入的数据数组
    $data = array(
        'title' => $title,
        'url' => $url,
        'user_id' => $user_id,
    );

    // 插入数据
    $result = $wpdb->insert($table_name, $data);

    if ($result === false) {
        return new WP_Error('500', '创建数据失败', array('status' => 500));
    }

    // 返回成功的响应
    return array(
        'code' => 200,
        'message' => '数据创建成功',
    );
    

}

// 修改某条首页网址数据
function register_edit_nav_home_data_route() {
    register_rest_route( 'jwt-auth/v1', 'edit-nav-home-data', array(
        'methods'  => 'POST',
        'callback' => 'edit_nav_home_data_callback',
        'permission_callback' => 'jwt_authenticate_request'
    ) );
}
add_action( 'rest_api_init', 'register_edit_nav_home_data_route' );
function edit_nav_home_data_callback( $request ) {
    // 获取当前用户的 ID
    $user_id = get_current_user_id();
    $title = $request->get_param('title');
    $url = $request->get_param('url');
    $id = $request->get_param('id');
    
    global $wpdb;
     $table_name = $wpdb->prefix . 'nav_home';
    // 检查记录是否存在
    $record_exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE id = %d AND user_id = %d", $id, $user_id));

    if (!$record_exists) {
        return new WP_Error('401', '非法操作', array('status' => 401));
    }

    $table_name = $wpdb->prefix . 'nav_home';

    // 构建要更新的数据数组
    $data = array(
        'title' => $title,
        'url' => $url,
    );

    // 构建更新条件数组
    $where = array(
        'id' => $id,
        'user_id' => $user_id,
    );

    // 更新数据
    $result = $wpdb->update($table_name, $data, $where);

    if ($result === false) {
        return new WP_Error('update_failed', '更新数据失败', array('status' => 500));
    }

    // 返回成功的响应
    return array(
        'code' => 200,
        'message' => '数据更新成功',
    );

}
// 删除某条首页网址数据
function register_delete_nav_home_data_route() {
    register_rest_route( 'jwt-auth/v1', 'delete-nav-home-data', array(
        'methods'  => 'POST',
        'callback' => 'delete_nav_home_data_callback',
        'permission_callback' => 'jwt_authenticate_request'
    ) );
}
add_action( 'rest_api_init', 'register_delete_nav_home_data_route' );
function delete_nav_home_data_callback( $request ) {
    // 获取当前用户的 ID
    $user_id = get_current_user_id();
    $id = $request->get_param('id');
 
   // 检查是否存在对应的项
   global $wpdb;
   $table_name = $wpdb->prefix . 'nav_home';
   $query = $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d AND user_id = %d", $id, $user_id);
   $result = $wpdb->get_row($query);

   if (!$result) {
       // 项不存在，返回非法请求错误
       return new WP_Error('400', '非法请求', array('status' => 400));
   }

   // 删除项
   $delete_query = $wpdb->prepare("DELETE FROM $table_name WHERE id = %d AND user_id = %d", $id, $user_id);
   $delete_result = $wpdb->query($delete_query);

   if ($delete_result) {
       // 删除成功
       return array(
           'code' => 200,
           'success' => true,
           'message' => '删除成功',
       );
   } else {
       // 删除失败
       return new WP_Error('400', '删除失败', array('status' => 400));
   }

}






















// 当前用户获取侧边栏网址接口
function get_sidebar_nav_url_home_data_route() {
    register_rest_route( 'jwt-auth/v1', 'get-sidebar-website', array(
        'methods'  => 'GET',
        'callback' => 'get_sidebar_nav_url_home_data_callback',
        'permission_callback' => 'jwt_authenticate_requests'
    ) );
}
add_action( 'rest_api_init', 'get_sidebar_nav_url_home_data_route' );

// 获取分类网址方法
function get_sidebar_nav_url_home_data_callback( $request ) {
    // 获取当前用户的 ID
    $user_id = get_current_user_id();
    global $wpdb;
    // 将数据转换为 JSON 字符串
    // 检查是否已存在记录
    $table_name = $wpdb->prefix . 'nav_category'; // 替换为实际的表名
    $existing_data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE user_id = %d", $user_id ) );
    if ( $existing_data ) {
        // 更新现有记录
        $nav_category_table = $wpdb->prefix . 'nav_category'; // nav_category 表名
        $nav_url_table = $wpdb->prefix . 'nav_url_table'; // nav_url_table 表名
        // 查询 nav_category 表中 user_id 为 12 的所有数据
        $category_query = $wpdb->prepare("SELECT * FROM $nav_category_table WHERE user_id = %d", $user_id);
        $categories = $wpdb->get_results($category_query);
        $result = array();
        if ($categories) {
            foreach ($categories as $category) {
                $category_id = $category->id;
                $category_title = $category->title;
                $category_description = $category->description;
                $category_icon = $category->icon;
                $category_sort_order = $category->sort_order;
                // 查询 nav_url_table 表中 category_id 等于当前分类的所有数据
                $url_query = $wpdb->prepare("SELECT * FROM $nav_url_table WHERE category_id = %d", $category_id);
                $urls = $wpdb->get_results($url_query);
                $child = array();
                if ($urls) {
                    foreach ($urls as $url) {
                        $url_id = $url->id;
                        $url_title = $url->title;
                        $url_url = $url->url;
                        $url_icon = $url->icon;
                        $url_description = $url->description;
                        $sort_order = $url->sort_order;
        
                        // 构建每个网址对象
                        $url_data = array(
                            'id' => $url_id,
                            'title' => $url_title,
                            'url' => $url_url,
                            'icon' => $url_icon,
                            'description' => $url_description,
                            'sort_order' => $sort_order
                        );
        
                        // 将网址对象添加到子数组
                        $child[] = $url_data;
                    }
                }
        
                // 构建每个分类对象
                $category_data = array(
                    'id' => $category_id,
                    'title' => $category_title,
                    'description' => $category_description,
                    'icon' => $category_icon,
                    'sort_order' => $category_sort_order,
                    'child' => $child 
                );
        
                // 将分类对象添加到结果数组
                $result[] = $category_data;
            }
        }
        
        // 将结果数组转换为 JSON 字符串
        $json_data = json_encode($result);
        
        
        return array(
            'success' => true,
            'message' => '用户数据查询成功',
            'code'=>200,
            'data'=>$result
          );
        
    } else {
        
        if($user_id > 0){
             return array(
            'code'=>200,
            'message' => '网站默认分类数据',
            'identity'=>'已登录但未有数据',
            'data'=>'noData',
            );
           }else{
           return array(
            'code'=>200,
            'message' => '网站默认分类数据',
            'identity'=>'未登录，默认数据',
            'data'=>_nav('sidebar_category_website'),
         );
           }

    }


}

// 新建网址分类
function add_sidebar_nav_category_url_data_route() {
    register_rest_route( 'jwt-auth/v1', 'add-sidebar-website-category', array(
        'methods'  => 'POST',
        'callback' => 'add_sidebar_nav_category_url_data_callback',
        'permission_callback' => 'add_sidebar_nav_category_url_data_permission_check'
    ) );
}
add_action( 'rest_api_init', 'add_sidebar_nav_category_url_data_route' );

function add_sidebar_nav_category_url_data_callback( $request ) {
    // 获取当前用户的 ID
    $user_id = get_current_user_id();
    $title = sanitize_text_field( $request['title'] );
    $description = sanitize_text_field( $request['description'] );
    $icon = $request['icon'];
    if(!$title){
        return new WP_Error( '400', '标题不能为空', array( 'status' => 401 ) );
    }
    global $wpdb;
    
    $data = array(
        'title' => $title,
        'description' => $description,
        'icon' => $icon,
        'user_id' => $user_id
    );
      $nav_category_table = $wpdb->prefix . 'nav_category'; // nav_category 表名
    // 向 nav_category 表插入数据
    $result = $wpdb->insert($nav_category_table, $data);
    
    if ($result === false) {
        // 插入失败
        return  array( 'code' => 400 ,'message'=>'创建失败');
    } else {
        // 插入成功
         return  array( 'code' => 200 ,'message'=>'创建分类成功');
    }
  


}
function add_sidebar_nav_category_url_data_permission_check() {
    // 验证用户是否已登录
    if ( ! is_user_logged_in() ) {
        return new WP_Error( 'rest_forbidden', '您必须登录后才能访问接口', array( 'code' => 401 ) );
    }
    // 获取当前用户ID
    $user_id = get_current_user_id();
    return true;
}
// 编辑网址分类
function edit_sidebar_nav_category_url_data_route() {
    register_rest_route( 'jwt-auth/v1', 'edit-sidebar-website-category', array(
        'methods'  => 'POST',
        'callback' => 'edit_sidebar_nav_category_url_data_callback',
        'permission_callback' => 'edit_sidebar_nav_category_url_data_permission_check'
    ) );
}
add_action( 'rest_api_init', 'edit_sidebar_nav_category_url_data_route' );

function edit_sidebar_nav_category_url_data_callback( $request ) {
    // 获取当前用户的 ID
    $user_id = get_current_user_id();
    $title = sanitize_text_field( $request['title'] );
    $categores_id = sanitize_text_field( $request['cat_id'] );
    
     if(!$categores_id){
        return new WP_Error( '400', '分类id不能为空', array( 'status' => 401 ) );
    }
    if(!$title){
        return new WP_Error( '400', '标题不能为空', array( 'status' => 401 ) );
    }
     global $wpdb;
    
    // 查询 nav_category 表
    $category_row = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}nav_category WHERE id = %d AND user_id = %d",
            $categores_id, $user_id
        )
    );

    if (!$category_row) {
        return new WP_Error('404', '未找到符合条件的分类', array('status' => 404));
    }

    // 查询 nav_url_table 表
    $url_row = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}nav_category WHERE id = %d",
            $categores_id
        )
    );

    if (!$url_row) {
        return new WP_Error('404', '未找到符合条件的标题', array('status' => 404));
    }

    // 更新字段值
    $url_table_name = $wpdb->prefix . 'nav_category';
    $data = array(
        'title' => $title,
     
    );

    $where = array(
        'id' => $categores_id,
        'user_id' => $user_id,
    );

    $result = $wpdb->update($url_table_name, $data, $where);

    // 检查更新是否成功
    if ($result === false) {
        return new WP_Error('400', '更新失败', array('status' => 500));
    }

    // 返回成功响应
     return  array( 'code' => 200 ,'message'=>'更新分类标题成功');


}
function edit_sidebar_nav_category_url_data_permission_check() {
    // 验证用户是否已登录
    if ( ! is_user_logged_in() ) {
        return new WP_Error( 'rest_forbidden', '您必须登录后才能访问接口', array( 'code' => 401 ) );
    }
    // 获取当前用户ID
    $user_id = get_current_user_id();
    return true;
}







// 删除该分类

function delete_sidebar_nav_category_url_data_route() {
    register_rest_route( 'jwt-auth/v1', 'delete-sidebar-website-category', array(
        'methods'  => 'POST',
        'callback' => 'delete_sidebar_nav_category_url_data_callback',
        'permission_callback' => 'delete_sidebar_nav_category_url_data_permission_check'
    ) );
}
add_action( 'rest_api_init', 'delete_sidebar_nav_category_url_data_route' );
function delete_sidebar_nav_category_url_data_callback( $request ) {
    // 获取当前用户的 ID
    $user_id = get_current_user_id();
    $category_id = sanitize_text_field( $request['category_id'] );
    
    if(!$category_id){
        return new WP_Error( '400', '分类id不能为空', array( 'status' => 401 ) );
    }
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'nav_category'; // 替换为实际的表名
    
    // 定义删除条件
    $where = array(
        'id' => $category_id,
        'user_id' => $user_id,
    );
    
    // 执行删除操作
    $result = $wpdb->delete($table_name, $where);
    
    if ($result === false) {
        // 删除失败
       return new WP_Error( '400', '删除分类失败', array( 'status' => 401 ) );
    } else {
        // 删除成功
        return  array( 'code' => 200 ,'message'=>'删除分类成功');
    }


}
function delete_sidebar_nav_category_url_data_permission_check() {
    // 验证用户是否已登录
    if ( ! is_user_logged_in() ) {
        return new WP_Error( 'rest_forbidden', '您必须登录后才能访问接口', array( 'code' => 401 ) );
    }
    // 获取当前用户ID
    $user_id = get_current_user_id();
    return true;
}







// 更新子网址tag属性
// 注册自定义REST路由
add_action('rest_api_init', 'register_nav_url_update_route');

function register_nav_url_update_route() {
    register_rest_route('jwt-auth/v1', '/update_nav_url', array(
        'methods' => 'POST',
        'callback' => 'update_nav_url_callback',
        'permission_callback' => '__return_true',
    ));
}

// 处理更新操作的回调函数
function update_nav_url_callback($request) {
    // 获取请求参数
    $user_id = get_current_user_id();
    $id = $request->get_param('website_id');
    $category_id = $request->get_param('category_id');
    $title = $request->get_param('title');
    $url = $request->get_param('url');
    $icon = $request->get_param('icon');

    // 检查是否存在对应的分类
    global $wpdb;
    $category_table_name = $wpdb->prefix . 'nav_category';

    $category_exists = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $category_table_name WHERE id = %d AND user_id = %d",
        $category_id,
        $user_id // 替换为实际的用户ID
    ));

    if (!$category_exists) {
        return new WP_Error('400', '非法操作', array('status' => 404));
    }

    // 更新数据
    $url_table_name = $wpdb->prefix . 'nav_url_table';

    $data = array(
        'title' => $title,
        'url' => $url,
        'icon' => $icon,
    );

    $where = array(
        'id' => $id,
        'category_id' => $category_id,
    );

    $result = $wpdb->update($url_table_name, $data, $where);

    // 检查更新是否成功
    if ($result === false) {
        return new WP_Error('400', '更新失败', array('status' => 500));
    }

    // 返回成功响应
     return  array( 'code' => 200 ,'message'=>'更新网址成功');
}





// 创建分类下的网址
function add_sidebar_nav_category_item_url_data_route() {
    register_rest_route( 'jwt-auth/v1', 'add-sidebar-category-website-url', array(
        'methods'  => 'POST',
        'callback' => 'add_sidebar_nav_category_item_url_data_callback',
        'permission_callback' => 'add_sidebar_nav_category_item_url_data_permission_check'
    ) );
}
add_action( 'rest_api_init', 'add_sidebar_nav_category_item_url_data_route' );
function add_sidebar_nav_category_item_url_data_callback( $request ) {
    // 获取当前用户的 ID
    $user_id = get_current_user_id();
    $categores_id = sanitize_text_field( $request['categores_id'] );
    $title = sanitize_text_field( $request['title'] );
    $url = sanitize_text_field( $request['url'] );
    $sort_order = sanitize_text_field( $request['sort_order'] );
    $icon = $request['icon'];
    $description = sanitize_text_field( $request['description'] );
    if(!$title){
        return new WP_Error( '400', '标题不能为空', array( 'status' => 401 ) );
    }

    global $wpdb;
    $table_name_category = $wpdb->prefix . 'nav_category';
    $table_name_url = $wpdb->prefix . 'nav_url_table';
    
    $user_id = $user_id;
    $id = $categores_id;
    $title = $title;
    $url = $url;
    $icon = $icon;
    $description = $description;
    $sort_order = 0;
    
    // 判断条件是否成立
    $query = $wpdb->prepare(
        "SELECT * FROM $table_name_category WHERE id = %d AND user_id = %d",
        $id,
        $user_id,
    );
    $category_data = $wpdb->get_row($query);
    
    if ($category_data) {
        // 条件成立，插入数据到nav_url_table表
        $data = array(
            'title' => $title,
            'url' => $url,
            'icon' => $icon,
            'description' => $description,
            'category_id' => $id,
            'sort_order' => $sort_order,
        );
    
        $result = $wpdb->insert($table_name_url, $data);
    
        if ($result === false) {
            // 插入失败
            return  array( 'code' => 401 ,'message'=>'新建网址失败');
        } else {
            // 插入成功
            return  array( 'code' => 200 ,'message'=>'新建网址成功');
        }
    } else {
        // 条件不成立，不进行插入操作

           return  array( 'code' => 401 ,'message'=>'非法请求，无法插入数据');
    }
    

}
function add_sidebar_nav_category_item_url_data_permission_check() {
    // 验证用户是否已登录
    if ( ! is_user_logged_in() ) {
        return new WP_Error( 'rest_forbidden', '您必须登录后才能访问接口', array( 'code' => 401 ) );
    }
    // 获取当前用户ID
    $user_id = get_current_user_id();
    return true;
}

// 删除分类下的网址
function delete_sidebar_nav_category_item_url_data_route() {
    register_rest_route( 'jwt-auth/v1', 'delete-sidebar-category-website-url', array(
        'methods'  => 'POST',
        'callback' => 'delete_sidebar_nav_category_item_url_data_callback',
        'permission_callback' => 'delete_sidebar_nav_category_item_url_data_permission_check'
    ) );
}
add_action( 'rest_api_init', 'delete_sidebar_nav_category_item_url_data_route' );

function delete_sidebar_nav_category_item_url_data_callback( $request ) {
    // 获取当前用户的 ID
    $user_id = get_current_user_id();
    $categores_id = sanitize_text_field( $request['category_id'] );
    $url_id = sanitize_text_field( $request['id'] );

    if(!$categores_id){
        return new WP_Error( '400', '分类id不能为空', array( 'status' => 401 ) );
    }
    if(!$url_id){
        return new WP_Error( '400', '标签id不能为空', array( 'status' => 401 ) );
    }
     global $wpdb;
    $table_name = $wpdb->prefix . 'nav_url_table';
    
    // 定义删除条件
    $where = array(
        'id' => $url_id,
        'category_id' => $categores_id,
    );
    
    // 执行删除操作
    $result = $wpdb->delete($table_name, $where);
    
    if ($result === false) {
        // 删除失败
        return new WP_Error( '400', '删除网址失败', array( 'code' => 401 ) );
    } else {
        // 删除成功
        return  array( 'code' => 200 ,'message'=>'删除网址成功');
    }
        

}
function delete_sidebar_nav_category_item_url_data_permission_check() {
    // 验证用户是否已登录
    if ( ! is_user_logged_in() ) {
        return new WP_Error( '400', '您必须登录后才能访问接口', array( 'code' => 401 ) );
    }
    // 获取当前用户ID
    $user_id = get_current_user_id();
    return true;
}

// 修改侧边栏分类下网址的内容
add_action( 'rest_api_init', 'edit_sidebar_nav_category_item_url_data_route' );
function edit_sidebar_nav_category_item_url_data_route() {
    register_rest_route( 'jwt-auth/v1', 'edit-sidebar-category-website-url', array(
        'methods'  => 'POST',
        'callback' => 'edit_sidebar_nav_category_item_url_data_callback',
        'permission_callback' => 'edit_sidebar_nav_category_item_url_data_permission_check'
    ) );
}

function edit_sidebar_nav_category_item_url_data_callback( $request ) {
    // 获取当前用户的 ID
    $user_id = get_current_user_id();
    $categores_id = sanitize_text_field( $request['categoryId'] );
    $url_id = sanitize_text_field( $request['id'] );
    $title = sanitize_text_field( $request['title'] );
    $url = sanitize_text_field( $request['url'] );

    if(!$categores_id){
        return new WP_Error( '400', '分类id不能为空', array( 'status' => 401 ) );
    }
    if(!$url_id){
        return new WP_Error( '400', '网址id不能为空', array( 'status' => 401 ) );
    }
     global $wpdb;
    
    // 查询 nav_category 表
    $category_row = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}nav_category WHERE id = %d AND user_id = %d",
            $categores_id, $user_id
        )
    );

    if (!$category_row) {
        return new WP_Error('404', '未找到符合条件的分类', array('status' => 404));
    }

    // 查询 nav_url_table 表
    $url_row = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}nav_url_table WHERE category_id = %d",
            $categores_id
        )
    );

    if (!$url_row) {
        return new WP_Error('404', '未找到符合条件的URL', array('status' => 404));
    }

    // 更新字段值
    $result = $wpdb->update(
        $wpdb->prefix . 'nav_url_table',
        array(
            'title' => $title,
            'url'   => $url,
        ),
        array('id' => $url_id),
        array('%s', '%s'),
        array('%d')
    );

    if ($result === false) {
        return new WP_Error('500', '数据更新失败', array('status' => 500));
    } elseif ($result === 0) {
        return array(
        'code' => 200,
        'message' => '获取数据失败或为空！'
    );
    }
    
    return array(
        'code' => 200,
        'message' => '更新成功！'
    );

}
function edit_sidebar_nav_category_item_url_data_permission_check() {
    // 验证用户是否已登录
    if ( ! is_user_logged_in() ) {
        return new WP_Error( '400', '您必须登录后才能访问接口', array( 'code' => 401 ) );
    }
    // 获取当前用户ID
    $user_id = get_current_user_id();
    return true;
}


// 侧边栏网址分类排序
function sort_sidebar_nav_category_item_data_route() {
    register_rest_route( 'jwt-auth/v1', 'sort_sidebar-category', array(
        'methods'  => 'POST',
        'callback' => 'sort_sidebar_nav_category_item_data_callback',
        'permission_callback' => 'sort_sidebar_nav_category_item_data_permission_check'
    ) );
}
add_action( 'rest_api_init', 'sort_sidebar_nav_category_item_data_route' );
function sort_sidebar_nav_category_item_data_callback( $request ) {
    // 获取当前用户的 ID
    $user_id = get_current_user_id();

    // 获取排序后的数据
    $sortedData =  $request->get_param( 'data' );// 假设数据已经通过POST请求发送到后端
    if(!$sortedData){
      return $response = array(
            'code' => 400,
            'message' => '请发送数据',
            'data'=>$sortedData
        );
    }


    $array = json_decode($sortedData, true);
    // 检查 JSON 解码是否发生错误
    if (json_last_error() !== JSON_ERROR_NONE) {
        return new WP_Error( '400', 'json格式错误', array( 'status' => 401 ) );
    }
    // 循环更新每个项的排序顺序
    // 将 JSON 数据解码为对象数组
//     $any = [];
    global $wpdb;
    // 跟踪更新操作结果的变量
    $allUpdated = true;
    foreach ($array as $obj) {
//   echo $any =$obj['id'] . "\n";
    // 获取数据库表名
        $table_name = $wpdb->prefix . 'nav_category';
        
        // 更新数据
        $data = array(
            'sort_order' => $obj['sort_order'],
        );
        
        $where = array(
            'id' => $obj['id'],
            'user_id' => $user_id,
        );
        
        $wpdb->update($table_name, $data, $where);
         if ($result === false) {
        $allUpdated = false;
        break; // 可以选择在第一个更新失败时跳出循环
    }
    
    }
   

    if ($allUpdated) {
          return  array( 'code' => 2000 ,'message'=>'更新成功');
    } else {
        return new WP_Error( '400', '更新失败', array( 'status' => 401 ) );
    }

}

function sort_sidebar_nav_category_item_data_permission_check() {
    // 验证用户是否已登录
    if ( ! is_user_logged_in() ) {
        return new WP_Error( 'rest_forbidden', '您必须登录后才能访问接口', array( 'status' => 401 ) );
    }
    // 获取当前用户ID
    $user_id = get_current_user_id();
    return true;
}

// 侧边栏网址分类下面的网址排序
function sort_sidebar_nav_category_wbsite_item_data_route() {
    register_rest_route( 'jwt-auth/v1', 'sort_sidebar-category-website', array(
        'methods'  => 'POST',
        'callback' => 'sort_sidebar_nav_category_wbsite_item_data_callback',
        'permission_callback' => 'sort_sidebar_nav_category_wbsite_item_data_permission_check'
    ) );
}
add_action( 'rest_api_init', 'sort_sidebar_nav_category_wbsite_item_data_route' );

function sort_sidebar_nav_category_wbsite_item_data_callback( $request ) {
    // 获取当前用户的 ID
    $user_id = get_current_user_id();

    // 获取排序后的数据
    $sortedData =  $request->get_param( 'data' );// 假设数据已经通过POST请求发送到后端
    $categoryId =  $request->get_param( 'categoryId' );
    if(!$sortedData){
      return $response = array(
            'code' => 400,
            'message' => '请发送数据',
            'data'=>$sortedData
        );
    }


    $array = json_decode($sortedData, true);
    // 检查 JSON 解码是否发生错误
    if (json_last_error() !== JSON_ERROR_NONE) {
        return new WP_Error( '400', 'json格式错误', array( 'status' => 401 ) );
    }
    // 循环更新每个项的排序顺序
    // 将 JSON 数据解码为对象数组
//     $any = [];
    global $wpdb;
    // 跟踪更新操作结果的变量
    $allUpdated = true;
    foreach ($array as $obj) {
//   echo $any =$obj['id'] . "\n";
    // 获取数据库表名
        $table_name = $wpdb->prefix . 'nav_url_table';
        
        // 更新数据
        $data = array(
            'sort_order' => $obj['sort_order'],
        );
        
        $where = array(
            'id' => $obj['id'],
            'category_id' => $categoryId,
        );
        
        $wpdb->update($table_name, $data, $where);
         if ($result === false) {
        $allUpdated = false;
        break; // 可以选择在第一个更新失败时跳出循环
    }
    
    }
   

    if ($allUpdated) {
          return  array( 'code' => 2000 ,'message'=>'更新成功');
    } else {
        return new WP_Error( '400', '更新失败', array( 'status' => 401 ) );
    }

}

function sort_sidebar_nav_category_wbsite_item_data_permission_check() {
    // 验证用户是否已登录
    if ( ! is_user_logged_in() ) {
        return new WP_Error( 'rest_forbidden', '您必须登录后才能访问接口', array( 'status' => 401 ) );
    }
    // 获取当前用户ID
    $user_id = get_current_user_id();
    return true;
}













// 存储服务器列表


// 读取个性设置配置
function user_setting_personalise_item_data_route() {
    register_rest_route( 'jwt-auth/v1', 'setting-personalise', array(
        'methods'  => 'POST',
        'callback' => 'user_setting_personalise_item_data_callback',
        'permission_callback' => 'jwt_authenticate_requests'
    ) );
}
add_action( 'rest_api_init', 'user_setting_personalise_item_data_route' );
function user_setting_personalise_item_data_callback( $request ) {
    // 获取当前用户的 ID
    $user_id = get_current_user_id();

    global $wpdb;
    // 跟踪查询结果的变量

    $table_name = $wpdb->prefix . 'nav_settings'; // 替换成实际的数据库表名
    $code =  sanitize_text_field( $request['code'] );
        if(!$code){
        return new WP_Error( '400', 'code不能为空', array( 'status' => 401 ) );
    }
    $sql = $wpdb->prepare(
        "SELECT id, title, data FROM $table_name WHERE code = %s AND user_id = %d",
        $code,
        $user_id
    );
    
    $results = $wpdb->get_results($sql);
    
    foreach ($results as $result) {
        $id = $result->id;
        $title = $result->title;
        $data = $result->data;
    
        // 处理数据
        $datas = array('title' => $title, 'id' => $id, 'data' => json_decode($data));
        
        return array(
            'code' => 200,
            'message' => '获取个性配置成功',
            'data' => $datas
        );
    }
    
    return array(
        'code' => 400,
        'message' => '获取数据失败或为空！'
    );
}


// 更新个性设置配置
function set_user_setting_personalise_item_data_route() {
    register_rest_route( 'jwt-auth/v1', 'set-setting-personalise', array(
        'methods'  => 'POST',
        'callback' => 'set_user_setting_personalise_item_data_callback',
        'permission_callback' => 'set_user_setting_personalise_item_data_permission_check'
    ) );
}
add_action( 'rest_api_init', 'set_user_setting_personalise_item_data_route' );
function set_user_setting_personalise_item_data_callback( $request ) {
    // 获取当前用户的 ID
    $user_id = get_current_user_id();

    // 获取排序后的数据
    $code =  sanitize_text_field( $request['code'] );
    $externalImage =  sanitize_text_field( $request['externalImage'] );
    $serverImage =  sanitize_text_field( $request['serverImage'] );
    if(!$code){
         return  array( 'code' => 400 ,'message'=>'code不能为空');
    }

    global $wpdb;
     $personali = array(
       'code' => 1,
    );
    // $personalis = "[{code:'1',data:{type:1,externalImage:'在线图片',serverImage:'服务器图片'}}]";
    // 每个配置折叠
    $personalis_child_1 = (object) array('type' => 1, 'externalImage' => $externalImage, 'serverImage' => $serverImage);
    // 创建包含两个对象的数组
    $childArray = array($personalis_child_1);

    // 将 $childArray 添加为 $data 数组的 'child' 属性的值
    $personali['data'] = $childArray;
    $personaliArr=$personali;
    
    
    
    
    $table_name = $wpdb->prefix . 'nav_settings'; // 替换成实际的数据库表名
    
    
    // 查询是否存在 code=personality 的数据
    $query = $wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE code = %s AND user_id = %d",
        $code,
        $user_id);
    $count = $wpdb->get_var($query);
    
    if (!$count > 0) {
        
        
    $data = json_encode($personaliArr);
    $code = 'personality'; // 字段code
    $title = '个性设置'; // 字段title
    
    // 插入数据
    $wpdb->insert(
        $table_name,
        array(
            'user_id' => $user_id,
            'code' => $code,
            'title' => $title,
            'data' => $data
        ),
        array(
            '%d',
            '%s',
            '%s',
            '%s'
        )
    );
    
    // 判断插入是否成功
    if ($wpdb->insert_id) {
       return $response = array(
            'code' => 200,
            'message' => '更新成功'
        );
    } else {
       return $response = array(
            'code' => 500,
            'message' => '创建失败'
        );
    }

        
        
        
        
        
        
      
    }
    
    $data = array(
        'data' => json_encode($personaliArr) // 更新后的内容
    );
    
    $where = array(
        'code' => $code
    );
    
    $result = $wpdb->update($table_name, $data, $where);
    
    if ($result !== false) {
        return  array( 'code' => 200 ,'message'=>'更新数据成功');
    } else {
       return  array( 'code' => 400 ,'message'=>'更新数据失败');
    }
}

function set_user_setting_personalise_item_data_permission_check() {
    // 验证用户是否已登录
    if ( ! is_user_logged_in() ) {
        return new WP_Error( '401', '您必须登录后才能访问接口', array( 'status' => 401 ) );
    }
    // 获取当前用户ID
    $user_id = get_current_user_id();
    return true;
}

// 修改密码接口
// 注册自定义 REST API 路由
add_action('rest_api_init', 'register_custom_password_change_route');

function register_custom_password_change_route() {
    register_rest_route('jwt-auth/v1', '/change-password', array(
        'methods'  => 'POST',
        'callback' => 'custom_password_change_callback',
        'permission_callback' => 'is_user_logged_in',
    ));
}

function custom_password_change_callback($request) {
    // 获取当前用户的 ID
    $user_id = get_current_user_id();

    // 获取请求参数
    $old_password = sanitize_text_field( $request['old_password'] );
    $new_password = sanitize_text_field( $request['new_password'] );
     // 返回成功响应
    // return  array( 'code' => 200 ,'message'=>$old_password,$new_password);
    if(!$old_password){
         return new WP_Error('400', '不能为空', array('status' => 401));
    }
    // 检查旧密码是否正确
    $user = wp_get_current_user();
    $is_password_correct = wp_check_password($old_password, $user->user_pass, $user->ID);

    if (!$is_password_correct) {
        return new WP_Error('400', '旧密码不正确', array('status' => 401));
    }
    // 更新用户密码
    wp_set_password($new_password, $user_id);

    // 返回成功响应
    return  array( 'code' => 200 ,'message'=>'密码已经修改');
}

// 默认数据接口  logo
add_action('rest_api_init', 'home_seting_data');

function home_seting_data() {
    register_rest_route('jwt-auth/v1', '/home_data', array(
        'methods'  => 'GET',
        'callback' => 'home_seting_data_callback',

    ));
}

function home_seting_data_callback($request) {
    return  array( 'code' => 200 ,'message'=>'配置数据','data'=>array( 'logo' => _nav('logo-img') ,'copyright'=>_nav('copyright-text'),'bg_img'=>_nav('bg-img') ) );
}