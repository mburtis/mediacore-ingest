<?php
if(!class_exists('fwp_mediacore_ingest_settings'))
{
	class fwp_mediacore_ingest_settings
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
			// register actions
            add_action('admin_init', array(&$this, 'admin_init'));
        	add_action('admin_menu', array(&$this, 'add_menu'));
		} // END public function __construct
		
        /**
         * hook into WP's admin_init action hook
         */
        public function admin_init()
        {
            register_setting('fwp_mediacore_ingest_group', 'hub_url', array (&$this, 'settings_field_hub_url_sanitize'));
            register_setting('fwp_mediacore_ingest_group', 'show_tags');

        	add_settings_section(
        	    'fwp_mediacore_ingest_section', 
        	    'Mediacore Ingest Settings', 
        	    array (&$this, 'fwp_mediacore_ingest_section_callback'), 
        	    'fwp_mediacore_ingest'
        	);
        	
            add_settings_field(
                'fwp_mc_ingest_hub_url', 
                'Mediacore Installation URL', 
                array (&$this, 'settings_field_hub_url_callback'), 
                'fwp_mediacore_ingest', 
                'fwp_mediacore_ingest_section', 
                array(
                	'field' => 'hub_url'
                )
                
            );
            
            add_settings_field(
                'fwp_mc_ingest_show_tags', 
                'Mediacore Tag Options', 
                array (&$this, 'settings_field_show_tags_callback'), 
                'fwp_mediacore_ingest', 
                'fwp_mediacore_ingest_section', 
                array(
                	'field' => 'show_tags'
                )
                
            );
           
           
        } // END public static function activate
        
		public function fwp_mediacore_ingest_section_callback() {
			echo 'Change these settings to control how the Mediacore Ingest plugin works.';
		}

		public function settings_field_hub_url_callback($args) {
			$field = $args['field'];
			$value = get_option($field);
			echo sprintf('http://<input type="text" name="%s" id="%s" value="%s" />', $field, $field, $value);
			echo '<p>Please indicate the Web address of your Mediacore install so that we can ensure the ingest functions are only executed on content being syndicated from that site.</p>';

		}
		
		public function settings_field_hub_url_sanitize($input){
		
			$disallowed = array('http://', 'https://', '/');
			
			foreach($disallowed as $d) {
				if(strpos($input, $d) !== false) {
					$input = str_replace($d, '', $input);
				}
			}
			return $input;
		}
		
		
		public function settings_field_show_tags_callback($args) {
			echo '<input name="show_tags" type="checkbox" value="1" class="code" ' . checked( 1, get_option( 'show_tags' ), false ) . ' />';
			echo '<p>Check here if you would like us to capture categories from the Mediacore feed and create tags out of them on syndicated posts.</p>';
		}
        
        public function add_menu()
        {
            // Add a page to manage this plugin's settings
        	add_options_page(
        	    'Mediacore Ingest Settings', 
        	    'Mediacore Ingest', 
        	    'manage_options', 
        	    'fwp_mediacore_ingest', 
        	    array(&$this, 'fwp_mediacore_ingest_settings_page')
        	);
        } // END public function add_menu()
    
        /**
         * Menu Callback
         */		
        public function fwp_mediacore_ingest_settings_page()
        {
        	if(!current_user_can('manage_options'))
        	{
        		wp_die(__('You do not have sufficient permissions to access this page. LOSER'));
        	}
	
        	// Render the settings template
        	include(sprintf("%s/templates/settings.php", dirname(__FILE__)));
        } // END public function plugin_settings_page()
    } // END class wp_ccsve_template_Settings
} // END if(!class_exists('wp_ccsve_template_Settings'))

