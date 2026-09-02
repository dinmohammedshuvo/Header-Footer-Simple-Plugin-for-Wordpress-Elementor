<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings screen: pick which published header and which published footer
 * are the live ones, and jump straight into editing them with Elementor.
 */
class EHF_Settings {

	public static function register_menu() {
		add_options_page(
			'Header Footer Builder',
			'Header Footer Builder',
			'manage_options',
			'ehf-settings',
			array( __CLASS__, 'render_page' )
		);
	}

	public static function register_settings() {
		register_setting( 'ehf_settings_group', 'ehf_active_header', array( 'sanitize_callback' => 'absint' ) );
		register_setting( 'ehf_settings_group', 'ehf_active_footer', array( 'sanitize_callback' => 'absint' ) );
		register_setting( 'ehf_settings_group', 'ehf_sticky_header', array( 'sanitize_callback' => 'absint' ) );
	}

	public static function get_templates( $type ) {
		return get_posts(
			array(
				'post_type'      => EHF_CPT::POST_TYPE,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'meta_key'       => '_ehf_type',
				'meta_value'     => $type,
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);
	}

	private static function render_picker( $field_id, $label, $options, $active_id ) {
		?>
		<tr>
			<th scope="row"><label for="<?php echo esc_attr( $field_id ); ?>"><?php echo esc_html( $label ); ?></label></th>
			<td>
				<select id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo esc_attr( $field_id ); ?>">
					<option value="0">— None —</option>
					<?php foreach ( $options as $option ) : ?>
						<option value="<?php echo esc_attr( $option->ID ); ?>" <?php selected( $active_id, $option->ID ); ?>><?php echo esc_html( $option->post_title ); ?></option>
					<?php endforeach; ?>
				</select>
				<?php if ( $active_id ) : ?>
					<a href="<?php echo esc_url( admin_url( 'post.php?post=' . $active_id . '&action=elementor' ) ); ?>" class="button" target="_blank" rel="noopener">Edit with Elementor</a>
				<?php endif; ?>
			</td>
		</tr>
		<?php
	}

	private static function render_sticky_toggle( $enabled ) {
		?>
		<tr>
			<th scope="row">Sticky Header</th>
			<td>
				<label for="ehf_sticky_header">
					<input type="checkbox" id="ehf_sticky_header" name="ehf_sticky_header" value="1" <?php checked( $enabled, 1 ); ?> />
					Keep the active header pinned to the top of the screen while scrolling.
				</label>
			</td>
		</tr>
		<?php
	}

	public static function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$headers       = self::get_templates( 'header' );
		$footers       = self::get_templates( 'footer' );
		$active_header = absint( get_option( 'ehf_active_header' ) );
		$active_footer = absint( get_option( 'ehf_active_footer' ) );
		$sticky_header = absint( get_option( 'ehf_sticky_header' ) );
		?>
		<div class="wrap">
			<h1>Header Footer Builder</h1>
			<p><a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=' . EHF_CPT::POST_TYPE ) ); ?>" class="button">+ Add New Header/Footer</a></p>
			<form method="post" action="options.php">
				<?php settings_fields( 'ehf_settings_group' ); ?>
				<table class="form-table" role="presentation">
					<?php
					self::render_picker( 'ehf_active_header', 'Active Header', $headers, $active_header );
					self::render_picker( 'ehf_active_footer', 'Active Footer', $footers, $active_footer );
					self::render_sticky_toggle( $sticky_header );
					?>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}
