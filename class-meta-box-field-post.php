<?php
namespace HIK\Framework\Meta_Box;

class Field_Post extends Field {
	public $post_type;

	public function __construct( $args ) {
		parent::__construct( $args );

		$defaults = array(
			'post_type' => 'post',
		);

		$args = wp_parse_args( $args, $defaults );

		$this->post_type = $args['post_type'];
	}

	public function render( $post ) {
		$value = absint( $this->get_value( $post ) );
		$selected_post = get_posts( array(
			'post_type' => $this->post_type,
			'post__in' => array( $value )
		) );

		$id = $this->id;
		if ( ! $id ) {
			$id = 'haf-' . $this->name . '-field';
		}

		$class = 'form-control post-select-field';
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
					data-post-type="<?php esc_attr_e( $this->post_type ); ?>"
				>
					<?php
					$posts = get_posts( array(
						'post_type' => $this->post_type,
						'posts_per_page' => -1
					) );
					
					foreach ( $posts as $post ) : ?>
						<option
							value="<?php esc_attr_e( $post->ID ); ?>" 
							<?php selected( $value, $post->ID ); ?>
						>
							<?php echo $post->post_title; ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<?php
	}
}