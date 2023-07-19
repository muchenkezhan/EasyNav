<?php if (!defined('ABSPATH')) {die;}
//
// Create a widget cao_widget_pay
//

// CAO主题的广告展示小工具
CSF::createWidget('cao_widget_ads', array(
    'title'       => '-广告展示',
    'classname'   => 'widget-adss',
    'description' => '',
    'fields'      => array(
        array(
            'id'         => '_color',
            'type'       => 'color',
            'title'      => '背景颜色',
            'default'    => '#21add1',
        ),
        array(
            'id'         => '_title',
            'type'       => 'text',
            'title'      => '主标题',
            'default'    => '免费领50元优惠券',
        ),
        array(
            'id'         => '_desc',
            'type'       => 'text',
            'title'      => '描述',
            'default'    => '安全有保障',
        ),
        array(
            'id'         => '_href',
            'type'       => 'text',
            'title'      => '链接地址',
            'default'    => 'https://.com/',
        ),
    ),
));
if (!function_exists('cao_widget_ads')) {
    function cao_widget_ads($args, $instance)
    {
        echo $args['before_widget'];
        // if ( ! empty( $instance['title'] ) ) {
        //   echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        // }
        // start
        $_color   = $instance['_color'];
        $_title     = $instance['_title'];
        $_desc     = $instance['_desc'];
        $_href     = $instance['_href'];
       
        echo '<div class="adsbg">';
        echo '<a class="asr" href="'.$_href.'" target="_blank" style="background-color:'.$_color.'">';
        echo '<h4>'.$_title.'</h4>';
        echo '<h5>'.$_desc.'</h5>';
        echo '<span class="btn btn-outline">立即查看</span>';
        echo '</a>';
        echo '</div>';
       
        // end
        echo $args['after_widget'];
    }
}