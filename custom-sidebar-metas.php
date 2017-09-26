<?php

/**
 * Custom Sidebar Meta Box
 * Metabox for adding custom sidebar special to the page.
 *
 * @author   Nivo Themes
 * @since    1.0
 * @package  nivoshop
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CustomSidebarMeta {

	public function __construct() {

		if ( is_admin() ) {
			add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
		}

	}

	public function init_metabox() {

		add_action( 'add_meta_boxes',        array( $this, 'add_metabox' ), 10, 2 );
		add_action( 'save_post',             array( $this, 'save_metabox' ), 10, 2 );

	}

	public function add_metabox() {
		add_meta_box(
			'nivoshop-custom-sidebar',
			__( 'Bileşen Alanı Yönetimi', 'nivoshop' ),
			array( $this, 'nivo_customsidebar_metabox' ),
			array( 'page', 'product' ),
			'side',
			'high'
		);
	}

	public function nivo_customsidebar_metabox( $post ) {

		// Add nonce for security and authentication.
		wp_nonce_field( 'nivoshop_showsidebar_nonce_action', 'nivoshop_showsidebar_nonce' );

		// Retrieve an existing value from the database.
		$_showsidebar = get_post_meta( $post->ID, '_showsidebar', true );
		$_sidebarposition = get_post_meta( $post->ID, '_sidebarposition', true );
		
		// Set default values.
		if( empty( $_showsidebar ) ) $_showsidebar = '';

		// Form fields.
		echo '<table class="form-table">';

		echo '	<tr>';
		echo '		<th style="padding-bottom: 0 !important;"><label for="_showsidebar" class="_showsidebar_label">' . __( 'İçeriğe Özel Bileşen Alanı', 'nivoshop' ) . '</label></th>';
		echo '	</tr>';
		echo '	<tr>';
		echo '		<td style="padding-left:0">';
		echo '			<input type="radio" name="_showsidebar" class="_showsidebar_field" value="show" ' . checked( $_showsidebar, 'show', false ) . '> ' . __( 'Etkinleştir', 'nivoshop' );
		echo '			<input type="radio" name="_showsidebar" class="_showsidebar_field" value="hide" ' . checked( $_showsidebar, 'hide', false ) . '> ' . __( 'Etkinleştirme', 'nivoshop' );
		echo '		</td>';
		echo '	</tr>';

		echo '	<tr>';
		echo '		<th style="padding-bottom: 0 !important;"><label for="_sidebarposition" class="_sidebarposition_label">' . __( 'Bileşen Alanı Konumu', 'nivoshop' ) . '</label></th>';
		echo '	</tr>';
		echo '	<tr>';
		echo '		<td style="padding: 0 !important;">';
		echo '			<select name="_sidebarposition">';
		echo '				<option value="left" ' . selected( $_sidebarposition, 'left', false ) . '> ' . __( 'Bileşen Alanı Solda', 'nivoshop' ) . '</option>';
		echo '				<option value="right" ' . selected( $_sidebarposition, 'right', false ) . '> ' . __( 'Bileşen Alanı Sağda', 'nivoshop' ) . '</option>';
		echo '			</select>';		
		echo '		</td>';
		echo '	</tr>';

		echo '</table>';

	}

	public function save_metabox( $post_id, $post ) {
		// Add nonce for security and authentication.
		$nonce_name   = isset( $_POST['nivoshop_showsidebar_nonce'] ) ? $_POST['nivoshop_showsidebar_nonce'] : '';
		$nonce_action = 'nivoshop_showsidebar_nonce_action';

		// Check if a nonce is set.
		if ( ! isset( $nonce_name ) )
			return;

		// Check if a nonce is valid.
		if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) )
			return;
		
		// Check if the user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;

		// Check if it's not an autosave.
		if ( wp_is_post_autosave( $post_id ) )
			return;

		// Check if it's not a revision.
		if ( wp_is_post_revision( $post_id ) )
			return;		

		// Sanitize input.
		$_showsidebar_new = isset( $_POST[ '_showsidebar' ] ) ? sanitize_text_field( $_POST[ '_showsidebar' ] ) : '';
		$_sidebarposition_new = isset( $_POST[ '_sidebarposition' ] ) ? sanitize_text_field( $_POST[ '_sidebarposition' ] ) : '';

		// Update the meta field in the database.
		update_post_meta( $post_id, '_showsidebar',		$_showsidebar_new );
		update_post_meta( $post_id, '_sidebarposition',	$_sidebarposition_new );
	}
}

new CustomSidebarMeta;
