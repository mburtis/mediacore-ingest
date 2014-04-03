<?php
/**
 * Plugin Name: Mediacore Ingest (FeedWordPress AddOn)
 * Description: This plugin takes an RSS feed from Mediacore (vai FWP) and formats it with the shortcode necessary to embed it in a post.
 * Version: 1.0
 * Author: Martha Burtis
 * Author URI: http://marthaburtis.net
 * License: GPL2
 */
 
 

if(!class_exists('fwp_mediacore_ingest'))
{
	class fwp_mediacore_ingest
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
        	// Initialize Settings
            require_once(sprintf("%s/settings.php", dirname(__FILE__)));
            add_filter( 'syndicated_item', 'fwp_mediacore_content', 10, 2);
            add_filter( 'syndicated_item_categories', 'fwp_mediacore_tags',10, 2);
            $fwp_mediacore_ingest_settings = new fwp_mediacore_ingest_settings();
        	
      	} // END public function __construct
	    
		/**
		 * Activate the plugin
		 */
		public static function activate()
		{
			// Do nothing
		} // END public static function activate
	
		/**
		 * Deactivate the plugin
		 */		
		public static function deactivate()
		{
			
		} 
	} 
} 

if(class_exists('fwp_mediacore_ingest'))
{
	// Installation and uninstallation hooks
	register_activation_hook(__FILE__, array('fwp_mediacore_ingest', 'activate'));
	register_deactivation_hook(__FILE__, array('fwp_mediacore_ingest', 'deactivate'));

	// instantiate the plugin class
	$fwp_mediacore_ingest = new fwp_mediacore_ingest();
	
    // Add a link to the settings page onto the plugin page
    if(isset($fwp_mediacore_ingest))
    {
        // Add the settings link to the plugins page
        function plugin_settings_link($links)
        { 
            $settings_link = '<a href="options-general.php?page=fwp_mediacore_ingest">Settings</a>'; 
            array_unshift($links, $settings_link); 
            return $links; 
        }

        $plugin = plugin_basename(__FILE__); 
        add_filter("plugin_action_links_$plugin", 'plugin_settings_link');
    }
}
 
 
function fwp_mediacore_content ($item, $post) {
	$fwp_mediacore_hub_url = get_site_option('hub_url');
	$syndicated_guid_array = parse_url($item['guid']);
	$syndicated_guid_host = $syndicated_guid_array['host'];
 
	if ($syndicated_guid_host == $fwp_mediacore_hub_url) {
		$syndicatedtags = $item["media"]["media"]["community_tags"];
		global $syndicatedtags_array; 
		$syndicatedtags_array = explode(',', $syndicatedtags);
		$item["description"] = '[mediacore public_url="'.$item["link"].'" thumb_url="'.$item["media"]["thumbnail@url"].'" title="'.$item["title"].' width="640px" height="360px"]';
		

	}
 
 return $item;
} 

function fwp_mediacore_tags ($categories, $post) {
	echo 'test';
	global $syndicatedtags_array;
	if ($syndicatedtags_array){
	$fwp_mediacore_tag_options = get_site_option('show_tags');
	if ($fwp_mediacore_tag_options == 1) {
	global $syndicatedtags_array;
	$categories = $syndicatedtags_array;
	}
	}
	return $categories;
}

function mcore_shortcode_handler($atts) {
	extract(shortcode_atts(array(
		'public_url' => '',
		'thumb_url' => '',
		'title' => '',
		'width' => '',
		'height' => '',
	), $atts));

	$embedcode = "<iframe src=\"" . $public_url . "/embed_player?iframe=True\"";
	$embedcode .= " width=\"" . $width . "\"";
	$embedcode .= " height=\"" . $height . "\"";
	$embedcode .= " mozallowfullscreen=\"mozallowfullscreen\"";
	$embedcode .= " webkitallowfullscreen=\"webkitallowfullscreen\"";
	$embedcode .= " allowfullscreen=\"allowfullscreen\"";
	$embedcode .= " scrolling=\"no\"";
	$embedcode .= " frameborder=\"0\"";
	$embedcode .= "></iframe>";
	return $embedcode;
}
add_shortcode('mediacore', 'mcore_shortcode_handler');