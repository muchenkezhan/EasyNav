<?php
if( class_exists( 'CSF' ) ) {
  $prefix = 'my_post_optionss0';
  CSF::createMetabox( $prefix, array(
    'title'     => '拓展功能',
    'post_type' => 'page',
    'data_type'  => 'unserialize',
  ) );
  CSF::createSection( $prefix, array(
    'title'  => 'SEO设置',
    'fields' => array(
            array(
        'id'    => 'qzdy_zdy_ym_key',
        'type'  => 'text',
        'title' => '自定义关键词',
      ),
            array(
        'id'    => 'qzdy_zdy_ym_des',
        'type'  => 'text',
        'title' => '自定义描述',
      ),
    )
  ) );
}
