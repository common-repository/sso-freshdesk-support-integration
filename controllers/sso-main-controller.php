<?php

use MSFSI\Helper\Utilities\MoIDPUtility;
use MSFSI\Helper\Utilities\TabDetails;
use MSFSI\Helper\Utilities\Tabs;

$registered = MoIDPUtility::micr();
$verified 	= MoIDPUtility::iclv();
$controller = MSFSI_DIR . 'controllers/';
/** @var TabDetails $tabs */
$tabs = TabDetails::instance();
$tabDetails = $tabs->_tabDetails;
$parentSlug = $tabs->_parentSlug;


/** @var \MSFSI\Helper\Utilities\PluginPageDetails $metadataTabDetails */
$metadataTabDetails = $tabDetails[Tabs::METADATA];
/** @var \MSFSI\Helper\Utilities\PluginPageDetails $attrMapTabDetails */
$attrMapTabDetails = $tabDetails[Tabs::ATTR_SETTINGS];
/** @var \MSFSI\Helper\Utilities\PluginPageDetails $freshdeskIntDetails */
$freshdeskIntDetails = $tabDetails[Tabs::FRESHDESK_INT];
/** @var \IDP\Helper\Utilities\PluginPageDetails $supportSection */
$supportSection = $tabDetails[Tabs::SUPPORT];
/** @var \IDP\Helper\Utilities\PluginPageDetails $demoRequestTabDetails */  
$demoRequestTabDetails = $tabDetails[Tabs::DEMO_REQUEST]; 
/** @var \IDP\Helper\Utilities\PluginPageDetails $settingsTabDetails */
$settingsTabDetails = $tabDetails[Tabs::SIGN_IN_SETTINGS];
/** @var  \IDP\Helper\Utilities\PluginPageDetails $licenseTabDetails */
$licenseTabDetails = $tabDetails[Tabs::LICENSE];

/** @var \IDP\Helper\Utilities\PluginPageDetails $profileTabDetails */
$profileTabDetails = $tabDetails[Tabs::PROFILE];

include MSFSI_DIR 	 . 'views/common-elements.php';
include MSFSI_DIR 	 . 'controllers/sso-idp-navbar.php';

if( isset( $_GET[ 'page' ]))
{   
    $account = $registered ? 'sso-idp-profile.php' : 'sso-idp-registration.php';
    switch(sanitize_text_field($_GET['page']))
    {
        case $metadataTabDetails->_menuSlug:
            include $controller . 'sso-idp-data.php';			break;
        case $freshdeskIntDetails->_menuSlug:
            include $controller . 'sso-fd-integration.php';     break;
        case $attrMapTabDetails->_menuSlug:
            include $controller . 'sso-attr-settings.php';		break;
        case $profileTabDetails->_menuSlug:
            include $controller . $account;		                break;
        case $parentSlug:
            include $controller . 'plugin-details.php';         break;
        case $settingsTabDetails->_menuSlug:
            include $controller . 'sso-signin-settings.php';	break;
        case $supportSection->_menuSlug:
            include $controller . 'sso-idp-support.php';        break;
        case $demoRequestTabDetails->_menuSlug:  
            include $controller . 'sso-idp-request-demo.php';   break;
        case $licenseTabDetails->_menuSlug:
            include $controller . 'sso-pricing.php';			break;
    }
    include $controller . 'contact-button.php';
}

echo '<form name="f" method="post" id="mo_idp_show_pointers">
        <input type="hidden" name="option" value="mo_idp_clear_pointers"/>
      </form>';
