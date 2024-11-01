<?php

use MSFSI\Helper\Constants\MoIDPConstants;
use MSFSI\Helper\Utilities\MoIDPUtility;


$idp_settings	= add_query_arg( array('page' => $freshdeskIntDetails->_menuSlug   ),  esc_url_raw($_SERVER['REQUEST_URI']) );
$sp_settings	= add_query_arg( array('page' => $metadataTabDetails->_menuSlug	    ), esc_url_raw($_SERVER['REQUEST_URI']) );
$attr_settings	= add_query_arg( array('page' => $attrMapTabDetails->_menuSlug		), esc_url_raw($_SERVER['REQUEST_URI']) );
$freshdesk_url  = add_query_arg( array('page' => $freshdeskIntDetails->_menuSlug    ), esc_url_raw($_SERVER['REQUEST_URI']) );
$help_url       = MoIDPConstants::FAQ_URL;
$support_url    = add_query_arg( array('page' => $supportSection->_menuSlug         ), esc_url_raw($_SERVER['REQUEST_URI'] ));
$demoRequest_url= add_query_arg( array('page' => $demoRequestTabDetails->_menuSlug  ), esc_url_raw($_SERVER['REQUEST_URI'] )); 
$login_settings	= add_query_arg( array('page' => $settingsTabDetails->_menuSlug	    ), esc_url_raw($_SERVER['REQUEST_URI'] )); 
$profile_url	= add_query_arg( array('page' => $profileTabDetails->_menuSlug      ), esc_url_raw($_SERVER['REQUEST_URI'] ));
$license_url 	= add_query_arg( array('page' => $licenseTabDetails->_menuSlug	    ), esc_url_raw($_SERVER['REQUEST_URI'] ));
$register_url	= add_query_arg( array('page' => $profileTabDetails->_menuSlug	    ), esc_url_raw($_SERVER['REQUEST_URI'] ));

$active_tab 	= sanitize_text_field($_GET['page']);

$useNewCert 	= MoIDPUtility::checkCertExpiry();
if ($useNewCert == TRUE)
    update_site_option ("mo_idp_new_certs", FALSE);

include MSFSI_DIR . 'views/navbar.php';