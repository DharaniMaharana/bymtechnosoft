<?php
/**
 * Plugin Name:       	WP Travel Gutenberg Blocks
 * Description: 		This WP Travel Gutenberg blocks is a free plugin which integrates seamlessly with the WP Travel plugin and help you display your trips and tours packages just the way you want. Along with the above features, this plugin can handle components such as text, images, buttons, and table. You will be able to completely built a new trip page adding different block from the plugin. The add-on comes with the collection of ready-to-use custom blocks which are specially designed for travel agency sites. Built with simplicity in mind, you can select multiple layouts without touching a single line of code.
 * Requires at least: 	5.0
 * Requires PHP:      	7.4
 * Version:         	3.1.0
 * Tested up to: 		6.3
 * Author:           	WP Travel
 * Author URI: 			http://wptravel.io
 * License:          	GPLv3
 * License URI: 		http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       	wp-travel-blocks
 *
 * @package           	create-block
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */


 // Exit if accessed Directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// define( 'WPTRAVEL_BLOCK_VERSION', '1.0.0' );

if( ! class_exists( 'WP_Travel_Blocks' )):
	/**
	 * 
	 * Main wptravel_block_Class
	 * 
	 * @since 1.0.0
	 */

	 final class WP_Travel_Blocks {

		/**
		 * Version
		 * 
		 * @var string 
		 */
		public $version = '1.0.0';

		/**
		 * The single instance of the class
		 * 
		 * @var WP Tarvel Block Extend
		 * @since 1.0.0
		 */
		protected static $instance = null;

		/**
		 * 
		 * Main WP Travel Block Extend Instance
		 * Ensure only one instance of WP Travel Block Extend Instance is loaded or can be loaded 
		 * 
		 * @since 1.0.0
		 * @static
		 * @return Main instance
		 * 
		 */
		public static function instance(){
			if( is_null( self::$instance ) ){
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * 
		 * WP Travel Block Extend - constructor
		 * 
		 * @since 1.0.0
		 */
		public function __construct(){

			add_action( 'wp_enqueue_scripts', array( $this, 'block_scripts' ) );

			add_action( 'enqueue_block_editor_assets', array( $this, 'block_editor_scripts'), 10, 2 );
			add_filter( 'block_categories_all' , function( $categories ) {
				// Adding a new category.
				$categories[] = array(
					'slug'  => 'wp-travel-blocks',
					'title' => esc_html__( 'WP Travel Blocks', 'wp-travel-blocks' )
				);	
				$categories[] = array(
					'slug'  => 'wp-travel-single-trip-blocks',
					'title' => esc_html__( 'WP Travel Single Trip Blocks', 'wp-travel-blocks' )
				);		
				return $categories;
			} );


			add_action( 'init', array( $this, 'block_init' ) );

			$this->wptravel_block_include_block_render_callback();

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		}


		
		public function admin_scripts(){

			wp_enqueue_style( 
				'wp-travel-admin-demo-style', 
				plugin_dir_url( __FILE__ ) . 'assets/css/admin-style.css', 
			);

			wp_enqueue_script( 
				'wp-travel-admin-demo-script', 
				plugin_dir_url( __FILE__ ) . 'build/demo/index.js' , 
				array( 'jquery' ), 
				'', 
				true 
			);
	
		}
		
		public function block_editor_scripts(){

			wp_enqueue_style( 
				'wp-travel-blocks-editor-style', 
				plugin_dir_url( __FILE__ ) . '/assets/css/editor-style.css', 
			);
	
		}

		public function block_scripts(){
			wp_enqueue_script( 'theia-sticky-js', plugin_dir_url( __FILE__ ) . 'assets/js/theia-sticky-sidebar.js' , array( 'jquery' ), '', true );
			wp_enqueue_script( 'wp-travel-blocks-custom-js', plugin_dir_url( __FILE__ ) . 'assets/js/custom.js' , array( 'jquery' ), '', true );
		}

		public function block_init() {

			wp_register_script(
				'wp-travel-blocks-slider-script', 
				plugin_dir_url( __FILE__ ) . 'assets/js/slider-front.js', 
				array(), 
				'',
				true
			);

			// register_block_type( __DIR__ . '/build/slider' );
			register_block_type( __DIR__ . '/build/slider', array(
				'render_callback' => 'wptravel_block_slider_render'
			) );
			register_block_type( __DIR__ . '/build/slides' );

			register_block_type( __DIR__ . '/build/trip-search', array(
				'render_callback' => 'wptravel_block_trip_search_render'
			) );

			register_block_type( __DIR__ . '/build/trip-list', array(
				'render_callback' => 'wptravel_block_trip_list_render'
			) );

			register_block_type( __DIR__ . '/build/trip-calendar', array(
				'render_callback' => 'wptravel_block_trip_calendar_render'
			) );

			register_block_type( __DIR__ . '/build/trip-categories', array(
				'render_callback' => 'wptravel_block_trip_category_render'
			) );

			register_block_type( __DIR__ . '/build/trip-code', array(
				'render_callback' => 'wptravel_block_trip_code_render',
			) );

			register_block_type( __DIR__ . '/build/trip-duration', array(
				'render_callback' => 'wptravel_block_trip_duration_render'
			) );

			register_block_type( __DIR__ . '/build/trip-enquiry', array(
				'render_callback' => 'wptravel_block_trip_enquiry_render'
			) );

			register_block_type( __DIR__ . '/build/trip-excludes', array(
				'render_callback' => 'wptravel_block_trip_excludes_render'
			) );

			register_block_type( __DIR__ . '/build/trip-facts', array(
				'render_callback' => 'wptravel_block_trip_facts_render'
			) );

			register_block_type( __DIR__ . '/build/trip-faqs', array(
				'render_callback' => 'wptravel_block_trip_faqs_render'
			) );

			register_block_type( __DIR__ . '/build/trip-filters', array(
				'render_callback' => 'wptravel_block_trip_filters_render'
			) );

			register_block_type( __DIR__ . '/build/trip-gallery', array(
				'render_callback' => 'wptravel_block_trip_gallery_render'
			) );

			register_block_type( __DIR__ . '/build/trip-group-size', array(
				'render_callback' => 'wptravel_block_trip_group_size_render'
			) );

			register_block_type( __DIR__ . '/build/trip-includes', array(
				'render_callback' => 'wptravel_block_trip_includes_render'
			) );

			register_block_type( __DIR__ . '/build/trip-map', array(
				'render_callback' => 'wptravel_block_trip_map_render'
			) );

			register_block_type( __DIR__ . '/build/trip-outline', array(
				'render_callback' => 'wptravel_block_trip_outline_render'
			) );

			register_block_type( __DIR__ . '/build/trip-overview', array(
				'render_callback' => 'wptravel_block_trip_overview_render'
			) );

			register_block_type( __DIR__ . '/build/trip-price', array(
				'render_callback' => 'wptravel_block_trip_price_render'
			) );

			register_block_type( __DIR__ . '/build/trip-rating', array(
				'render_callback' => 'wptravel_block_trip_rating_render'
			) );

			register_block_type( __DIR__ . '/build/trip-review', array(
				'render_callback' => 'wptravel_block_trip_review_render'
			) );

			register_block_type( __DIR__ . '/build/trip-review-list', array(
				'render_callback' => 'wptravel_block_trip_review_list_render'
			) );

			register_block_type( __DIR__ . '/build/trip-tabs', array(
				'render_callback' => 'wptravel_block_trip_tabs_render'
			) );

			register_block_type( __DIR__ . '/build/trip-wishlists', array(
				'render_callback' => 'wptravel_block_trip_wishlists_render'
			) );

			register_block_type( __DIR__ . '/build/trip-sale', array(
				'render_callback' => 'wptravel_block_trip_sale_render'
			) );

			register_block_type( __DIR__ . '/build/trip-departure', array(
				'render_callback' => 'wptravel_block_trip_departure_render'
			) );

			register_block_type( __DIR__ . '/build/trip-downloads', array(
				'render_callback' => 'wptravel_block_trip_downloads_render'
			) );

			register_block_type( __DIR__ . '/build/breadcrumb', array(
				'render_callback' => 'wptravel_block_breadcrumb_render'
			) );

			register_block_type( __DIR__ . '/build/trip-slider', array(
				'render_callback' => 'wptravel_block_trip_slider_render'
			) );

			register_block_type( __DIR__ . '/build/trip-button', array(
				'render_callback' => 'wptravel_block_trip_button_render'
			) );

			register_block_type( __DIR__ . '/build/trip-featured-category', array(
				'render_callback' => 'wptravel_block_trip_featured_category_render'
			) );

			register_block_type( __DIR__ . '/build/icon-picker' );

		}

		public function wptravel_block_include_block_render_callback(){

			include sprintf( '%s/inc/breadcrumb-class.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/class-rest.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/class-templates.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/importer/importer.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/demo.php', dirname( __FILE__ ) );

			//render block 
			include sprintf( '%s/inc/block-render/trip-search.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/block-render/trip-list.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/block-render/trip-calendar.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/block-render/trip-category.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/block-render/trip-code.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/block-render/trip-duration.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/block-render/trip-enquiry.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/block-render/trip-excludes.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/block-render/trip-facts.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/block-render/trip-faqs.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/block-render/trip-filters.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/block-render/trip-gallery.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/block-render/trip-group-size.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/block-render/trip-includes.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/block-render/trip-map.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/block-render/trip-outline.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/block-render/trip-overview.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/block-render/trip-price.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/block-render/trip-rating.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/block-render/trip-review.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/block-render/trip-review-list.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/block-render/trip-tabs.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/block-render/trip-wishlists.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/block-render/trip-sale.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/block-render/trip-departure.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/block-render/trip-downloads.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/block-render/breadcrumb.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/block-render/trip-slider.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/block-render/trip-button.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/block-render/slider.php', dirname( __FILE__ ) );
			include sprintf( '%s/inc/block-render/trip-featured-category.php', dirname( __FILE__ ) );

		}
	}

endif;

/**
 * 
 * Main instance of WP Travel Block Extend
 * 
 * @since 1.0.0
 */
function wptravel_block(){
	return WP_Travel_Blocks::instance();
}

wptravel_block();
