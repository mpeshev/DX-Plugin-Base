## DX Plugin Base

The DX Plugin Base plugin outlines the best practices through existing code snippets in order to make it easier for you to create your own WordPress plugin.

The plugin is ready to go - you can install it and it will simply work! Feel free to fork it away, remove and alter existing snippets and fine tune it in order to make it all yours.

You could also use specific snippets and copy them over to your new plugin - just like a snippet library of helper functions for you to use. It's up to you.

When in doubt, always check the syntax and complete function reference at [developer.wordpress.org](https://developer.wordpress.org/).

## Included Features and Snippets

### Enqueueing JavaScript

JavaScript could be added both at the frontend of your WordPress website, and the backend (your WordPress admin dashboard). Depending on your preference, you should attach your JavaScript callback to the hook responsible for the frontend inclusions, or the backend once.

For frontend:

```php
    add_action( 'wp_enqueue_scripts', array( $this, 'dx_add_JS' ) );
```    

This would add the `dx_add_JS` function do the hook responsible for adding scripts to the frontend. Your function can later add JS files like that:

```php
	public function dx_add_JS() {
		wp_enqueue_script( 'jquery' );
		// load custom JSes and put them in footer
		wp_register_script( 'samplescript', plugins_url( '/js/samplescript.js' , __FILE__ ), array('jquery'), '1.0', true );
		wp_enqueue_script( 'samplescript' );
	}
```

And for backend:

```php
    add_action( 'admin_enqueue_scripts', array( $this, 'dx_add_admin_JS' ) );
```
    
Calling a function for your backend is similar:

```php
	public function dx_add_admin_JS( $hook ) {
		wp_enqueue_script( 'jquery' );
		wp_register_script( 'samplescript-admin', plugins_url( '/js/samplescript-admin.js' , __FILE__ ), array('jquery'), '1.0', true );
		wp_enqueue_script( 'samplescript-admin' );
	}
```
    
You can also use the $hook argument in order to identify which is the current screen, and display context-specific content this way. 
    

### Enqueueing CSS

Styling your WordPress project could require both admin updates (for plugin settings pages or general dashboard overhaul) or frontend updates for your components (and overrides on top of the existing WordPress theme). 

In order to accomplish that, you need to enqueue your style callback functions to the frontend or backend hooks.

Similarly to the JS enqueueing process, the same hooks are used for adding your styles: `wp_enqueue_scripts` for the frontend, and `admin_enqueue_scripts` for the backend, example:

```php
    add_action( 'wp_enqueue_scripts', array( $this, 'dx_add_CSS' ) );
    add_action( 'wp_enqueue_scripts', array( $this, 'dx_add_CSS' ) );
```

Then, you can call the `wp_enqueue_style` function within your callback method in order to load the style in question:

```php
   	public function dx_add_admin_CSS( $hook ) {
		wp_register_style( 'samplestyle-admin', plugins_url( '/css/samplestyle-admin.css', __FILE__ ), array(), '1.0', 'screen' );
		wp_enqueue_style( 'samplestyle-admin' );	
		if( 'toplevel_page_dx-plugin-base' === $hook ) {
			wp_register_style('dx_help_page',  plugins_url( '/help-page.css', __FILE__ ) );
			wp_enqueue_style('dx_help_page');
		}
	}
```

We have also used the `$hook` argument available for the admin callbacks, that allows you to easily enqueue a style only in certain admin pages.  

### Registering Menu Pages

There are several ways to register menu pages, the main one requires hooking your callback to `admin_menu` first:

```php
	add_action( 'admin_menu', array( $this, 'dx_admin_pages_callback' ) );
```

Then you can add top level or submenu pages to your dashboard menu:

```php
	public function dx_admin_pages_callback() {
		add_menu_page(__( "Plugin Base Admin", 'dxbase' ), __( "Plugin Base Admin", 'dxbase' ), 'edit_themes', 'dx-plugin-base', array( $this, 'dx_plugin_base' ) );		
		add_submenu_page( 'dx-plugin-base', __( "Base Subpage", 'dxbase' ), __( "Base Subpage", 'dxbase' ), 'edit_themes', 'dx-base-subpage', array( $this, 'dx_plugin_subpage' ) );
		add_submenu_page( 'dx-plugin-base', __( "Remote Subpage", 'dxbase' ), __( "Remote Subpage", 'dxbase' ), 'edit_themes', 'dx-remote-subpage', array( $this, 'dx_plugin_side_access_page' ) );
	}
```

It's up to you what would you hook exactly and what would be the capabilities required for your users, but that's the sample syntax that you'd need. Each of those pages is defined via a callback at the end of the function parameters list, that could either be plain HTML/PHP, or loading an external file including your logic:

```php
	// Earlier in your plugin header
	define( 'DXP_PATH_INCLUDES', dirname( __FILE__ ) . '/inc' );
	// A class method for the callback
	public function dx_plugin_side_access_page() {
		include_once( DXP_PATH_INCLUDES . '/remote-page-template.php' );
	}
```

### Registering Post Types

Creating new content types in WordPress is fairly easy - it requires registering new post types for each data collection. Since it's a global action that's used across the entire site, we need to register it with the `init` hook:

```php
		add_action( 'init', array( $this, dx_custom_post_types_callback' ), 5 );
```

The function responsible for the registration has plenty of options to play with, in terms of labels, capabilities, visibility control and so forth. An example is:

```php
	public function dx_custom_post_types_callback() {
		register_post_type( 'pluginbase', array(
			'labels' => array(
				'name' => __("Base Items", 'dxbase'),
				'singular_name' => __("Base Item", 'dxbase'),
				'add_new' => _x("Add New", 'pluginbase', 'dxbase' ),
				'add_new_item' => __("Add New Base Item", 'dxbase' ),
				'edit_item' => __("Edit Base Item", 'dxbase' ),
				'new_item' => __("New Base Item", 'dxbase' ),
				'view_item' => __("View Base Item", 'dxbase' ),
				'search_items' => __("Search Base Items", 'dxbase' ),
				'not_found' =>  __("No base items found", 'dxbase' ),
				'not_found_in_trash' => __("No base items found in Trash", 'dxbase' ),
			),
			'description' => __("Base Items for the demo", 'dxbase'),
			'public' => true,
			'publicly_queryable' => true,
			'query_var' => true,
			'rewrite' => true,
			'exclude_from_search' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 40, 
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
```

The base item here is a random name for your entry type, which may as well be a Product, House or something else.

### Registering Taxonomies

Grouping content entries by criteria is possible with Categories and Tags in a default WordPress install. We can create other Custom Taxonomy entries for things such as Cities, Colors, Number of Bedrooms or other enumerable and classifiable entries.

We should hook them up at an `init` hook as well:

```php
    add_action( 'init', array( $this, 'dx_custom_taxonomies_callback' ), 6 );
```

Then, our callback is registering the custom taxonomy and binds it so a custom post type:

```php
	public function dx_custom_taxonomies_callback() {
		register_taxonomy( 'pluginbase_taxonomy', 'pluginbase', array(
			'hierarchical' => true,
			'labels' => array(
				'name' => _x( "Base Item Taxonomies", 'taxonomy general name', 'dxbase' ),
				'singular_name' => _x( "Base Item Taxonomy", 'taxonomy singular name', 'dxbase' ),
				'search_items' =>  __( "Search Taxonomies", 'dxbase' ),
				'popular_items' => __( "Popular Taxonomies", 'dxbase' ),
				'all_items' => __( "All Taxonomies", 'dxbase' ),
				'parent_item' => null,
				'parent_item_colon' => null,
				'edit_item' => __( "Edit Base Item Taxonomy", 'dxbase' ), 
				'update_item' => __( "Update Base Item Taxonomy", 'dxbase' ),
				'add_new_item' => __( "Add New Base Item Taxonomy", 'dxbase' ),
				'new_item_name' => __( "New Base Item Taxonomy Name", 'dxbase' ),
				'separate_items_with_commas' => __( "Separate Base Item taxonomies with commas", 'dxbase' ),
				'add_or_remove_items' => __( "Add or remove Base Item taxonomy", 'dxbase' ),
				'choose_from_most_used' => __( "Choose from the most used Base Item taxonomies", 'dxbase' )
			),
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => true,
		));
		
		register_taxonomy_for_object_type( 'pluginbase_taxonomy', 'pluginbase' );
	}
``` 

### Adding Meta Boxes

Your existing or custom Post types can display additional boxes on the Add/Edit admin screen that allows for displaying data or embedding custom forms for additional data - such as Price, Address or something else. 

You can register those as Custom Fields added to Meta Boxes - sections visible in the Add/Edit Posts screen. There's an `add_meta_boxes` hook to start with:

```php
	add_action( 'add_meta_boxes', array( $this, 'dx_meta_boxes_callback' ) );
```

Our callback method will register the metaboxes that we need, attached to a specific post type and listed in the respective position:

```php
	public function dx_meta_boxes_callback() {
		// register side box
		add_meta_box( 
		        'dx_side_meta_box',
		        __( "DX Side Box", 'dxbase' ),
		        array( $this, 'dx_side_meta_box' ),
		        'pluginbase', // leave empty quotes as '' if you want it on all custom post add/edit screens
		        'side',
		        'high'
		    );
		    
		// register bottom box
		add_meta_box(
		    	'dx_bottom_meta_box',
		    	__( "DX Bottom Box", 'dxbase' ), 
		    	array( $this, 'dx_bottom_meta_box' ),
		    	'' // leave empty quotes as '' if you want it on all custom post add/edit screens or add a post type slug
		    );
	}
```

The callback of our `add_meta_box` call includes everything that is to be displayed in our new admin section - which could be some informative message or input fields:

```php
	public function dx_side_meta_box( $post, $metabox) {
		_e("<p>Side meta content here</p>", 'dxbase');
		
		// Add some test data here - a custom field, that is
		$dx_test_input = '';
		if ( ! empty ( $post ) ) {
			// Read the database record if we've saved that before
			$dx_test_input = get_post_meta( $post->ID, 'dx_test_input', true );
		}
		?>
		<label for="dx-test-input"><?php _e( 'Test Custom Field', 'dxbase' ); ?></label>
		<input type="text" id="dx-test-input" name="dx_test_input" value="<?php echo $dx_test_input; ?>" />
		<?php
	}
``` 

Our side metabox includes a test input field that is fetched from the database and displayed (if an existing value is available). Other than that, we could save our post and get those data populated in the WordPress database.

### Storing custom field (post meta) values

When saving a post, the `save_post` action is being called:

```php
	add_action( 'save_post', array( $this, 'dx_save_sample_field' ) );
```

 We can hook there and verify our custom fields, and store them in the _postmeta database table for the current post entry ID. The default fields are stored by default, but we need to handle our custom entries:

```php
	public function dx_save_sample_field( $post_id ) {
		// Avoid autosaves
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$slug = 'pluginbase'; // our post type slug that we're handling
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
``` 

### Making your plugin translatable (i18n)

### Creating a Settings Page

### Creating a Custom Widget

### Creating a Custom Shortcode

### Fetching AJAX Data Remotely

It's live on WordPress.org - http://wordpress.org/extend/plugins/dx-plugin-base/developers/  - and ready for an automatic install from the WordPress admin.

Learn how to build custom post types and taxonomies, add metaboxes, include external JS/CSS files properly and much more.
