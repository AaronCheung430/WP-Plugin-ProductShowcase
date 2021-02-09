<?php

/**
 *
 * @link              http://athemes.com
 * @since             1.0
 * @package           Product_Showcase
 *
 * @wordpress-plugin
 * Plugin Name:       Product Showcase
 * Plugin URI:        http://www.chii.com.hk
 * Description:       Registers custom post types and custom fields for the Sydney theme
 * Version:           1.2
 * Author:            Union Enterpise
 * Author URI:        http://www.chii.com.hk
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       product-showcase
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/* Filter the single_template with our custom function*/
add_filter('single_template', 'my_custom_template' );
add_filter('archive_template', 'my_custom_template' );
add_filter('search_template', 'my_search_template' );

function my_search_template($template){
	if( isset($_REQUEST['search']) == 'advanced' ) {
		return plugin_dir_path( __FILE__ ) . '/advanced-search-result.php';
	}
	return $template;
}

function my_custom_template($single) {

	global $post;

	/* Checks for single template by post type */
	if ( $post->post_type == 'products' ) {
		if (is_tax()) {
			$slug = 'taxonomy-headnotes';
		} else {
			$slug = is_archive() ? 'archive' : 'single';
		}
		if ( file_exists( plugin_dir_path( __FILE__ ) . '/'.$slug.'-products.php' ) ) {
			return plugin_dir_path( __FILE__ ) . '/'.$slug.'-products.php';
		}
	}

	return $single;
}

/**
 * Set up and initialize
 */
class Product_Showcase {
	
	private static $instance;

	/**
	 * Actions setup
	 */
	public function __construct() {

		add_action( 'plugins_loaded', array( $this, 'constants' ), 2 );
		add_action( 'plugins_loaded', array( $this, 'i18n' ), 3 );
		add_action( 'plugins_loaded', array( $this, 'includes' ), 4 );
		add_action( 'admin_notices', array( $this, 'admin_notice' ), 4 );

		//SVG styles
		add_action( 'wp_head', array( $this, 'svg_styles' ) );
		
		//Elementor actions
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'elementor_includes' ), 4 );
		add_action( 'elementor/init', array( $this, 'elementor_category' ), 4 );
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'scripts' ), 4 );
		//add_action( 'init', array( $this, 'tags_archives' ) );

		//hook into the init action and call create_topics_nonhierarchical_taxonomy when it fires
		add_action( 'init', 'create_topics_nonhierarchical_taxonomy_headnotes', 0 );
		add_action( 'init', 'create_topics_nonhierarchical_taxonomy_heartnotes', 0 );
		add_action( 'init', 'create_topics_nonhierarchical_taxonomy_basenotes', 0 );
	}
	

	/**
	 * Constants
	 */
	function constants() {
		define( 'PS_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'PS_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );
	}

	/**
	 * Includes
	 */
	function includes() {

		if ( defined( 'SITEORIGIN_PANELS_VERSION' ) ) {
			require_once( PS_DIR . 'inc/post-type-products.php' );
			require_once( PS_DIR . 'inc/metaboxes/products-metabox.php' );	

		}

		/**
		 * Demo content setup
		 * 
		 * @since 1.07
		 */
		if ( !$this->is_pro() ) {
			require_once( PS_DIR . 'demo-content/setup.php' );
		}
	}

	function elementor_includes() {
		if ( !version_compare(PHP_VERSION, '5.4', '<=') ) {			

			if ( $this->is_pro() ) {
				require_once( PS_DIR . 'inc/elementor/block-employee.php' );
			}
		}
	}

	function elementor_category() {
		if ( !version_compare(PHP_VERSION, '5.4', '<=') ) {
			\Elementor\Plugin::$instance->elements_manager->add_category( 
				'sydney-elements',
				[
					'title' => __( 'Sydney Elements', 'sydney-toolbox' ),
					'icon' => 'fa fa-plug',
				],
				2
			);
		}
	} 

	static function install() {
		if ( version_compare(PHP_VERSION, '5.4', '<=') ) {
			wp_die( __( 'Sydney Toolbox requires PHP 5.4. Please contact your host to upgrade your PHP. The plugin was <strong>not</strong> activated.', 'sydney-toolbox' ) );
		};
	}	

	function i18n() {
		load_plugin_textdomain( 'sydney-toolbox', false, 'sydney-toolbox/languages' );
	}

	function admin_notice() {
	}

	function svg_styles() {
		?>
			<style>
				.sydney-svg-icon {
					display: inline-block;
					width: 16px;
					height: 16px;
					vertical-align: middle;
					line-height: 1;
				}
				.team-item .team-social li .sydney-svg-icon {
					fill: #fff;
				}
				.team-item .team-social li:hover .sydney-svg-icon {
					fill: #000;
				}
				.team_hover_edits .team-social li a .sydney-svg-icon {
					fill: #000;
				}
				.team_hover_edits .team-social li:hover a .sydney-svg-icon {
					fill: #fff;
				}				
			</style>
		<?php
	}

	function scripts() {

		$forked_owl = get_theme_mod( 'forked_owl_carousel', false );
		if ( $forked_owl ) {
			wp_enqueue_script( 'st-carousel', PS_URI . 'js/main.js', array(), '20200504', true );
		} else {
			wp_enqueue_script( 'st-carousel', PS_URI . 'js/main-legacy.js', array(), '20200504', true );
		}
	}

	public static function is_pro() {
		$theme  = wp_get_theme();
		$parent = wp_get_theme()->parent();
		if ( ( $theme != 'Sydney Pro' ) && ( $parent != 'Sydney Pro') ) {
			return false;
	    } else {
	    	return true;
	    }		
	}

	public static function get_instance() {

		if ( !self::$instance )
			self::$instance = new self;

		return self::$instance;
	}
}



function create_topics_nonhierarchical_taxonomy_headnotes() {
	return create_topics_nonhierarchical_taxonomy('headnotes', 'Headnotes');
}
function create_topics_nonhierarchical_taxonomy_heartnotes() {
	return create_topics_nonhierarchical_taxonomy('heartnotes', 'Heartnotes');
}
function create_topics_nonhierarchical_taxonomy_basenotes() {
	return create_topics_nonhierarchical_taxonomy('basenotes', 'Basenotes');
}

function create_topics_nonhierarchical_taxonomy($taxonomy, $name) {

	// Labels part for the GUI
	$labels = array(
	'name' => _x( $name, 'taxonomy general name' ),
	'singular_name' => _x( $name, 'taxonomy singular name' ),
	'search_items' =>  __( 'Search '.$name ),
	'popular_items' => __( 'Popular '.$name ),
	'all_items' => __( 'All '.$name ),
	'parent_item' => null,
	'parent_item_colon' => null,
	'edit_item' => __( 'Edit '.$name ), 
	'update_item' => __( 'Update '.$name ),
	'add_new_item' => __( 'Add New '.$name ),
	'new_item_name' => __( 'New '.$name.' Name' ),
	'separate_items_with_commas' => __( 'Separate'.strtolower($name).'with commas' ),
	'add_or_remove_items' => __( 'Add or remove '.strtolower($name) ),
	'choose_from_most_used' => __( 'Choose from the most used '.strtolower($name) ),
	'menu_name' => __( $name ),
	); 
	
	// Now register the non-hierarchical taxonomy like tag
	
	register_taxonomy($taxonomy,'products',array(
	'hierarchical' => false,
	'labels' => $labels,
	'show_ui' => true,
	'show_admin_column' => true,
	'update_count_callback' => '_update_post_term_count',
	'query_var' => true,
	'rewrite' => true,
	));

	register_taxonomy_for_object_type($taxonomy, 'products');
}

function product_showcase_plugin() {
	return Product_Showcase::get_instance();
}
add_action('plugins_loaded', 'product_showcase_plugin', 1);

//Does not activate the plugin on PHP less than 5.4
//register_activation_hook( __FILE__, array( 'Product_Showcase', 'install' ) );

register_activation_hook( __FILE__, 'mw_plugin_activation' );

function mw_plugin_activation() {
	require_once( trailingslashit( plugin_dir_path( __FILE__ ) )	 . 'inc/post-type-products.php' );
	product_showcase_register_products();
	create_topics_nonhierarchical_taxonomy_headnotes();
	create_topics_nonhierarchical_taxonomy_heartnotes();
	create_topics_nonhierarchical_taxonomy_basenotes();
    flush_rewrite_rules();
}

// Register and load the widget
function wpb_load_widget() {
	register_widget( 'Menu_Products' );
	register_widget( 'Search_Products_Bar' );
	register_widget( 'Search_Products_Bar_Advanced' );
}

add_action( 'widgets_init', 'wpb_load_widget' );

// Creating the widget 
class Menu_Products extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'show_product_widget', 'description' => __( 'Display your products in a grid.', 'sydney') );
       	parent::__construct(false, $name = __('Show Products', 'sydney'), $widget_ops);
		$this->alt_option_name = 'show_product_widget';
  	}

  	public function widget( $args, $instance ) {

		?>

		<style type="text/css">
		.img-box {
			position: relative; width: 100%; padding-top: 100%; background-size: cover; background-position: center; margin-bottom: 0px;
		}
		.img-content {
			position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); color: #fff; display: flex; align-items: center; opacity: 0; transition: 0.5s;
		}
		.img-box:hover .img-content {
			opacity: 1;
		}
		.img-container {
			display: flex;
			flex-wrap: wrap;
		}
		.img-container > a {
			width: 50%;
			overflow: hidden;
		}
		@media only screen and (max-width: 767px) {
			.img-container > a{
				width:100%;
			}
		}
		</style>

		<?php
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$terms = apply_filters( 'search_terms', empty( $instance['terms'] ) ? '' : $instance['terms'], $instance, $this->id_base );
		$notes = empty( $instance['notes'] ) ? '' : $instance['notes'];
		$show_text = isset( $instance['show_text'] ) ? $instance['show_text'] : false;
		$mode = empty( $instance['mode'] ) ? 1 : $instance['mode'];
		$headnotes = empty( $instance['headnotes'] ) ? '' : $instance['headnotes'];
		$heartnotes = empty( $instance['heartnotes'] ) ? '' : $instance['heartnotes'];
		$basenotes = empty( $instance['basenotes'] ) ? '' : $instance['basenotes'];
		$number 		= ( ! empty( $instance['number'] ) ) ? intval( $instance['number'] ) : -1;
		if ( ! $number )
			$number = -1;	

    	echo $args['before_widget'];

    	if ( ! empty( $title ) ) { echo $args['before_title'] . $title . $args['after_title']; }


    	$options = array(
     		'posts'         => $number,
     		'post_type'     => 'products',
     		'include'       => $includes,
     		'filter'        => $show_filter,
     		'show_all_text' => ! empty( $show_all_text ) ? $show_all_text : __('Show all', 'sydney')
    	);

		$output = ''; //Start output
		//$output .= '<div class="project-wrap">';


		//Build the layout
		$output .= '<div>';
		$output .= '<div class="img-container">';


		if ($mode == 2) {
			$options['product_name'] = $terms;
			$options['tax_query'] = array(
				array(
					'relation' => 'AND'
				)
			);
			if (!empty($headnotes)) {
				array_push($options['tax_query'][0],
				[
					'taxonomy' => 'headnotes',
					'field' => 'name',
					'terms' => $headnotes,
				]);
			}
			if (!empty($heartnotes)) {
				array_push($options['tax_query'][0],
				[
					'taxonomy' => 'heartnotes',
					'field' => 'name',
					'terms' => $heartnotes,
				]);
			}
			if (!empty($basenotes)) {
				array_push($options['tax_query'][0],
				[
					'taxonomy' => 'basenotes',
					'field' => 'name',
					'terms' => $headnotes,
				]);
			}
		} else {
			$options['product_name'] = '';
			if (empty($terms)) {
				$options['tax_query'] = array();
			} elseif ($notes == 'headnotes') {
				$options['tax_query'] = array(
					array(
						'taxonomy' => 'headnotes',
						'field' => 'name',
						'terms' => explode(',', $terms)
					)
				);

			} elseif ($notes == 'heartnotes') {
				$options['tax_query'] = array(
					array(
						'taxonomy' => 'heartnotes',
						'field' => 'name',
						'terms' => explode(',', $terms)
					)
				);

			} elseif ($notes == 'basenotes') {
				$options['tax_query'] = array(
					array(
						'taxonomy' => 'basenotes',
						'field' => 'name',
						'terms' => explode(',', $terms)
					)
				);
						
			} else {
				$options['tax_query'] = array(
					array(
						// 'taxonomy' => 'headnotes',
						// 'field'    => 'name',
						// 'terms'    => explode(',', $title)
						'relation' => 'OR',
						//$newtitle = explode(',', $title),
						[
							'taxonomy' => 'headnotes',
							'field' => 'name',
							'terms' => explode(',', $terms),
						],
						[
							'taxonomy' => 'heartnotes',
							'field' => 'name',
							'terms' => explode(',', $terms),
						],
						[
							'taxonomy' => 'basenotes',
							'field' => 'name',
							'terms' => explode(',', $terms),
						]
					)
				);
			}
		}

		// if ($notes == 'headnotes') {
		// 	//$options['tax_query'] = array();
		// 	echo 'hello';
		
		// } else {
		// 	echo 'hello';

		// }

    	$the_query = new WP_Query( array (
     		'post_type' => $options['post_type'],
     		'posts_per_page' => $options['posts'],
	 		'category_name' => $options['include'],
			'tax_query' => $options['tax_query'],
			's' => $options['product_name']

    	) );

    	while ( $the_query->have_posts() ):
       		$the_query->the_post();
       		global $post;
       		$id = $post->ID;
       		$termsArray = get_the_terms( $id, 'category' );
	   		$termsString = "";

			if ( $termsArray) {
				foreach ( $termsArray as $term ) {
				$termsString .= $term->slug.' ';
				}
			}
	   
	  	 	$headArray = get_the_terms( $id, 'headnotes' );
	   		$headString = "";

			if ( $headArray) {
				foreach ( $headArray as $term ) {
					$headString .= $term->name.', ';
				}
			}
			$headString = substr ($headString, 0, -2);
			$heartArray = get_the_terms( $id, 'heartnotes' );
			$heartString = "";
	
			if ( $heartArray) {
				foreach ( $heartArray as $term ) {
					$heartString .= $term->name.', ';
				}
			}
			$heartString = substr ($heartString, 0, -2);

			$baseArray = get_the_terms( $id, 'basenotes' );
			$baseString = "";
	
			if ( $baseArray) {
				foreach ( $baseArray as $term ) {
					$baseString .= $term->name.', ';
				}
			}
		  
			$baseString = substr ($baseString, 0, -2);

			$project_title = '<div class="project-title-wrap">';
			$project_title .= '<div class="project-title">';
			$project_title .= '<span>'.get_the_title($post->ID).'</span>';
			$project_title .= '</div>';
			$project_title .= '</div>';


       		if ( has_post_thumbnail() ) {
           		$project_url = get_post_meta( get_the_ID(), 'wpcf-project-link', true );
           		if ( $project_url ) :
					$output .= '<div class="project-item item isotope-item ' . $termsString . '">';
					$output .= '<a class="project-pop-wrap" href="' . esc_url($project_url) . '">';
					$output .= '<div class="project-pop"></div>';
					$output .= ($show_text == 1) ? $project_title : '';
					$output .= '</a>';
					$output .= '<a href="' . esc_url($project_url) . '">';
					$output .= get_the_post_thumbnail($post->ID,'sydney-medium-thumb');
					$output .= '</a>';
					$output .= '</div>';
		   		else :
					$output .= '<a href="' . get_the_permalink() . '">';
					$output .= '<div class="img-box" style="background-image: url(\''.get_the_post_thumbnail_url($post->ID,'sydney-medium-thumb').'\');">';
					$output .= '<div class="img-content">';
					$output .= '<div style="width: 100%; text-align: center;">';

					if ($show_text == 0) {
						$output .= '<div style="font-size: 1.3em; letter-spacing: 2px; font-weight: 300;">';
						$output .= get_the_title();
						$subtitle = get_post_meta( get_the_ID(), 'wpcf-subtitle', true );
						if ($subtitle != ''){
							$output .= ' - ' . $subtitle;
						}
						$output .= '</div>';
						$output .= '<br>';
						$output .= '<div style="font-size: 0.8em; font-weight: 300;">';
						$output .= 'Head notes: '.$headString.'<br>';
						$output .= 'Heart notes: '.$heartString.'<br>';
						$output .= 'Base notes: '.$baseString.'</div>';
					}

					$output .= '</div></div></div></a>';
           		endif;
       		}
    	endwhile;
    	wp_reset_postdata();
    	$output .= '</div>';
    	$output .= '</div>';
		echo $output;

    	echo $args['after_widget'];

	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] 			= strip_tags($new_instance['title']);
		$instance['terms'] 			= strip_tags($new_instance['terms']);
		$instance['notes'] 			= strip_tags($new_instance['notes']);
		$instance['number'] 		= strip_tags($new_instance['number']);		
		$instance['show_text'] = is_null( $new_instance['show_text'] ) ? 0 : 1;

    	return $instance;
	}

	function form($instance) {
		$title     		 = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$terms     		 = isset( $instance['terms'] ) ? esc_attr( $instance['terms'] ) : '';
		$notes     		 = isset( $instance['notes'] ) ? esc_attr( $instance['notes'] ) : '';
		$number    		 = isset( $instance['number'] ) ? intval( $instance['number'] ) : -1;
		$includes      = isset( $instance['includes'] ) ? esc_attr($instance['includes']) : '';
		$show_filter   = isset( $instance['show_filter'] ) ? (bool) $instance['show_filter'] : true;
		$show_text = isset( $instance['show_text'] ) ? (bool) $instance['show_text'] : false;
		$show_all_text = isset( $instance['show_all_text'] )  ? esc_html($instance['show_all_text']) : __('Show all', 'sydney');

		?>

		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'sydney'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('terms'); ?>"><?php _e('Search', 'sydney'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('terms'); ?>" name="<?php echo $this->get_field_name('terms'); ?>" type="text" value="<?php echo esc_attr($terms); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('notes'); ?>"><?php _e('Notes', 'sydney'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('notes'); ?>" name="<?php echo $this->get_field_name('notes'); ?>" type="text" value="<?php echo esc_attr($notes); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of products to show (-1 shows all of them):', 'sydney' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $show_text ); ?> id="<?php echo $this->get_field_id( 'show_text' ); ?>" name="<?php echo $this->get_field_name( 'show_text' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_text' ); ?>"><?php _e( 'Show Text?', 'sydney' ); ?></label></p>

		<?php

	}

}

//Second widget created, which is for Search Bar
class Search_Products_Bar extends WP_Widget {

	function __construct() {
		$widget_search = array('classname' => 'search_product_widget', 'description' => __( 'Search Products', 'sydney') );
		parent::__construct(false, $name = __('Search Bar - Products', 'sydney'), $widget_search);
		$this->alt_option_name = 'search_product_widget';

	}
	
	public function widget( $args, $instance ) {
		include(PS_DIR . 'advanced-searchform.php');
	}
			
	// Creating widget Backend 
	public function form( $instance ) {
		$title     		 = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$titleW     		 = isset( $instance['title_w'] ) ? esc_attr( $instance['title_w'] ) : '';

		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title for Search Page', 'sydney'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('title_w'); ?>"><?php _e('Title for Widge', 'sydney'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_w'); ?>" name="<?php echo $this->get_field_name('title_w'); ?>" type="text" value="<?php echo esc_attr($titleW); ?>" /></p>

		<?php	
	}
		
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {	
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['title_w'] = strip_tags($new_instance['title_w']);

		return $instance;

	}

}

//Third widget created, which is for Search Bar Advanced
class Search_Products_Bar_Advanced extends WP_Widget {

	function __construct() {
		$widget_search = array('classname' => 'search_product_widget_advanced', 'description' => __( 'Search Products Advanced', 'sydney') );
		parent::__construct(false, $name = __('Search Bar - Products Advanced', 'sydney'), $widget_search);
		$this->alt_option_name = 'search_product_widget_advanced';

	}
	
	public function widget( $args, $instance ) {
		include(PS_DIR . 'advanced-searchform-advanced.php');
	}
			
	// Creating widget Backend 
	public function form( $instance ) {
		$title     		 = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$titleW     		 = isset( $instance['title_w'] ) ? esc_attr( $instance['title_w'] ) : '';

		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title for Search Page', 'sydney'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('title_w'); ?>"><?php _e('Title for Widge', 'sydney'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_w'); ?>" name="<?php echo $this->get_field_name('title_w'); ?>" type="text" value="<?php echo esc_attr($titleW); ?>" /></p>

		<?php	
	}
		
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {	
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['title_w'] = strip_tags($new_instance['title_w']);

		return $instance;

	}

}

define('CPTPATH',   plugin_dir_path(__FILE__));
define('CPTURL',    plugins_url('', __FILE__));
    
include_once(CPTPATH . '/include/class.cpto.php');
include_once(CPTPATH . '/include/class.functions.php');
  
add_action( 'plugins_loaded', 'cpto_class_load');     
function cpto_class_load() {
    global $CPTO;
    $CPTO   =   new CPTO();
}
				
add_action( 'plugins_loaded', 'cpto_load_textdomain'); 
function cpto_load_textdomain() {
    load_plugin_textdomain('post-types-order', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages');
}
 
add_action('wp_loaded', 'initCPTO' ); 	
function initCPTO() {
	global $CPTO;

    $options = $CPTO->functions->get_options();

    if (is_admin()) {
		if(isset($options['capability']) && !empty($options['capability'])) {
            if( current_user_can($options['capability']) )
                $CPTO->init(); 
            } else if (is_numeric($options['level'])) {
                if ( $CPTO->functions->userdata_get_user_level(true) >= $options['level'] )
                    $CPTO->init();
            } else {
                $CPTO->init();
		}
  	}        
}

?>