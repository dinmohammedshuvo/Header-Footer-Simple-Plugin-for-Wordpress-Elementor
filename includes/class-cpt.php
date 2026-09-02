<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The "Header/Footer" custom post type. Each entry is one Elementor-editable
 * design, tagged as either a header or a footer via the _ehf_type meta.
 */
class EHF_CPT {

	const POST_TYPE = 'ehf_template';

	public static function register() {
		$labels = array(
			'name'               => 'Headers & Footers',
			'singular_name'      => 'Header/Footer',
			'add_new_item'       => 'Add New Header/Footer',
			'edit_item'          => 'Edit Header/Footer',
			'new_item'           => 'New Header/Footer',
			'all_items'          => 'All Headers & Footers',
			'search_items'       => 'Search Headers & Footers',
			'not_found'          => 'No headers or footers found',
			'not_found_in_trash' => 'No headers or footers found in Trash',
		);

		register_post_type(
			self::POST_TYPE,
			array(
				'labels'              => $labels,
				'public'              => true,
				'publicly_queryable'  => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => false,
				'exclude_from_search' => true,
				'menu_icon'           => 'dashicons-align-center',
				'supports'            => array( 'title', 'editor', 'thumbnail' ),
				'has_archive'         => false,
				'rewrite'             => false,
				'capability_type'     => 'post',
			)
		);

		add_post_type_support( self::POST_TYPE, 'elementor' );
	}

	public static function register_meta_box() {
		add_meta_box( 'ehf_type', 'Header or Footer', array( __CLASS__, 'render_meta_box' ), self::POST_TYPE, 'side', 'high' );
	}

	public static function render_meta_box( $post ) {
		wp_nonce_field( 'ehf_save_type', 'ehf_type_nonce' );
		$type = get_post_meta( $post->ID, '_ehf_type', true );
		if ( ! $type ) {
			$type = 'header';
		}
		?>
		<p>
			<label><input type="radio" name="ehf_type" value="header" <?php checked( $type, 'header' ); ?> /> Header</label><br />
			<label><input type="radio" name="ehf_type" value="footer" <?php checked( $type, 'footer' ); ?> /> Footer</label>
		</p>
		<?php
	}

	public static function save_meta_box( $post_id ) {
		if ( ! isset( $_POST['ehf_type_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ehf_type_nonce'] ) ), 'ehf_save_type' ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		if ( isset( $_POST['ehf_type'] ) && in_array( $_POST['ehf_type'], array( 'header', 'footer' ), true ) ) {
			update_post_meta( $post_id, '_ehf_type', sanitize_key( $_POST['ehf_type'] ) );
		}
	}

	public static function redirect_single_template() {
		if ( ! is_singular( self::POST_TYPE ) ) {
			return;
		}
		if ( isset( $_GET['elementor-preview'] ) || is_admin() ) {
			return;
		}
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
}
