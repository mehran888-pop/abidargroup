<?php
/**
 * Widget: compact contact card.
 *
 * @package Abidar
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class Abidar_Contact_Widget
 */
class Abidar_Contact_Widget extends WP_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			'abidar_contact',
			__( 'Abidar — Contact card', 'abidar' ),
			array(
				'description' => __( 'Address, phone, email and hours from the theme options.', 'abidar' ),
				'classname'   => 'widget-abidar-contact',
			)
		);
	}

	/**
	 * Front-end output.
	 *
	 * @param array $args     Sidebar args.
	 * @param array $instance Widget instance.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput

		$title = ! empty( $instance['title'] ) ? $instance['title'] : abidar_copy( 'footer_contact' );
		echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput

		abidar_contact_rows();

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput
	}

	/**
	 * Admin form.
	 *
	 * @param array $instance Instance.
	 */
	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'abidar' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
				type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p class="description"><?php esc_html_e( 'Contact details come from Abidar → Theme options → Contact details.', 'abidar' ); ?></p>
		<?php
	}

	/**
	 * Save.
	 *
	 * @param array $new_instance New.
	 * @param array $old_instance Old.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		return array(
			'title' => sanitize_text_field( $new_instance['title'] ?? '' ),
		);
	}
}
