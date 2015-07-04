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

### Registering Taxonomies

### Adding Meta Boxes

### Storing custom postmeta values

### Making your plugin translatable (i18n)

### Creating a Settings Page

### Creating a Custom Widget

### Creating a Custom Shortcode

### Fetching AJAX Data Remotely

It's live on WordPress.org - http://wordpress.org/extend/plugins/dx-plugin-base/developers/  - and ready for an automatic install from the WordPress admin.

Learn how to build custom post types and taxonomies, add metaboxes, include external JS/CSS files properly and much more.
