<?php

namespace HIK\Framework\Post_Type;

if ( ! class_exists( '\HIK\Framework\Post_Type\Post_Type' ) ) {
	class Post_Type {
		/**
		 * @var array $args
		 */
		public $args;

		/**
		 * create new post type
		 * @param array $args
		 * @return void
		 */
		public function __construct( $args ) {
			// parse arguments
			$this->args = $this->parse_args( $args );
			
			// register method for init action for register post type
			add_action( 'init', array( &$this, 'register' ) );

			// filter table list columns
			add_action( 'admin_head-edit.php', array( &$this, 'edit_page_filters' ) );
		}

		public function parse_args( $args ) {
			$defaults = array(
				'name' 					=> '',
				'label'                 => '',
				'plural_label'          => '',
				'description'           => '',
				'supports'              => array( '' ), // 'title', 'editor'
				'taxonomies'            => array(), // 'category', 'post_tag'
				'hierarchical'          => false,
				'public'                => true,
				'show_ui'               => true,
				'show_in_menu'          => true,
				'menu_position'         => 5,
				'show_in_admin_bar'     => true,
				'show_in_nav_menus'     => true,
				'can_export'            => true,
				'has_archive'           => true,
				'exclude_from_search'   => false,
				'publicly_queryable'    => true,
				'capability_type'       => 'post',
				'labels' => array(),
				'filters' => []
			);

			$args = wp_parse_args( $args, $defaults );
			$args['labels'] = wp_parse_args( $args['labels'], array(
				'name'                  => '',
				'singular_name'         => '',
				'menu_name'             => '',
				'name_admin_bar'        => '',
				'archives'              => '',
				'attributes'            => '',
				'parent_item_colon'     => '',
				'all_items'             => '',
				'add_new_item'          => '',
				'add_new'               => '',
				'new_item'              => '',
				'edit_item'             => '',
				'update_item'           => '',
				'view_item'             => '',
				'view_items'            => '',
				'search_items'          => '',
				'not_found'             => '',
				'not_found_in_trash'    => '',
				'featured_image'        => '',
				'set_featured_image'    => '',
				'remove_featured_image' => '',
				'use_featured_image'    => '',
				'insert_into_item'      => '',
				'uploaded_to_this_item' => '',
				'items_list'            => '',
				'items_list_navigation' => '',
				'filter_items_list'     => '',
			) );

			// plural name
			if ( ! $args['plural_label'] ) {
				$args['plural_label'] = $args['label'] . ' ها';
			}

			/** Labels **/
			if ( ! $args['labels']['name'] ) {
				$args['labels']['name'] = $args['plural_label'];
			}

			if ( ! $args['labels']['singular_name'] ) {
				$args['labels']['singular_name'] = $args['label'];
			}

			if ( ! $args['labels']['menu_name'] ) {
				$args['labels']['menu_name'] = $args['plural_label'];
			}

			if ( ! $args['labels']['name_admin_bar'] ) {
				$args['labels']['name_admin_bar'] = $args['label'];
			}

			if ( ! $args['labels']['archives'] ) {
				$args['labels']['archives'] = 'بایگانی ' . $args['plural_label'];
			}

			if ( ! $args['labels']['attributes'] ) {
				$args['labels']['attributes'] = 'خصوصیات ' . $args['plural_label'];
			}

			if ( ! $args['labels']['parent_item_colon'] ) {
				$args['labels']['parent_item_colon'] = $args['label'] . ' مادر:';
			}

			if ( ! $args['labels']['all_items'] ) {
				$args['labels']['all_items'] = 'همه ' . $args['plural_label'];
			}

			if ( ! $args['labels']['add_new_item'] ) {
				$args['labels']['add_new_item'] = 'افزودن ' . $args['label'] . ' جدید';
			}

			if ( ! $args['labels']['add_new'] ) {
				$args['labels']['add_new'] = 'افزودن ' . $args['label'] . ' جدید';
			}

			if ( ! $args['labels']['new_item'] ) {
				$args['labels']['new_item'] = $args['label'] . ' جدید';
			}

			if ( ! $args['labels']['edit_item'] ) {
				$args['labels']['edit_item'] = 'ویرایش ' . $args['label'];
			}

			if ( ! $args['labels']['update_item'] ) {
				$args['labels']['update_item'] = 'بروزرسانی ' . $args['label'];
			}

			if ( ! $args['labels']['view_item'] ) {
				$args['labels']['view_item'] = 'نمایش ' . $args['label'];
			}

			if ( ! $args['labels']['view_items'] ) {
				$args['labels']['view_items'] = 'نمایش ' . $args['plural_label'];
			}

			if ( ! $args['labels']['search_items'] ) {
				$args['labels']['search_items'] = 'جستجوی در میان ' . $args['plural_label'];
			}

			if ( ! $args['labels']['not_found'] ) {
				$args['labels']['not_found'] = 'چیزی یافت نشد';
			}

			if ( ! $args['labels']['not_found_in_trash'] ) {
				$args['labels']['not_found_in_trash'] = 'چیزی در سطل زباله یافت نشد';
			}

			if ( ! $args['labels']['featured_image'] ) {
				$args['labels']['featured_image'] = 'تصویر ' . $args['label'];
			}

			if ( ! $args['labels']['set_featured_image'] ) {
				$args['labels']['set_featured_image'] = 'قرار دادن به عنوان تصویر ' . $args['label'];
			}

			if ( ! $args['labels']['remove_featured_image'] ) {
				$args['labels']['remove_featured_image'] = 'حذف تصویر ' . $args['label'];
			}

			if ( ! $args['labels']['use_featured_image'] ) {
				$args['labels']['use_featured_image'] = 'استفاده به عنوان تصویر ' . $args['label'];
			}

			if ( ! $args['labels']['insert_into_item'] ) {
				$args['labels']['insert_into_item'] = 'افزودن به ' . $args['label'];
			}

			if ( ! $args['labels']['uploaded_to_this_item'] ) {
				$args['labels']['uploaded_to_this_item'] = 'آئلود برای استفاده در ' . $args['label'];
			}

			if ( ! $args['labels']['items_list'] ) {
				$args['labels']['items_list'] = 'فهرست ' . $args['plural_label'];
			}

			if ( ! $args['labels']['items_list_navigation'] ) {
				$args['labels']['items_list_navigation'] = 'ناوبری فهرست ' . $args['plural_label'];
			}

			if ( ! $args['labels']['filter_items_list'] ) {
				$args['labels']['filter_items_list'] = 'فیلتر فهرست ' . $args['plural_label'];
			}

			return $args;
		}

		public function register() {
			register_post_type( $this->args['name'], $this->args );
		}

		public function edit_page_filters( $columns ) {
			if ( isset( $this->args['filters']['title'] ) ) {
    			add_filter( 'the_title', array( &$this, 'table_list_filter_title' ), 10, 2 );
    		}
		}

		public function table_list_filter_title( $title, $post_id ) {
			return $this->args['filters']['title']( $title, $post_id );
		}
	}
}