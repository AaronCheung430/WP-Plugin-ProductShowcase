<?php

/**
 * Metabox for the Products custom post type
 *
 * @package    	Product_Showcase
 * @link        https://www.chii.com.hk
 * Author:      Union Enterprises
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */


function product_showcase_products_metabox() {
    new Product_Showcase_Products();
}

if ( is_admin() ) {
    add_action( 'load-post.php', 'product_showcase_products_metabox' );
    add_action( 'load-post-new.php', 'product_showcase_products_metabox' );
}

class Product_Showcase_Products {

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );
	}

	public function add_meta_box( $post_type ) {
        global $post;
		add_meta_box(
			'st_employees_metabox'
			,__( 'Product info', 'sydney_toolbox' )
			,array( $this, 'render_meta_box_content' )
			,'products'
			,'advanced'
			,'high'
		);
	}

	public function save( $post_id ) {
	
		if ( ! isset( $_POST['product_showcase_products_nonce'] ) )
			return $post_id;

		$nonce = $_POST['product_showcase_products_nonce'];

		if ( ! wp_verify_nonce( $nonce, 'product_showcase_products' ) )
			return $post_id;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $post_id;

		if ( 'products' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) )
				return $post_id;
	
		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) )
				return $post_id;
		}


		$subtitle 	= isset( $_POST['subtitle'] ) ? sanitize_text_field($_POST['subtitle']) : false;
		$Ref1 	= isset( $_POST['Ref1'] ) ? sanitize_text_field($_POST['Ref1']) : false;
		$Ref2 	= isset( $_POST['Ref2'] ) ? sanitize_text_field($_POST['Ref2']) : false;
		$google 	= isset( $_POST['sydney_toolbox_emp_google'] ) ? esc_url_raw($_POST['sydney_toolbox_emp_google']) : false;
		$link 	= isset( $_POST['sydney_toolbox_emp_link'] ) ? esc_url_raw($_POST['sydney_toolbox_emp_link']) : false;
		
		update_post_meta( $post_id, 'wpcf-subtitle', $subtitle );
		update_post_meta( $post_id, 'wpcf-Ref1', $Ref1 );
		update_post_meta( $post_id, 'wpcf-Ref2', $Ref2 );
		update_post_meta( $post_id, 'wpcf-google-plus', $google );
		update_post_meta( $post_id, 'wpcf-custom-link', $link );
	}

	public function render_meta_box_content( $post ) {
		wp_nonce_field( 'product_showcase_products', 'product_showcase_products_nonce' );

		$subtitle = get_post_meta( $post->ID, 'wpcf-subtitle', true );
		$Ref1 = get_post_meta( $post->ID, 'wpcf-Ref1', true );
		$Ref2  = get_post_meta( $post->ID, 'wpcf-Ref2', true );
		$google   = get_post_meta( $post->ID, 'wpcf-google-plus', true );
		$link     = get_post_meta( $post->ID, 'wpcf-custom-link', true );

	?>
		<p><strong><label for="subtitle"><?php _e( 'Subtitle', 'sydney_toolbox' ); ?></label></strong></p>
		<p><input type="text" class="widefat" id="subtitle" name="subtitle" value="<?php echo esc_html($subtitle); ?>"></p>	
		<p><strong><label for="Ref1"><?php _e( 'Reference #1', 'sydney_toolbox' ); ?></label></strong></p>
		<p><input type="text" class="widefat" id="Ref1" name="Ref1" value="<?php echo esc_html($Ref1); ?>"></p>				
		<p><strong><label for="Ref2"><?php _e( 'Reference #2', 'sydney_toolbox' ); ?></label></strong></p>
		<p><input type="text" class="widefat" id="Ref2" name="Ref2" value="<?php echo esc_html($Ref2); ?>"></p>
<!--		<p><strong><label for="sydney_toolbox_emp_google"><?php _e( 'Employee Google', 'sydney_toolbox' ); ?></label></strong></p>
		<p><input type="text" class="widefat" id="sydney_toolbox_emp_google" name="sydney_toolbox_emp_google" value="<?php echo esc_url($google); ?>"></p>
		<p><strong><label for="sydney_toolbox_emp_link"><?php _e( 'Employee Link', 'sydney_toolbox' ); ?></label></strong></p>
		<p><em><?php _e('Add a link here if you want the employee name to link somewhere.', 'sdyney_toolbox'); ?></em></p>
		<p><input type="text" class="widefat" id="sydney_toolbox_emp_link" name="sydney_toolbox_emp_link" value="<?php echo esc_url($link); ?>"></p>
-->
	<?php
	}
}
