<?php
/**
 * Plugin Name: Your Sample Plugin
 * Plugin URI: http://example.org/
 * Description: Your sample plugin description
 * Author: nofearinc
 * Author URI: http://devwp.eu/
 * Version: 0.1a
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
 * 
 * The plugin base class - the root of all WP goods!
 * 
 * @author nofearinc
 *
 */
class DX_Plugin_Base {
	/**
	 * 
	 * Assign everything as a call from within the constructor
	 */
	function __construct() {
		// add script and style calls the WP way 
		$this->dx_add_JS();
		$this->dx_add_CSS();
		
		// register admin pages for the plugin
		$this->dx_admin_pages();
		
		// register meta boxes for Pages (could be replicated for posts and custom post types)
		$this->dx_meta_boxes();
		
		// Register custom post types and taxonomies
		$this->dx_custom_post_types();
		$this->dx_custom_taxonomies();
		
		// Register activation and deactivation hooks
		$this->dx_on_activate();
		$this->dx_on_dectivate();
		
		/* 
		$this->dx_add_widgets();
		$this->dx_add_shortcodes();
		$this->dx_register_settings();
		 */
		
		// TODO: add some filters so that this may not be edited at all but hooked instead
	}	
	
	/**
	 * 
	 * Adding JavaScript scripts
	 * 
	 * Loading existing scripts from wp-includes or adding custom ones
	 * 
	 */
	function dx_add_JS() {
		wp_enqueue_script('jquery');
		// load custom JSes and put them in footer
		wp_register_script('samplescript', plugins_url( 'samplescript.js' , __FILE__ ), array('jquery'), '1.0', true);
		wp_enqueue_script('samplescript');
	}
	
	/**
	 * 
	 * Add CSS styles
	 * 
	 */
	function dx_add_CSS() {
		wp_register_style('samplestyle', plugins_url('samplestyle.css', __FILE__), array(), '1.0', 'screen');
		wp_enqueue_style('samplestyle');
	}
	
	/**
	 * 
	 * Add admin pages via callback
	 * 
	 */
	function dx_admin_pages() {
		add_action( 'admin_menu', array(&$this, 'dx_admin_pages_callback') );
	}
	
	/**
	 * 
	 * Callback for registering pages
	 * 
	 * This demo registers a custom page for the plugin and a subpage
	 *  
	 */
	function dx_admin_pages_callback() {
		add_menu_page('Plugin Base Admin', 'Plugin Base Admin', 'edit_themes', 'dx-plugin-base', array(&$this, 'dx_plugin_base'));		
		add_submenu_page( 'dx-plugin-base', 'Base Subpage', 'Base Subpage', 'edit_themes', 'dx-base-subpage', array(&$this, 'dx_plugin_subpage'));
	}
	
	/**
	 * 
	 * The content of the base page
	 * 
	 */
	function dx_plugin_base() {
		
	}
	
	/**
	 * 
	 * The content of the subpage 
	 *
	 */
	function dx_plugin_subpage() {
		
	}
	
	/**
	 * 
	 * Registering meta boxes
	 * 
	 */
	function dx_meta_boxes() {
		add_action( 'add_meta_boxes', array(&$this, 'dx_meta_boxes_callback') );
	}
	
	/**
	 * 
	 *  Adding right and bottom meta boxes to Pages
	 *   
	 */
	function dx_meta_boxes_callback() {
		// register side box
		add_meta_box( 
		        'dx_side_meta_box',
		        __( 'DX Side Box', 'dxbase' ),
		        array(&$this, 'dx_side_meta_box'),
		        'page',
		        'side',
		        'high'
		    );
		    
		// register bottom box
		add_meta_box(
		    	'dx_bottom_meta_box',
		    	__( 'DX Bottom Box', 'dxbase' ), 
		    	array(&$this, 'dx_bottom_meta_box'),
		    	'page'
		    );
	}
	
	/**
	 * 
	 * Init right side meta box here 
	 * @param post $post the post object of the given page 
	 * @param metabox $metabox metabox data
	 */
	function dx_side_meta_box($post, $metabox) {
		_e('<p>Side meta content here</p>', 'dxbase');
	}
	
	/**
	 * 
	 * Init bottom meta box here 
	 * @param post $post the post object of the given page 
	 * @param metabox $metabox metabox data
	 */
	function dx_bottom_meta_box($post, $metabox) {
		_e('<p>Bottom meta content here</p>', 'dxbase');
	}
	
	/**
	 * Register custom post types
     *
	 */
	function dx_custom_post_types() {
		add_action('init', array( &$this, 'dx_custom_post_types_callback' ));
	}
	
	function dx_custom_post_types_callback() {
		register_post_type('pluginbase', array(
			'labels' => array(
				'name' => __('Base Items', 'dxbase'),
				'singular_name' => __('Base Item', 'dxbase'),
				'add_new' => _x('Add New', 'pluginbase', 'dxbase' ),
				'add_new_item' => __('Add New Base Item', 'dxbase' ),
				'edit_item' => __('Edit Base Item', 'dxbase' ),
				'new_item' => __('New Base Item', 'dxbase' ),
				'view_item' => __('View Base Item', 'dxbase' ),
				'search_items' => __('Search Base Items', 'dxbase' ),
				'not_found' =>  __('No base items found', 'dxbase' ),
				'not_found_in_trash' => __('No base items found in Trash', 'dxbase' ),
			),
			'description' => __('Base Items for the demo', 'dxbase'),
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
			'taxonomies' => array('post_tag')
		));	
	}
	
	
	/**
	 * Register custom taxonomies
     *
	 */
	function dx_custom_taxonomies() {
		add_action('init', array(&$this, 'dx_custom_taxonomies_callback' ));
	}
	
	function dx_custom_taxonomies_callback() {
		register_taxonomy('pluginbase_taxonomy','pluginbase',array(
			'hierarchical' => true,
			'labels' => array(
				'name' => _x( 'Base Item Taxonomies', 'taxonomy general name', 'dxbase' ),
				'singular_name' => _x( 'Base Item Taxonomy', 'taxonomy singular name', 'dxbase' ),
				'search_items' =>  __( 'Search Taxonomies', 'dxbase' ),
				'popular_items' => __( 'Popular Taxonomies', 'dxbase' ),
				'all_items' => __( 'All Taxonomies', 'dxbase' ),
				'parent_item' => null,
				'parent_item_colon' => null,
				'edit_item' => __( 'Edit Base Item Taxonomy', 'dxbase' ), 
				'update_item' => __( 'Update Base Item Taxonomy', 'dxbase' ),
				'add_new_item' => __( 'Add New Base Item Taxonomy', 'dxbase' ),
				'new_item_name' => __( 'New Base Item Taxonomy Name', 'dxbase' ),
				'separate_items_with_commas' => __( 'Separate Base Item taxonomies with commas', 'dxbase' ),
				'add_or_remove_items' => __( 'Add or remove Base Item taxonomy', 'dxbase' ),
				'choose_from_most_used' => __( 'Choose from the most used Base Item taxonomies', 'dxbase' )
			),
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => true,
		));
		
		register_taxonomy_for_object_type('pluginbase_taxonomy', 'pluginbase');
	}
	
	/**
	 * Register activation hook
	 *
	 */
	function dx_on_activate() {
		register_activation_hook( __FILE__, array(&$this, 'dx_on_activate_callback') );
	}
	
	function dx_on_activate_callback() {
		// do something on activation
	}
	
	/**
	 * Register deactivation hook
	 * 
	 */
	function dx_on_deactivate() {
		register_deactivation_hook( __FILE__, array(&$this, 'dx_on_deactivate_callback') );
	}
	
	function dx_on_deactivate_callback() {
		// do something when deactivated
	}
	
}

// Initialize everything
$dx_plugin_base = new DX_Plugin_Base();