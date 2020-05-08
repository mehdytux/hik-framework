<?php
namespace HIK\Framework\Meta_Box;

class Field_Select extends Field {
	public $items;

	public function __construct( $args ) {
		parent::__construct( $args );

		$defaults = array(
			'items' => []
		);

		$args = wp_parse_args( $args, $defaults );

		$this->items = $args['items'];
	}

	public function render( $post ) {
		$value = $this->get_value( $post );

		$id = $this->id;
		if ( ! $id ) {
			$id = 'haf-' . $this->name . '-field';
		}

		$class = 'form-control taxonomy-select-field';
		if ( $this->class ) {
			$class = $this->class;
		}
		?>
		<div class="col-12 p-0">
			<div class="form-group">
				<label for="<?php esc_attr_e( $id ); ?>">
					<strong><?php echo $this->label; ?></strong>
				</label>

				<select
					name="haf-<?php esc_attr_e( $this->name ); ?>"
					id="<?php esc_attr_e( $id ); ?>"
					class="<?php esc_attr_e( $class ); ?>"
				>
					<?php foreach ( $this->items as $key => $text ) : ?>
						<option value="<?php esc_attr_e( $key ); ?>" <?php selected( $value, $key ); ?> >
							<?php echo $text; ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<?php
	}
}