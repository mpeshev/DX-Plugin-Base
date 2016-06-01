<?php
/**
 * Plugin Name: DX Plugin Base
 * Description: A plugin framework for building new WordPress plugins reusing the accepted APIs and best practices
 * Plugin URI: http://example.org/
 * Author: nofearinc
 * Author URI: http://devwp.eu/
 * Version: 1.7
 * Text Domain: dx-sample-plugin
 * License: GPL2

 Copyright 2011 mpeshev (email : mpeshev AT devrix DOT com)

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License, version 2, as
 published by the Free Software Foundation.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Get some constants ready for paths when your plugin grows 
 * 
 */
if( !defined( 'DXP_TD' ) ){
	/**
	 * The plugin text_domain
	 */
	define( 'DXP_TD', 'dxbase' );
}


/**
 * 
 * The plugin base class - the root of all WP goods!
 * 
 * @author nofearinc
 * @todo Replace it with Your plugin class name
 */
class DX_Plugin_Base {
	
	/**
	 * Can be get only from ancestry of this class
	 * @var string The plugin version
	 */
	protected $version;

	/**
	 * Can be get only from ancestry of this class
	 * @var string The path to the plugin folder
	 */
	protected $path;
	
	/**
	 * Can be get only from ancestry of this class
	 * @var string The path to the include folder
	 */
	protected $path_include;
	
	/**
	 * Can be get only from ancestry of this class
	 * @var string Url to the plugin folder 
	 */
	protected $url;
	
	/**
	 * Can be get only from ancestry of this class
	 * @var string Url to the assets folder, where are js & css files are.
	 */
	protected $url_assets;

	/**
	 * 
	 * Assign everything as a call from within the constructor
	 */
	public function __construct() {
		$this->setup();
		
		// add script and style calls the WP way 
		// it's a bit confusing as styles are called with a scripts hook
		// @blamenacin - http://make.wordpress.org/core/2011/12/12/use-wp_enqueue_scripts-not-wp_print_styles-to-enqueue-scripts-and-styles-for-the-frontend/
		add_action( 'wp_enqueue_scripts', array( $this, 'dx_add_JS' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'dx_add_CSS' ) );
		
		// add scripts and styles only available in admin
		add_action( 'admin_enqueue_scripts', array( $this, 'dx_add_admin_JS' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'dx_add_admin_CSS' ) );
		// register admin pages for the plugin
		add_action( 'admin_menu', array( $this, 'dx_admin_pages_callback' ) );
		
		// register meta boxes for Pages (could be replicated for posts and custom post types)
		add_action( 'add_meta_boxes', array( $this, 'dx_meta_boxes_callback' ) );
		
		// register save_post hooks for saving the custom fields
		add_action( 'save_post', array( $this, 'dx_save_sample_field' ) );
		
		// Register custom post types and taxonomies
		add_action( 'init', array( $this, 'dx_custom_post_types_callback' ), 5 );
		add_action( 'init', array( $this, 'dx_custom_taxonomies_callback' ), 6 );
		
		// Translation-ready
		add_action( 'plugins_loaded', array( $this, 'dx_add_textdomain' ) );
		
		// Add earlier execution as it needs to occur before admin page display
		add_action( 'admin_init', array( $this, 'dx_register_settings' ), 5 );
		
		// Add a sample shortcode
		add_action( 'init', array( $this, 'dx_sample_shortcode' ) );
		
		// Add a sample widget
		add_action( 'widgets_init', array( $this, 'dx_sample_widget' ) );
		
		/*
		 * TODO:
		 * 		template_redirect
		 */
		
		// Add actions for storing value and fetching URL
		// use the wp_ajax_nopriv_ hook for non-logged users (handle guest actions)
 		add_action( 'wp_ajax_store_ajax_value', array( $this, 'store_ajax_value' ) );
 		add_action( 'wp_ajax_fetch_ajax_url_http', array( $this, 'fetch_ajax_url_http' ) );
		
		//hook to the base page setting page form , and add content in form before and after the elements
		add_action( 'dx_base_page_template_form_before_settings', array( $this, 'base_page_template_form_before' ) );
		add_action( 'dx_base_page_template_form_after_settings', array( $this, 'base_page_template_form_after' ) );
		
		//change the to the base page setting page form submit button text (value)
		add_filter( 'dx_base_page_template_form_submit_button', array( $this, 'base_page_template_form_submit_button' ) );
	}

	/**
	 * Setup the plugin main properties
	 */
	protected function setup() {
		//check all properties, if they are empty assign data accordingly
		if ( empty( $this->version ) ): 
			$this->version			= '1.7';				
		endif;
		if( empty( $this->path ) ): 
			$this->path				= trailingslashit( dirname( __FILE__ ) );				
		endif;
		if( empty( $this->path_include ) ):  
			$this->path_include		= trailingslashit( $this->path . 'inc' );				
		endif;
		if( empty( $this->path_template ) ):
			$this->path_template		= trailingslashit( $this->path . 'template' );				
		endif;
		if( empty( $this->url ) ): 
			$this->url				= trailingslashit( plugins_url( '', __FILE__ ) );				
		endif;
		if( empty( $this->url_assets ) ):
			$this->url_assets		= trailingslashit( $this->url . 'assets' );				
		endif;
	}
		
	/**
	 * Adding the content in form before it elemnts
	 */
	public function base_page_template_form_before() {
		_e( 'Sample example, of adding a custom hook. This will add conternt before form elements.', DXP_TD );
	}
	
	/**
	 * Adding the content in form after it elemnts
	 */
	public function base_page_template_form_after() {
		_e( 'Sample example, of adding a custom hook. This will add conternt after form elements.', DXP_TD );
		echo '<br>';
	}
	
	/**
	 * Callback for the filter the submit button text in base page template
	 * 
	 * @param string $value
	 * @return string The new value of the submit button
	 */
	public function base_page_template_form_submit_button( $value ) {
		return __( 'Save settings', DXP_TD );
	}
	
	/**
	 * 
	 * Adding JavaScript scripts
	 * 
	 * Loading existing scripts from wp-includes or adding custom ones
	 * 
	 */
	public function dx_add_JS() {
		wp_enqueue_script( 'jquery' );
		// load custom JSes and put them in footer
		wp_register_script( 'samplescript', $this->url_assets . 'js/samplescript.js', array('jquery'), '1.0', true );
		wp_enqueue_script( 'samplescript' );
	}
	
	
	/**
	 *
	 * Adding JavaScript scripts for the admin pages only
	 *
	 * Loading existing scripts from wp-includes or adding custom ones
	 *
	 */
	public function dx_add_admin_JS( $hook ) {
		wp_enqueue_script( 'jquery' );
		wp_register_script( 'samplescript-admin', $this->url_assets .  'js/samplescript-admin.js' , array('jquery'), '1.0', true );
		wp_enqueue_script( 'samplescript-admin' );
	}
	
	/**
	 * 
	 * Add CSS styles
	 * 
	 */
	public function dx_add_CSS() {
		wp_register_style( 'samplestyle', $this->url_assets . 'css/samplestyle.css', array(), '1.0', 'screen' );
		wp_enqueue_style( 'samplestyle' );
	}
	
	/**
	 *
	 * Add admin CSS styles - available only on admin
	 *
	 */
	public function dx_add_admin_CSS( $hook ) {
		wp_register_style( 'samplestyle-admin', $this->url_assets .  'css/samplestyle-admin.css', array(), '1.0', 'screen' );
		wp_enqueue_style( 'samplestyle-admin' );
		
		if( 'toplevel_page_dx-plugin-base' === $hook ) {
			wp_register_style('dx_help_page',  $this->url_assets . 'css/help-page.css' );
			wp_enqueue_style('dx_help_page');
		}
	}

	/**
	 * 
	 * Callback for registering pages
	 * 
	 * This demo registers a custom page for the plugin and a subpage
	 *  
	 */
	public function dx_admin_pages_callback() {
		add_menu_page(__( "Plugin Base Admin", DXP_TD ), __( "Plugin Base Admin", DXP_TD ), 'edit_themes', 'dx-plugin-base', array( $this, 'dx_plugin_base' ) );		
		add_submenu_page( 'dx-plugin-base', __( "Base Subpage", DXP_TD ), __( "Base Subpage", DXP_TD ), 'edit_themes', 'dx-base-subpage', array( $this, 'dx_plugin_subpage' ) );
		add_submenu_page( 'dx-plugin-base', __( "Remote Subpage", DXP_TD ), __( "Remote Subpage", DXP_TD ), 'edit_themes', 'dx-remote-subpage', array( $this, 'dx_plugin_side_access_page' ) );
	}
	
	/**
	 * 
	 * The content of the base page
	 * 
	 */
	public function dx_plugin_base() {
		include_once( $this->path_template . 'base-page-template.php' );
	}
	
	public function dx_plugin_side_access_page() {
		include_once( $this->path_template . 'remote-page-template.php' );
	}
	
	/**
	 * 
	 * The content of the subpage 
	 * 
	 * Use some default UI from WordPress guidelines echoed here (the sample above is with a template)
	 * 
	 * @see http://www.onextrapixel.com/2009/07/01/how-to-design-and-style-your-wordpress-plugin-admin-panel/
	 *
	 */
	public function dx_plugin_subpage() {
		echo '<div class="wrap">';
		_e( "<h2>DX Plugin Subpage</h2> ", DXP_TD );
		_e( "I'm a subpage and I know it!", DXP_TD );
		echo '</div>';
	}
	
	/**
	 * 
	 *  Adding right and bottom meta boxes to Pages
	 *   
	 */
	public function dx_meta_boxes_callback() {
		// register side box
		add_meta_box( 
		        'dx_side_meta_box',
		        __( "DX Side Box", DXP_TD ),
		        array( $this, 'dx_side_meta_box' ),
		        'pluginbase', // leave empty quotes as '' if you want it on all custom post add/edit screens
		        'side',
		        'high'
		    );
		    
		// register bottom box
		add_meta_box(
		    	'dx_bottom_meta_box',
		    	__( "DX Bottom Box", DXP_TD ), 
		    	array( $this, 'dx_bottom_meta_box' ),
		    	'' // leave empty quotes as '' if you want it on all custom post add/edit screens or add a post type slug
		    );
	}
	
	/**
	 * 
	 * Init right side meta box here 
	 * @param post $post the post object of the given page 
	 * @param metabox $metabox metabox data
	 */
	public function dx_side_meta_box( $post, $metabox) {
		_e("<p>Side meta content here</p>", DXP_TD);
		
		// Add some test data here - a custom field, that is
		$dx_test_input = '';
		if ( ! empty ( $post ) ) {
			// Read the database record if we've saved that before
			$dx_test_input = get_post_meta( $post->ID, 'dx_test_input', true );
		}
		?>
		<label for="dx-test-input"><?php _e( 'Test Custom Field', DXP_TD ); ?></label>
		<input type="text" id="dx-test-input" name="dx_test_input" value="<?php echo $dx_test_input; ?>" />
		<?php
	}
	
	/**
	 * Save the custom field from the side metabox
	 * @param $post_id the current post ID
	 * @return post_id the post ID from the input arguments
	 * 
	 */
	public function dx_save_sample_field( $post_id ) {
		// Avoid autosaves
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$slug = 'pluginbase';
		// If this isn't a 'book' post, don't update it.
		if ( ! isset( $_POST['post_type'] ) || $slug != $_POST['post_type'] ) {
			return;
		}
		
		// If the custom field is found, update the postmeta record
		// Also, filter the HTML just to be safe
		if ( isset( $_POST['dx_test_input']  ) ) {
			update_post_meta( $post_id, 'dx_test_input',  esc_html( $_POST['dx_test_input'] ) );
		}
	}
	
	/**
	 * 
	 * Init bottom meta box here 
	 * @param post $post the post object of the given page 
	 * @param metabox $metabox metabox data
	 */
	public function dx_bottom_meta_box( $post, $metabox) {
		_e( "<p>Bottom meta content here</p>", DXP_TD );
	}
	
	/**
	 * Register custom post types
     *
	 */
	public function dx_custom_post_types_callback() {
		register_post_type( 'pluginbase', array(
			'labels' => array(
				'name' => __("Base Items", DXP_TD),
				'singular_name' => __("Base Item", DXP_TD),
				'add_new' => _x("Add New", 'pluginbase', DXP_TD ),
				'add_new_item' => __("Add New Base Item", DXP_TD ),
				'edit_item' => __("Edit Base Item", DXP_TD ),
				'new_item' => __("New Base Item", DXP_TD ),
				'view_item' => __("View Base Item", DXP_TD ),
				'search_items' => __("Search Base Items", DXP_TD ),
				'not_found' =>  __("No base items found", DXP_TD ),
				'not_found_in_trash' => __("No base items found in Trash", DXP_TD ),
			),
			'description' => __("Base Items for the demo", DXP_TD),
			'public' => true,
			'publicly_queryable' => true,
			'query_var' => true,
			'rewrite' => true,
			'exclude_from_search' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 40, // probably have to change, many plugins use this
			'supports' => array(
				'title',
				'editor',
				'thumbnail',
				'custom-fields',
				'page-attributes',
			),
			'taxonomies' => array( 'post_tag' )
		));	
	}
	
	
	/**
	 * Register custom taxonomies
     *
	 */
	public function dx_custom_taxonomies_callback() {
		register_taxonomy( 'pluginbase_taxonomy', 'pluginbase', array(
			'hierarchical' => true,
			'labels' => array(
				'name' => _x( "Base Item Taxonomies", 'taxonomy general name', DXP_TD ),
				'singular_name' => _x( "Base Item Taxonomy", 'taxonomy singular name', DXP_TD ),
				'search_items' =>  __( "Search Taxonomies", DXP_TD ),
				'popular_items' => __( "Popular Taxonomies", DXP_TD ),
				'all_items' => __( "All Taxonomies", DXP_TD ),
				'parent_item' => null,
				'parent_item_colon' => null,
				'edit_item' => __( "Edit Base Item Taxonomy", DXP_TD ), 
				'update_item' => __( "Update Base Item Taxonomy", DXP_TD ),
				'add_new_item' => __( "Add New Base Item Taxonomy", DXP_TD ),
				'new_item_name' => __( "New Base Item Taxonomy Name", DXP_TD ),
				'separate_items_with_commas' => __( "Separate Base Item taxonomies with commas", DXP_TD ),
				'add_or_remove_items' => __( "Add or remove Base Item taxonomy", DXP_TD ),
				'choose_from_most_used' => __( "Choose from the most used Base Item taxonomies", DXP_TD )
			),
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => true,
		));
		
		register_taxonomy_for_object_type( 'pluginbase_taxonomy', 'pluginbase' );
	}
	
	/**
	 * Initialize the Settings class
	 * 
	 * Register a settings section with a field for a secure WordPress admin option creation.
	 * 
	 */
	public function dx_register_settings() {
		require_once( $this->path_include . '/class-dx-plugin-settings.php' );
		new DX_Plugin_Settings();
	}
	
	/**
	 * Register a sample shortcode to be used
	 * 
	 * First parameter is the shortcode name, would be used like: [dxsampcode]
	 * 
	 */
	public function dx_sample_shortcode() {
		add_shortcode( 'dxsampcode', array( $this, 'dx_sample_shortcode_body' ) );
	}
	
	/**
	 * Returns the content of the sample shortcode, like [dxsamplcode]
	 * @param array $attr arguments passed to array, like [dxsamcode attr1="one" attr2="two"]
	 * @param string $content optional, could be used for a content to be wrapped, such as [dxsamcode]somecontnet[/dxsamcode]
	 */
	public function dx_sample_shortcode_body( $attr, $content = null ) {
		/*
		 * Manage the attributes and the content as per your request and return the result
		 */
		return __( 'Sample Output', DXP_TD);
	}
	
	/**
	 * Hook for including a sample widget with options
	 */
	public function dx_sample_widget() {
		include_once $this->path_include . 'dx-sample-widget.class.php';
	}
	
	/**
	 * Add textdomain for plugin
	 */
	public function dx_add_textdomain() {
		load_plugin_textdomain( DXP_TD, false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}
	
	/**
	 * Callback for saving a simple AJAX option with no page reload
	 */
	public function store_ajax_value() {
		if( isset( $_POST['data'] ) && isset( $_POST['data']['dx_option_from_ajax'] ) ) {
			update_option( 'dx_option_from_ajax' , $_POST['data']['dx_option_from_ajax'] );
		}	
		die();
	}
	
	/**
	 * Callback for getting a URL and fetching it's content in the admin page
	 */
	public function fetch_ajax_url_http() {
		if( isset( $_POST['data'] ) && isset( $_POST['data']['dx_url_for_ajax'] ) ) {
			$ajax_url = $_POST['data']['dx_url_for_ajax'];
			
			$response = wp_remote_get( $ajax_url );
			
			if( is_wp_error( $response ) ) {
				echo json_encode( __( 'Invalid HTTP resource', DXP_TD ) );
				die();
			}
			
			if( isset( $response['body'] ) ) {
				if( preg_match( '/<title>(.*)<\/title>/', $response['body'], $matches ) ) {
					echo json_encode( $matches[1] );
					die();
				}
			}
		}
		echo json_encode( __( 'No title found or site was not fetched properly', DXP_TD ) );
		die();
	}
	
}


/**
 * Register activation hook
 *
 */
function dx_on_activate_callback() {
	// do something on activation
}
register_activation_hook( __FILE__, 'dx_on_activate_callback' );

/**
 * Register deactivation hook
 *
 */
function dx_on_deactivate_callback() {
	// do something when deactivated
}
register_deactivation_hook( __FILE__, 'dx_on_deactivate_callback' );

/**
 * Register uninstall hook
 * 
 */
function dx_on_uninstall_callback(){
	// do something when plugin is uninstalling
}
register_uninstall_hook( __FILE__, 'dx_on_uninstall_callback' );

// Initialize everything
$dx_plugin_base = new DX_Plugin_Base();
