<?php
namespace HIK\Framework\Meta_Box;

if ( ! class_exists( '\HIK\Framework\Meta_Box\Field_Label' ) ) {
	class Field_Label extends Field {
		public function render( $post ) {
			$value = $this->get_value( $post );
			$class = 'text-dark';
			if ( $this->class ) {
				$class = $this->class;
			}
			?>
			<div class="col-12 p-0">
				<div class="form-group">
					<label for="<?php esc_attr_e( $post->ID ); ?>">
						<strong><?php echo $this->label; ?></strong>
						<span class="text-dark"><?php echo $value; ?></span>
					</label>
				</div>
			</div>
			<?php
		}

		public function set_value( $post_id, $value ) {
			return;
		}
	}
}