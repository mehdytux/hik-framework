<?php
$dir_path = str_replace( '\\', '/', __DIR__ ) . '/';
define( 'HIK_FRAMEWORK_DIR_PATH',  $dir_path );

$dir_url = home_url() . '/' . str_replace( str_replace( '\\', '/', ABSPATH ), '', HIK_FRAMEWORK_DIR_PATH );
define( 'HIK_FRAMEWORK_DIR_URL', $dir_url );

require_once( HIK_FRAMEWORK_DIR_PATH . 'class-meta-box.php' );
require_once( HIK_FRAMEWORK_DIR_PATH . 'class-meta-box-field.php' );
require_once( HIK_FRAMEWORK_DIR_PATH . 'class-meta-box-field-editor.php' );
require_once( HIK_FRAMEWORK_DIR_PATH . 'class-meta-box-field-label.php' );
require_once( HIK_FRAMEWORK_DIR_PATH . 'class-meta-box-field-post.php' );
require_once( HIK_FRAMEWORK_DIR_PATH . 'class-meta-box-field-taxonomy.php' );
require_once( HIK_FRAMEWORK_DIR_PATH . 'class-meta-box-field-select.php' );
require_once( HIK_FRAMEWORK_DIR_PATH . 'class-post-type.php' );
require_once( HIK_FRAMEWORK_DIR_PATH . 'class-ajax-response.php' );