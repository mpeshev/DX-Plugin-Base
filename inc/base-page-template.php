<div class="wrap">
	<div id="icon-edit" class="icon32 icon32-base-template"><br></div>
	<h2><?php _e( 'Base plugin page', 'dxbase' ); ?></h2>
	
	<p><?php _e(' Sample base plugin page', 'dxbase' ); ?></p>
	
	<form id="dx-plugin-base-form" action="options.php" method="POST">
		
			<?php settings_fields( 'dx_setting' ) ?>
			<?php do_settings_sections( 'dx-plugin-base' ) ?>
			
			<input type="submit" value="<?php _e( 'Save', 'dxbase' ); ?>" />
	</form> <!-- end of #dxtemplate-form -->
</div>