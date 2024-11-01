<?php

/*
Plugin Name: WP Freshdesk Integration
Plugin URI: https://plugins.miniorange.com/
Description: Connect your Freshdesk with WordPress for SSO and to raise support tickets.
Version: 1.1.0
Author: miniOrange
Author URI: https://miniorange.com/
*/
if(! defined( 'ABSPATH' )) exit;
define('MSFSI_PLUGIN_NAME', plugin_basename(__FILE__));
$dirName = substr(MSFSI_PLUGIN_NAME, 0, strpos(MSFSI_PLUGIN_NAME, "/"));
define('MSFSI_NAME', $dirName);
include 'autoload.php'; 
MSFSI\MoIDP::instance();