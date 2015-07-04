## DX Plugin Base

The DX Plugin Base plugin includes best practices and code snippets to create your own WordPress plugin.

## Included Features

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

### Registering Menu Pages

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
