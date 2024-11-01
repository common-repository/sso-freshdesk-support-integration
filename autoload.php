<?php

use MSFSI\Helper\Utilities\TabDetails;
use MSFSI\Helper\Utilities\Tabs;
use MSFSI\SplClassLoader;

define('MSFSI_VERSION', '1.1.0');
define('MSFSI_DB_VERSION', '1.4');
define('MSFSI_DIR', plugin_dir_path(__FILE__));
define('MSFSI_URL', plugin_dir_url(__FILE__));
define('MSFSI_CSS_URL', MSFSI_URL . 'includes/css/mo_idp_style.min.css?version='.MSFSI_VERSION);
define('MSFSI_JS_URL', MSFSI_URL . 'includes/js/settings.min.js?version='.MSFSI_VERSION);
define('MSFSI_PRICING_JS_URL', MSFSI_URL . 'includes/js/pricing.min.js?version='.MSFSI_VERSION);
define('MSFSI_ICON', MSFSI_URL . 'includes/images/miniorange_icon.png');
define('MSFSI_LOGO_URL', MSFSI_URL . 'includes/images/logo.png');
define('MSFSI_LOADER', MSFSI_URL . 'includes/images/loader.gif');
define('MSFSI_TEST', FALSE);
define('MSFSI_DEBUG', FALSE);
define('MSFSI_LK_DEBUG', FALSE);

includeLibFiles();

function includeLibFiles()
{
    if(!class_exists("RobRichards\XMLSecLibs\XMLSecurityKey")) include 'helper/common/XMLSecurityKey.php';
    if(!class_exists("RobRichards\XMLSecLibs\XMLSecEnc")) include 'helper/common/XMLSecEnc.php';
    if(!class_exists("RobRichards\XMLSecLibs\XMLSecurityDSig")) include 'helper/common/XMLSecurityDSig.php';
}

function getRegistrationURL()
{
    return add_query_arg(
        [ 'page' => TabDetails::instance()->_tabDetails[Tabs::PROFILE]->_menuSlug ],
        esc_url_raw($_SERVER['REQUEST_URI'])
    );
}

include "SplClassLoader.php";
/** @var SplClassLoader $idpClassLoader */
$idpClassLoader = new SplClassLoader('MSFSI', realpath(__DIR__ . DIRECTORY_SEPARATOR . '..'));
$idpClassLoader->register();


