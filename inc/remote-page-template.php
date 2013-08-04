<div class="wrap">
	<div id="icon-edit" class="icon32 icon32-base-template"><br></div>
	<h2><?php _e( "Remote plugin page", 'dxbase' ); ?></h2>
	
	<p><?php _e( "Performing side activities - AJAX and HTTP fetch", 'dxbase' ); ?></p>
	<div id="dx_page_messages"></div>
	
	<?php
		$dx_ajax_value = get_option( 'dx_option_from_ajax', '' );
	?>
	
	<h3><?php _e( 'Store a Database option with AJAX', 'dxbase' ); ?></h3>
	<form id="dx-plugin-base-ajax-form" action="options.php" method="POST">
			<input type="text" id="dx_option_from_ajax" name="dx_option_from_ajax" value="<?php echo $dx_ajax_value; ?>" />
			
			<input type="submit" value="<?php _e( "Save with AJAX", 'dxbase' ); ?>" />
	</form> <!-- end of #dx-plugin-base-ajax-form -->
	
	<h3><?php _e( 'Fetch a title from URL with HTTP call through AJAX', 'dxbase' ); ?></h3>
	<form id="dx-plugin-base-http-form" action="options.php" method="POST">
			<input type="text" id="dx_url_for_ajax" name="dx_url_for_ajax" value="http://wordpress.org" />
			
			<input type="submit" value="<?php _e( "Fetch URL title with AJAX", 'dxbase' ); ?>" />
	</form> <!-- end of #dx-plugin-base-http-form -->
	
	<div id="resource-window">
	</div>
			
</div>