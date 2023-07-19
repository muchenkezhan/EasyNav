<?php
require_once 'jwt-config.php'; // 导入JWT配置
require_once 'vendor/autoload.php'; // 如果你使用Composer安装了firebase/php-jwt，请添加此行
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\JWK;

// 加密
function generate_jwt($user_id) {
   $issued_at = time();
   $expiration_time = $issued_at + JWT_EXPIRATION_TIME;
   $payload = array(
       'iss' => get_site_url(),
       'iat' => $issued_at,
       'exp' => $expiration_time,
       'sub' => $user_id
   );

   return JWT::encode($payload, JWT_SECRET, JWT_ALGORITHM);
}
// 解密
function verify_jwt($token) {
    try {
        $jwt_decoded = JWT::decode($token, new Key(JWT_SECRET, 'HS256'));
        return $jwt_decoded;
    } catch (Exception $e) {
        return null;
    }
}
add_action('rest_api_init', function () {
   register_rest_route('jwt-auth/v1', 'token', array(
       'methods' => 'POST',
       'callback' => 'handle_login',
       'permission_callback'=> '__return_true',
   ));
});
function handle_login(WP_REST_Request $request) {
   $username = $request->get_param('username');
   $password = $request->get_param('password');

   $user = wp_authenticate($username, $password);

   if (is_wp_error($user)) {
       return new WP_Error('invalid_credentials', '用户名或密码错误', array('status' => 401));
   }

   $jwt = generate_jwt($user->ID);

   $response = new WP_REST_Response(
       array('token' => $jwt, 
       'user_display_name' =>$user->user_login,
       'user_email'=>$user->user_email,
       'user_nicename'=>$user->display_name,
       ));
   $response->set_status(200);

   return $response;
}
// 统一请求判断
function jwt_authenticate_request($request) {
   $auth_header = $request->get_header('Authorization');

   if (!$auth_header) {
       return new WP_Error('missing_auth_header', '缺少授权头', array('status' => 401));
   }

   list($token) = sscanf($auth_header, 'Bearer %s');
   if (!$token) {
       return new WP_Error('invalid_auth_header', '无效的授权头', array('status' => 401));
   }

   $decoded = verify_jwt($token);
   if (!$decoded) {
       return new WP_Error('400', 'token失效', array('status' => 401));
   }

   $user_id = $decoded->sub;
   $user = get_user_by('ID', $user_id);

   if (!$user) {
       return new WP_Error('user_not_found', '用户未找到', array('status' => 401));
   }

   wp_set_current_user($user_id);
   return true;
}
// 统一请求判断
function jwt_authenticate_requests($request) {
   $auth_header = $request->get_header('Authorization');
    
    if(!$auth_header){
        return true;
    }
  list($token) = sscanf($auth_header, 'Bearer %s');


  $decoded = verify_jwt($token);
    if(!$decoded){
          return new WP_Error('tokenLoseEfficacy', 'token失效', array('status' => 401));
    }
   $user_id = $decoded->sub;
   $user = get_user_by('ID', $user_id);
    if($user){
           wp_set_current_user($user_id);
   return true;
    }else{
        return false;
    }


}



// 当 permission_callback 的值不是 __return_true 时，WordPress REST API 将触发 rest_authentication_errors 钩子来进行身份验证
// add_filter('rest_authentication_errors', 'custom_jwt_auth_skip_login', 10, 1);

// function custom_jwt_auth_skip_login($error) {
//     return  array( 'code' => 200 ,'message'=>'测试触发');
//     // 其他路径继续进行默认的身份验证逻辑
//     return $error;
// }


// wordpress的接口操作
// 注册rest_api_init钩子



// 添加身份验证逻辑到REST API请求之前
add_action('rest_api_init', 'custom_jwt_auth_init');
function custom_jwt_auth_init() {
    // 添加身份验证逻辑到REST API请求之前
    add_filter('rest_pre_dispatch', 'custom_jwt_auth_check', 10, 3);
}
// 必须验证
function custom_jwt_auth_check($result, $server, $request) {
    // 获取请求路径
    $path = $request->get_route();

    // 检查是否为登录接口
  // 定义需要跳过身份验证的接口路径数组
    $skip_auth_paths = array(
        '/jwt-auth/v1/token',
        '/jwt-auth/v1/get',
        '/jwt-auth/v1/get-sidebar-website',
        '/jwt-auth/v1/user_nav_data',
        '/jwt-auth/v1/setting-personalise',
        '/jwt-auth/v1/user_nav_data',
        '/jwt-auth/v1/home_data',
        '/jwt-auth/v1/register',
    );

    // 遍历接口路径数组，检查是否需要跳过身份验证
    foreach ($skip_auth_paths as $skip_path) {
        if (strpos($path, $skip_path) !== false) {
            return $result; // 不进行身份验证，直接通过
        }
    }

    // 获取JWT令牌
  $auth_header = $request->get_header('Authorization');

  if (!$auth_header) {
      return new WP_Error('no_logo', '请先登陆！', array('status' => 401));
  }

  list($token) = sscanf($auth_header, 'Bearer %s');
  if (!$token) {
      return new WP_Error('invalid_auth_header', '无效的授权头', array('status' => 401));
  }


    if (empty($token)) {
        return new WP_Error('jwt_auth_no_token', 'No token provided', array('status' => 401));
    }

    // 验证JWT令牌
    $jwt_secret = 'your_jwt_secret'; // 替换为您的JWT密钥
    $jwt_algorithm = 'HS256'; // 根据您使用的JWT库进行替换

    try {
        // 解码令牌
        $decoded = verify_jwt($token);

        // 验证成功，可以根据需要执行其他操作
      $user_id = $decoded->sub;
      $user = get_user_by('ID', $user_id);

        // 例如，将当前用户设置为已验证用户
        wp_set_current_user($user_id);

        return $result;

    } catch (Exception $e) {

        // 令牌验证失败，返回错误响应
        return new WP_Error('jwt_auth_invalid_token', $token, array('status' => 401));
    }
}
