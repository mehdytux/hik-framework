<?php
namespace HIK\Framework\Meta_Box;

if ( ! class_exists( '\HIK\Framework\Meta_Box\Field' ) ) {
	class Field {
		public $type;
		public $name;
		public $id;
		public $class;
		public $label;
		public $placeholder;
		public $default_value;
		public $return_type;
		public $on_save;

		public function __construct( $args ) {
			$defaults = array(
				'type'          => 'text',
				'name'          => '',
				'id'            => '',
				'class'         => '',
				'label'         => '',
				'placeholder'   => '',
				'default_value' => '',
				'return_type'   => 'string',
				'on_save' => '',
				'on_save_filter' => '',
			);

			$args = wp_parse_args( $args, $defaults );

			$this->type          = $args['type'];
			$this->name          = trim( $args['name'] );
			$this->id            = trim( $args['id'] );
			$this->class         = trim( $args['class'] );
			$this->label         = $args['label'];
			$this->default_value = $args['default_value'];
			$this->return_type   = $args['return_type'];
			$this->placeholder   = $args['placeholder'];
			$this->on_save = $args['on_save'];
			$this->on_save_filter = $args['on_save_filter'];
		}

		public function render( $post ) {
			$value = $this->get_value( $post );
			$id = $this->id;
			if ( ! $id ) {
				$id = 'haf-' . $this->name . '-field';
			}

			$class = 'form-control';
			if ( $this->class ) {
				$class = $this->class;
			}
			?>
			<div class="col-12 p-0">
				<div class="form-group">
					<label for="<?php esc_attr_e( $id ); ?>">
						<strong><?php echo $this->label; ?></strong>
					</label>
					<input
						type="<?php esc_attr_e( $this->type ); ?>"
						name="haf-<?php esc_attr_e( $this->name ); ?>"
						id="<?php esc_attr_e( $id ); ?>"
						class="<?php esc_attr_e( $class ); ?>"
						value="<?php esc_attr_e( $value ); ?>"
						placeholder="<?php esc_attr_e( $this->placeholder ); ?>"
					>
				</div>
			</div>
			<?php
		}

		public function get_value( $post ) {
			$value = get_post_meta( $post->ID, $this->name, true );

			switch ( $this->return_type ) {
				case 'string':
					return strval( $value );
					break;
				case 'int':
				case 'integer':
					return intval( $value );
					break;
				case 'float':
					return floatval( $value );
					break;
				case 'array':
					json_decode( $value, true );
					break;
				case 'object':
					json_decode( $value );
					break;
				default:
					return $value;
					break;
			}
		}

		public function set_value( $post_id, $value ) {
			if ( $this->on_save && is_callable( $this->on_save ) ) {
				$fn = $this->on_save;
				$fn( $post_id, $value );
			} elseif ( $this->on_save_filter && is_callable( $this->on_save_filter ) ) {
				$this->set_value_default_callback( $post_id, $value );
				$fn = $this->on_save_filter;
				$fn( $post_id, $value );
			} else {
				$this->set_value_default_callback( $post_id, $value );
			}
		}

		public function set_value_default_callback( $post_id, $value ) {
			if (
				( $this->return_type === 'array' || $this->return_type === 'object' ) &&
				( is_array( $value ) || is_object( $value ) )
			) {
				$value = json_encode( $value, JSON_UNESCAPED_UNICODE );
			} elseif ( $this->type === 'editor' ) {
				$value = wp_kses_post( $value );
			}

			update_post_meta( $post_id, $this->name, $value );
		}
	}
}
