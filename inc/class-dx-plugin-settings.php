<?php

class DX_Plugin_Settings {
	
	private $dx_setting;
	/**
	 * Construct me
	 */
	public function __construct() {
		$this->dx_setting = get_option( 'dx_setting', '' );
		
		// register the checkbox
		add_action('admin_init', array( $this, 'register_settings' ) );
	}
		
	/**
	 * Setup the settings
	 * 
	 * Add a single checkbox setting for Active/Inactive and a text field 
	 * just for the sake of our demo
	 * 
	 */
	public function register_settings() {
		register_setting( 'dx_setting', 'dx_setting', array( $this, 'dx_validate_settings' ) );
		
		add_settings_section(
			'dx_settings_section',         // ID used to identify this section and with which to register options
			__( "Enable DX Templates", DXP_TD ),                  // Title to be displayed on the administration page
			array($this, 'dx_settings_callback'), // Callback used to render the description of the section
			'dx-plugin-base'                           // Page on which to add this section of options
		);
	
		add_settings_field(
			'dx_opt_in',                      // ID used to identify the field throughout the theme
			__( "Active: ", DXP_TD ),                           // The label to the left of the option interface element
			array( $this, 'dx_opt_in_callback' ),   // The name of the function responsible for rendering the option interface
			'dx-plugin-base',                          // The page on which this option will be displayed
			'dx_settings_section'         // The name of the section to which this field belongs
		);
		
		add_settings_field(
			'dx_sample_text',                      // ID used to identify the field throughout the theme
			__( "DX Sample: ", DXP_TD ),                           // The label to the left of the option interface element
			array( $this, 'dx_sample_text_callback' ),   // The name of the function responsible for rendering the option interface
			'dx-plugin-base',                          // The page on which this option will be displayed
			'dx_settings_section'         // The name of the section to which this field belongs
		);
		//options page settings
		register_setting( 'dx_options_setting', 'dx_options_setting', array( $this, 'dx_options_settings_validate' ) );
		add_settings_section(
			'dx_options_settings_section',         // ID used to identify this section and with which to register options
			__( "Plugin Base Options", DXP_TD ),                  // Title to be displayed on the administration page
			array( $this, 'dx_options_settings_callback' ), // Callback used to render the description of the section
			'dx_base_plugin_options'                           // Page on which to add this section of options
		);
	
		add_settings_field(
			'dx_options_checkbox',                      // ID used to identify the field throughout the theme
			__( "Checkbox: ", DXP_TD ),                           // The label to the left of the option interface element
			array( $this, 'dx_options_checkbox_callback' ),   // The name of the function responsible for rendering the option interface
			'dx_base_plugin_options',                          // The page on which this option will be displayed
			'dx_options_settings_section'         // The name of the section to which this field belongs
		);
		
		add_settings_field(
			'dx_options_text',                      // ID used to identify the field throughout the theme
			__( "DX Sample: ", DXP_TD ),                           // The label to the left of the option interface element
			array( $this, 'dx_options_text_callback' ),   // The name of the function responsible for rendering the option interface
			'dx_base_plugin_options',                          // The page on which this option will be displayed
			'dx_options_settings_section'         // The name of the section to which this field belongs
		);
		
	}
	
	public function dx_settings_callback() {
		echo _e( "Enable me", DXP_TD );
	}
	
	public function dx_opt_in_callback() {
		$enabled = false;
		$out = ''; 
		$val = false;
		
		// check if checkbox is checked
		if(! empty( $this->dx_setting ) && isset ( $this->dx_setting['dx_opt_in'] ) ) {
			$val = true;
		}
		
		if($val) {
			$out = '<input type="checkbox" id="dx_opt_in" name="dx_setting[dx_opt_in]" CHECKED  />';
		} else {
			$out = '<input type="checkbox" id="dx_opt_in" name="dx_setting[dx_opt_in]" />';
		}
		
		$out .= sprintf( '<p>%s</p>', __( 'Cehckbox description text sample.', DXP_TD ) );
		
		echo $out;
	}
	
	public function dx_sample_text_callback() {
		$out = '';
		$val = '';
		
		// check if checkbox is checked
		if(! empty( $this->dx_setting ) && isset ( $this->dx_setting['dx_sample_text'] ) ) {
			$val = $this->dx_setting['dx_sample_text'];
		}

		$out = '<input type="text" id="dx_sample_text" name="dx_setting[dx_sample_text]" value="' . $val . '"  />';
		$out .= sprintf( '<p>%s</p>', __( 'Input description text sample.', DXP_TD ) );
		
		echo $out;
	}
	
	/**
	 * Helper Settings function if you need a setting from the outside.
	 * 
	 * Keep in mind that in our demo the Settings class is initialized in a specific environment and if you
	 * want to make use of this function, you should initialize it earlier (before the base class)
	 * 
	 * @return boolean is enabled
	 */
	public function is_enabled() {
		if(! empty( $this->dx_setting ) && isset ( $this->dx_setting['dx_opt_in'] ) ) {
			return true;
		}
		return false;
	}
	
	/**
	 * Validate Settings
	 * 
	 * Filter the submitted data as per your request and return the array
	 * 
	 * @param array $input
	 */
	public function dx_validate_settings( $input ) {
		array_walk_recursive($input, function( &$item, $key ){//senitizy the user input before saving to the DB
			$item = esc_attr( $item );
		});
		return $input;
	}
		
	public function dx_options_settings_callback() {
		_e( 'Options page settings', DXP_TD );
	}
	
	public function dx_options_checkbox_callback() {
		$dx_options_setting = get_option( 'dx_options_setting' );
		$checkbox = !empty( $dx_options_setting['dx_options_checkbox'] )? $dx_options_setting['dx_options_checkbox'] : '';
		?>
		<input type="checkbox" id="dx_options_checkbox" name="dx_options_setting[dx_options_checkbox]" value="1" <?php checked( 1, $checkbox ); ?> />
		<label for="dx_options_checkbox"><?php _e( 'Checkbox description', DXP_TD ); ?></label>
		<?php 
	}
	
	public function dx_options_text_callback() {
		$dx_options_setting = get_option( 'dx_options_setting' );
		$text = !empty( $dx_options_setting['dx_options_text'] )? $dx_options_setting['dx_options_text'] : '';
		?>
		<input type="text" id="dx_options_text" name="dx_options_setting[dx_options_text]" value="<?php esc_attr_e( $text ); ?>" />
		<label for="dx_options_text"><?php _e( 'Text input description', DXP_TD ); ?></label>
		<?php 
	}	
	
	public function dx_options_settings_validate( $input ) {
		array_walk_recursive($input, function( &$item, $key ){//senitizy the user input before saving to the DB
			$item = esc_attr( $item );
		});
		return $input;
	}
}
