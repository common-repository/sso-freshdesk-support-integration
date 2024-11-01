<?php
	if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) 
	    exit();

	if(!get_site_option('idp_keep_settings_intact'))
	{
		global $wpdb;
		delete_site_option('mo_idp_message');
		delete_site_option('mo_saml_idp_plugin_version');
		delete_site_option('mo_idp_usr_lmt');
		delete_site_option('mo_idp_entity_id');

		//freshdesk settings
		delete_site_option('mo_idp_freshdesk_api');
        delete_site_option('mo_idp_freshdesk_url');
        delete_site_option('mo_idp_freshdesk_widget');
        delete_site_option('mo_idp_freshdesk_widget_code');
        delete_site_option('mo_idp_wp_comments_tickets');
        delete_site_option('mo_idp_allow_anonymous_tickets');

		$sql =  is_multisite() ? "DROP TABLE mo_sp_attributes" : "DROP TABLE ". $wpdb->prefix . 'mo_sp_attributes';
		$wpdb->query($sql);

		$sql = is_multisite() ? "DROP TABLE mo_sp_data" : "DROP TABLE ". $wpdb->prefix . 'mo_sp_data';
		$wpdb->query($sql);
	}
?>