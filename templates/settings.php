<div class="wrap">
    <h2>Mediacore Ingest Settings</h2>
    <?php 
    if ( !is_plugin_active( 'feedwordpress/feedwordpress.php' ) ) { ?>
		<div class="error">
			<p>It doesn't appear that you currently have <a href="https://wordpress.org/plugins/feedwordpress/">FeedWordPress</a> installed and activated on this site. This plugin works with FeedWordPress; you will need to ensure it is installed and activated before this plugin will work.</p>
		</div>
	<?php } ?>
    
    <form method="post" action="options.php"> 
        <?php @settings_fields('fwp_mediacore_ingest_group'); ?>
        <?php @do_settings_fields('fwp_mediacore_ingest_section'); ?>

        <?php do_settings_sections('fwp_mediacore_ingest'); ?>

        <?php @submit_button(); ?>
    </form>
</div>