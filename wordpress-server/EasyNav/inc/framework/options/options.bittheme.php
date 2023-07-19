<?php 
/**
 * EasyNav是一个优秀的主题
 * Gitee：https://gitee.com/MUCEO/EasyNav/
 * 作者唯一QQ：858896214 （秋知德雨）
 * QQ交流群：917367358
 * 开发者不易，感谢支持，如果使用本主题尽量留个版权或者链接
 */
?>
<?php  if( class_exists( 'CSF' ) ) {
$prefix = 'readpre_my_options';
//
CSF::createOptions( $prefix, array(
  'menu_title' => 'EasyNav设置',
  'menu_slug'  => 'readpre-my_options',
) );
// 基础设置
CSF::createSection( $prefix, array(
  'id'    => 'media_fields',
  'title' => '基础设置',
  'icon'  => 'fas fa-tools',
    'fields'      => array(
      array(
        'type'    => 'subheading',
        'content' => 'LOGO与图标',
      ),

    array(
      'id'      => 'logo-img',
      'type'    => 'upload',
      'title'   => '网站图片LOGO',
       'subtitle' => '尺寸200px x 60px',
      'library' => 'image',
    ),
        array(
      'id'      => 'bg-img',
      'type'    => 'upload',
      'title'   => '网站默认背景图',
    //   'subtitle' => '尺寸200px x 60px',
      'library' => 'image',
    ),
    array(
      'id'    => 'copyright-text',
      'type'  => 'textarea',
      'title' => '自定义版权',
      'subtitle' => '',
      
    ),
     array(
      'id'       => 'home_category_url',
      'type'     => 'group',
      'title'    => '首页分类列表',
      'subtitle' => '默认打开之后的基础网站',
      'max' =>'1',
      'fields'   => array(
        array(
          'id'    => 'category_title',
          'type'  => 'text',
          'title' => '网站',
             ),
                array(
                  'id'     => 'child',
                  'type'   => 'group',
                  'title'  => '添加子网址',
                                  'fields' => array(
                                                array(
                                                  'id'    => 'title',
                                                  'type'  => 'text',
                                                  'title' => '网站名称',
                                                ),
                                                // array(
                                                //   'id'    => 'des',
                                                //   'type'  => 'text',
                                                //   'title' => '网站简介',
                                                // ),
                                                array(
                                                  'id'    => 'url',
                                                  'type'  => 'text',
                                                  'title' => '跳转地址',
                                                ),
                                    //             array(
                                    // 		    'id'    => 'icon',
                                    // 		    'type'  => 'upload',
                                    // 			'title' => '图片地址',
                                    // 			'default' => '',
                                    // 		    	),
                                  )
                ),
      ),
    ),





                
  )
) );
// 公共接口-侧边栏分类列表
CSF::createSection( $prefix, array(
  'id'    => 'media_fields',
  'title' => '侧边栏分类',
  'icon'  => 'fas fa-tools',
    'fields'      => array(
     array(
      'id'       => 'sidebar_category_website',
      'type'     => 'group',
      'title'    => '的网址分类',
      'subtitle' => '侧边栏默认网址分类',
      'fields'   => array(
        array(
          'id'    => 'title',
          'type'  => 'text',
          'title' => '网站',
             ),
//          array(
// 	    'id'    => 'icon',
// 	    'type'  => 'upload',
// 		'title' => '图片地址',
// 		'default' => '',
// 	    	),
                array(
                  'id'     => 'child',
                  'type'   => 'group',
                  'title'  => '添加子网址',
                                  'fields' => array(
                                                array(
                                                  'id'    => 'title',
                                                  'type'  => 'text',
                                                  'title' => '网站名称',
                                                ),
                                                // array(
                                                //   'id'    => 'des',
                                                //   'type'  => 'text',
                                                //   'title' => '网站简介',
                                                // ),
                                                array(
                                                  'id'    => 'url',
                                                  'type'  => 'text',
                                                  'title' => '跳转地址',
                                                ),
                                    //             array(
                                    // 		    'id'    => 'icon',
                                    // 		    'type'  => 'upload',
                                    // 			'title' => '图片地址',
                                    // 			'default' => '',
                                    // 		    	),
                                  )
                ),
      ),
    ),





                
  )
) );


} ?>