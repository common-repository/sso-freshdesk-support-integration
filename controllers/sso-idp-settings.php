<?php

    /** @global \MSFSI\Helper\Database\MoDbQueries $dbIDPQueries */
    global $dbIDPQueries;

	$sp_list 		= $dbIDPQueries->get_sp_list();
	$page 			= isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';
	$action 		= isset($_GET['action']) ? sanitize_text_field($_GET['action']) : '';

    $protocol_type	= sanitize_text_field($_GET['var']);

	$goback_url		= remove_query_arg (array('action','id'),esc_url_raw($_SERVER['REQUEST_URI']));
	$post_url		= remove_query_arg (array('action','id'),esc_url_raw($_SERVER['REQUEST_URI']));
	

	$sp_page_url	= add_query_arg( array('page' 	=> $spSettingsTabDetails->_menuSlug ), esc_url_raw($_SERVER['REQUEST_URI']) );
	$delete_url		= add_query_arg( array('action' => 'delete_sp_settings'	            ), esc_url_raw($_SERVER['REQUEST_URI']) ).'&id=';
	$settings_url 	= add_query_arg( array('action' => 'show_idp_settings'	            ), esc_url_raw($_SERVER['REQUEST_URI']) ).'&id=';

	$saml_doc  		= 'https://plugins.miniorange.com/step-by-step-guide-for-wordpress-saml-idp';
	$wsfed_doc		= 'https://www.miniorange.com/office-365-single-sign-on-(sso)-using-wsfed-protocol';

	$sp_exists 		= TRUE;
	$disabled       = "";
	
	if(isset($action) && $action=='delete_sp_settings')
	{
		$sp 		 = $dbIDPQueries->get_sp_data(sanitize_text_field($_GET['id']));
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

        if($sp->mo_idp_protocol_type=="WSFED")
            include MSFSI_DIR . 'views/idp-wsfed-settings.php';
        else
            include MSFSI_DIR . 'views/idp-settings.php';
	}
	else
	{
        $sp          = empty($sp_list) ? '' : $sp_list[0];
        $header		 = $protocol_inuse=="SAML" ? 'ADD NEW SAML SERVICE PROVIDER' : 'ADD NEW WS-FED SERVICE PROVIDER' ;
		$test_window = '';
        if($protocol_inuse=="WSFED")
            include MSFSI_DIR . 'views/idp-wsfed-settings.php';
        else
            include MSFSI_DIR . 'views/idp-settings.php';
	}