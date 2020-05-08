<?php
namespace HIK\Framework\Meta_Box;

class Field_Taxonomy extends Field {
	public $taxonomy;
	public $number;
	public $hide_empty;

	public function __construct( $args ) {
		parent::__construct( $args );

		$defaults = array(
			'taxonomy' => '',
			'number' => 0
		);

		$args = wp_parse_args( $args, $defaults );

		$this->taxonomy = $args['taxonomy'];
		$this->number = absint( $args['number'] );
		$this->hide_empty = absint( $args['hide_empty'] );
	}

	public function render( $post ) {
		$value = absint( $this->get_value( $post ) );

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
					data-taxonomy="<?php esc_attr_e( $this->taxonomy ); ?>"
				>
					<?php
					$terms = get_terms( array(
						'taxonomy' => $this->taxonomy,
						'number' => $this->number,
						'hide_empty' => $this->hide_empty
					) );

					foreach ( $terms as $term ) : ?>
						<option
							value="<?php esc_attr_e( $term->term_id ); ?>" 
							<?php selected( $value, $term->term_id ); ?>
						>
							<?php echo $term->name; ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<?php
	}
}