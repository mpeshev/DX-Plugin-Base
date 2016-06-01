<div class="wrap">
	<div id="icon-edit" class="icon32 icon32-base-template"><br></div>
	<h2><?php _e( "Base plugin page", DXP_TD ); ?></h2>
	
	<p><?php _e( "Sample base plugin page", DXP_TD ); ?></p>
	
	<form id="dx-plugin-base-form" action="options.php" method="POST">
			<?php do_action( 'dx_base_page_template_form_before_settings' ) ?>
		
			<?php settings_fields( 'dx_setting' ) ?>
			<?php do_settings_sections( 'dx-plugin-base' ) ?>
			
			<?php do_action( 'dx_base_page_template_form_after_settings' ) ?>
		
			<input type="submit" value="<?php echo apply_filters( 'dx_base_page_template_form_submit_button', __( "Save", DXP_TD ) ) ?>" />			
	</form> <!-- end of #dxtemplate-form -->
</div>