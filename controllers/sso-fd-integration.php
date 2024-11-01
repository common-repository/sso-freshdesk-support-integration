<?php

$fd_api                 = esc_html(get_site_option('mo_idp_freshdesk_api'));
$fd_url                 = esc_url_raw(get_site_option('mo_idp_freshdesk_url'));
$fd_widget_check        = esc_html(get_site_option('mo_idp_freshdesk_widget'));
$fd_widget_code         = esc_html(get_site_option('mo_idp_freshdesk_widget_code'));

$wp_comments_as_tickets = esc_html(get_site_option('mo_idp_wp_comments_tickets'));
$anonymous_tickets      = esc_html(get_site_option('mo_idp_allow_anonymous_tickets'));

$sp_exists 		= TRUE;
$post_url		= remove_query_arg (array('action','id'),esc_url_raw($_SERVER['REQUEST_URI']));

	$delete_url		= add_query_arg( array('action' => 'delete_sp_settings'	            ), esc_url_raw($_SERVER['REQUEST_URI']) ).'&id=';
	$settings_url 	= add_query_arg( array('action' => 'show_idp_settings'	            ), esc_url_raw($_SERVER['REQUEST_URI']) ).'&id=';
    $saml_doc  		= 'https://plugins.miniorange.com/step-by-step-guide-for-wordpress-saml-idp';
    $test_window= '';


    global $dbIDPQueries;

	$sp_list 		= $dbIDPQueries->get_sp_list();
	$page 			= esc_html($_GET['page']);
	$action 		= isset($_GET['action']) ? esc_html($_GET['action']) : '';

    $protocol_inuse = $action=="add_wsfed_app" ? "WSFED" : "SAML" ;

	$goback_url		= remove_query_arg (array('action','id'),esc_url_raw($_SERVER['REQUEST_URI']));
	$post_url		= remove_query_arg (array('action','id'),esc_url_raw($_SERVER['REQUEST_URI']));
	
	$delete_url		= add_query_arg( array('action' => 'delete_sp_settings'	            ), esc_url_raw($_SERVER['REQUEST_URI']) ).'&id=';
	$settings_url 	= add_query_arg( array('action' => 'show_idp_settings'	            ), esc_url_raw($_SERVER['REQUEST_URI']) ).'&id=';

	$saml_doc  		= 'https://plugins.miniorange.com/step-by-step-guide-for-wordpress-saml-idp';
	$wsfed_doc		= 'https://www.miniorange.com/office-365-single-sign-on-(sso)-using-wsfed-protocol';


	$sp_list 		= $dbIDPQueries->get_sp_list();
	$sp 			= !empty($sp_list) ? $sp_list[0] : null;

	$delete_sp		= false;
	$disabled 		= "";

	if(isset($action) && $action=='delete_sp_settings')
	{
		$sp 		 = $dbIDPQueries->get_sp_data(sanitize_text_field($_GET['id']));
		$delete_sp   = true;
		include MSFSI_DIR . 'views/idp-delete.php';
	}
	else if(!empty($sp_list))
	{
		$sp 		= $sp_list[0];
		$header		= 'EDIT '.(!empty($sp) ? $sp->mo_idp_sp_name : 'IDP' ).' SETTINGS';
		$sp_exists	= FALSE;
		$test_window= site_url(). '/?option=testConfig'.
                                    '&acs='.$sp->mo_idp_acs_url.
                                    '&issuer='.$sp->mo_idp_sp_issuer.
                                    '&defaultRelayState='.$sp->mo_idp_default_relayState;
	}	

	if(!$delete_sp)
	{
		include MSFSI_DIR. 'views/idp-freshdeskInt.php';
	}