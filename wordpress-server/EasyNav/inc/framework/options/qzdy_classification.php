<?php if ( ! defined( 'ABSPATH' )  ) { die; } // Cannot access directly.

//
// Set a unique slug-like ID
//
$prefix = '_prefix_taxonomy_options';

//
// Create taxonomy options
//
CSF::createTaxonomyOptions( $prefix, array(
  'taxonomy' => 'category',
  'data-type' => 'unserialie',
) );
//
// Create a section
//
CSF::createSection( $prefix, array(
  'fields' => array(
    //
    // A text field
    // 
    array(
      'id'      => 'rp-category-module',
      'type'    => 'radio',
      'title'   => '分类模板选择',
      'inline'  => 'opt-index-xbanner',
      'options' => array(
        'rp-1' => '常规图片',
        'rp-2' => '图文',
        'rp-3' => '图片',
        'rp-4' => '双卡片',
        'rp-5' => 'BLOG',
      ),
      'default' => 'rp-1',
    ),
    
  )
) );