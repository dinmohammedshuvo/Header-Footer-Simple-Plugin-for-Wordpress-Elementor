<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Prints the active header (on wp_body_open) and active footer (on wp_footer),
 * rendered through Elementor when the template was built with it.
 */
class EHF_Frontend {

	public static function render_header() {
		self::render( absint( get_option( 'ehf_active_header' ) ), 'ehf-site-header', (bool) get_option( 'ehf_sticky_header' ) );
	}

	public static function render_footer() {
		self::render( absint( get_option( 'ehf_active_footer' ) ), 'ehf-site-footer', false );
	}

	private static function render( $id, $wrap_class, $sticky = false ) {
		if ( ! $id || 'publish' !== get_post_status( $id ) ) {
			return;
		}

		if ( $sticky ) {
			$wrap_class .= ' ehf-is-sticky';
			echo '<style id="ehf-sticky-header-css">.ehf-site-header.ehf-is-sticky{position:-webkit-sticky;position:sticky;top:0;z-index:9999;}</style>';
		}

		echo '<div class="' . esc_attr( $wrap_class ) . '">';

		if ( class_exists( '\Elementor\Plugin' ) ) {
			echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			echo apply_filters( 'the_content', get_post_field( 'post_content', $id ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo '</div>';
	}
}
