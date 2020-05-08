<?php

namespace HIK\Framework\Meta_Box;

if ( ! class_exists( '\HIK\Framework\Meta_Box\Meta_Box' ) ) {
	class Meta_Box {
		/**
		 * metabox id
		 * @var string
		 */
		public $id;
		
		/**
		 * Meta Box Title
		 * @var string
		 */
		public $title;

		public $screen;
		public $context;
		public $priority;

		/**
		 * metabox fields
		 * @var array
		 */
		public $fields = array();

		/**
		 * Fields Types
		 * @var array
		 */
		public static $field_types;

		public $render_cb;

		public function __construct( $args ) {
			$defaults = array(
				'id' => '',
				'title' => '',
				'screen' => '',
				'context' => 'advanced',
				'priority' => 'default',
				'fields' => array(), /* type, id, class, label, default, return_type	*/
				'render_cb' => null
			);

			$args = wp_parse_args( $args, $defaults );

			$this->id = $args['id'];
			$this->title = $args['title'];
			$this->screen = $args['screen'];
			$this->context = $args['context'];
			$this->priority = $args['priority'];
			$this->render_cb = $args['render_cb'];

			// register default field types
			$this->register_default_field_types();

			foreach ( $args['fields'] as $field ) {
				$this->add_field( $field );
			}

			// hook for register metabox
			add_action( 'add_meta_boxes', array( &$this, 'register' ), 10, 2 );

			// hook for enqueue scripts and styles
			add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_scripts_and_styles' ), 10 );
			
			add_action( 'save_post', array( &$this, 'save' ), 10 );
		}

		/**
		 * register metabox
		 * @return void
		 */
		public function register() {
			add_meta_box(
				$this->id,
				$this->title,
				is_callable( $this->render_cb ) ? $this->render_cb : array( &$this, 'render' ),
				$this->screen,
				$this->context,
				$this->priority
			);
		}

		/**
		 * render metabox html content
		 * @return void
		 */
		public function render( $post ) {
			foreach ( $this->fields as $field ) {
				$field->render( $post );
			}
		}

		/**
		 * add field to metabox
		 * @return void
		 */
		public function add_field( $args ) {
			// wp_die( var_dump( $args ) );
			$this->fields[] = new self::$field_types[ $args['type'] ]( $args );
		}

		/**
		 * get specific field with name
		 * @param string $name
		 * @return \HIK\Meta_Box_Field
		 */
		public function get_field( string $name ) {
			for ( $i = 0; $i < count( $this->fields ); $i++ ) {
				if ( $this->fields[ $i ]->name === $name ) {
					return $this->fields[ $i ];
				}
			}

			return false;
		}

		public function register_default_field_types() {
			$this->register_field_type( 'text', '\HIK\Framework\Meta_Box\Field' );
			$this->register_field_type( 'number', '\HIK\Framework\Meta_Box\Field' );
			$this->register_field_type( 'label', '\HIK\Framework\Meta_Box\Field_Label' );
			$this->register_field_type( 'editor', '\HIK\Framework\Meta_Box\Field_Editor' );
			$this->register_field_type( 'post', '\HIK\Framework\Meta_Box\Field_Post' );
			$this->register_field_type( 'taxonomy', '\HIK\Framework\Meta_Box\Field_Taxonomy' );
			$this->register_field_type( 'select', '\HIK\Framework\Meta_Box\Field_Select' );
		}

		public static function register_field_type( $type, $class_name ) {
			self::$field_types[ $type ] = $class_name;
		}

		public function enqueue_scripts_and_styles() {
			if ( ! $this->is_post_edit_page() ) {
				return;
			}

			wp_enqueue_script( 'haf-bootstrap', HIK_FRAMEWORK_DIR_URL . 'assets/js/bootstrap.bundle.min.js', array( 'jquery' ), true, '4.4.1' );
			wp_enqueue_style( 'haf-bootstrap', HIK_FRAMEWORK_DIR_URL . 'assets/css/bootstrap-rtl.min.css', array(), '4.4.1' );

			wp_enqueue_script( 'haf-select2', HIK_FRAMEWORK_DIR_URL . 'assets/js/select2.full.min.js', array( 'jquery' ), true, '4.0.3' );
			wp_enqueue_style( 'haf-select2', HIK_FRAMEWORK_DIR_URL . 'assets/css/select2.min.css', array(), '4.0.3' );
			
			wp_enqueue_script( 'haf-main', HIK_FRAMEWORK_DIR_URL . 'assets/js/main.js', array( 'jquery', 'haf-bootstrap', 'haf-select2' ), true, '0.0.1' );
			wp_localize_script( 'haf-main', 'pageData', [
				'ajaxUrl' => admin_url( 'admin-ajax.php' )
			] );
		}

		public function is_post_edit_page() {
			global $pagenow;
			if ( ! $pagenow === 'post.php' ) {
				return false;
			}

			if ( is_string( $this->screen ) && $this->screen === get_post_type() ) {
				return true;
			}

			if ( is_array( $this->screen ) ) {
				foreach( $this->screen as $s ) {
					if ( $s === get_post_type() ) {
						return true;
					}
				}
			}

			return false;
		}

		/**
		 * save metabox fields value
		 * @return void
		 */
		public function save( $post_id ) {
			if ( ! $this->is_post_edit_page() ) {
				return;
			}

			foreach ( $this->fields as $field ) {
				$name = 'haf-' . $field->name;
				$value = $field->default_value;
				if ( isset( $_POST[ $name ] ) ) {
					$value = $_POST[ $name ];
				}

				$field->set_value( $post_id, $value );
			}
		}
	}
}