<?php
namespace HIK\Framework\Meta_Box;

if ( ! class_exists( '\HIK\Framework\Meta_Box\Field_Editor' ) ) {
	class Field_Editor extends Field {
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
					<?php wp_editor( $value, $id, array( 'textarea_name' => 'haf-' . $this->name ) ); ?>
				</div>
			</div>
			<?php
		}
	}
}