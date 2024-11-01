<?php

namespace MSFSI;

use MSFSI\Actions\RegistrationActions;
use MSFSI\Actions\SettingsActions;
use MSFSI\Actions\SSOActions;
use MSFSI\Helper\Constants\MoIdPDisplayMessages;
use MSFSI\Helper\Database\MoDbQueries;
use MSFSI\Helper\Traits\Instance;
use MSFSI\Helper\Utilities\MenuItems;
use MSFSI\Helper\Utilities\MoIDPUtility;
use MSFSI\Helper\Utilities\RewriteRules;
use MSFSI\Helper\Utilities\TabDetails;
use MSFSI\Helper\Utilities\Tabs;
use MSFSI\VisualTour\PointersManager;

final class MoIDP
{
    use Instance;

    /** Private constructor to avoid direct object creation */
    private function __construct()
    {
        $this->initializeGlobalVariables();
        $this->initializeActions();
        $this->addHooks();
    }

    function initializeGlobalVariables()
    {
        global $dbIDPQueries;
        $dbIDPQueries = MoDbQueries::instance();
    }

    function addHooks()
    {
        add_action( 'mo_idp_show_message',  		        array( $this, 'mo_show_message' 				), 1 , 2 );
        add_action( 'admin_menu', 					        array( $this, 'mo_idp_menu' 					) 		 );
        add_action( 'admin_enqueue_scripts', 		        array( $this, 'mo_idp_plugin_settings_style' 	) 		 );
        add_action( 'admin_enqueue_scripts', 		        array( $this, 'mo_idp_plugin_settings_script' 	) 		 );
        add_action( 'enqueue_scripts', 				        array( $this, 'mo_idp_plugin_settings_style' 	) 		 );
        add_action( 'enqueue_scripts', 				        array( $this, 'mo_idp_plugin_settings_script' 	) 		 );
        add_action( 'admin_footer',                         array( $this, 'feedback_request'  				)        );
        add_action( 'activated_plugin',                     array( $this, 'idp_activate_redirect'           )        );
        add_filter( 'plugin_action_links_'.MSFSI_PLUGIN_NAME, array($this , 'mo_idp_plugin_anchor_links'    )        );
        register_activation_hook  ( MSFSI_PLUGIN_NAME, 	    array( $this, 'mo_plugin_activate'			    )        );
    }

    function mo_idp_plugin_anchor_links($links){
        if(array_key_exists("deactivate", $links))
        {
            $links_array = array(
                "Premium Plans"    =>     "idp_upgrade_settings",
                "Settings"         =>     "freshdesk_integration"
            );

            foreach($links_array as $item => $value){
                $item_url = admin_url("admin.php?page=$value");
                $item_link = '<a href="'.$item_url.'">'.$item.'</a>';
                array_unshift($links,$item_link);
            }
        }
        return $links;
    }

    function initializeActions()
    {
        RewriteRules::instance();
        SettingsActions::instance();
        SSOActions::instance();
        RegistrationActions::instance();

    }

    function mo_idp_menu()
    {
        new MenuItems($this);
    }

    function mo_sp_settings()
    {
        include 'controllers/sso-main-controller.php';
    }

    function mo_idp_plugin_settings_style($hook_suffix)
    {
        if( $hook_suffix=="toplevel_page_idp_settings" || $hook_suffix == "plugins.php" 
            || preg_match('/(wp-freshdesk-integration_page_)/',$hook_suffix) ){
            wp_enqueue_style( 'mo_idp_admin_settings_style'	,MSFSI_CSS_URL);
            wp_enqueue_style( 'wp-pointer' );
        }
    }

    function mo_idp_plugin_settings_script($hook_suffix)
    {
        if( $hook_suffix=="toplevel_page_idp_settings" || $hook_suffix == "plugins.php" 
            || preg_match('/(wp-freshdesk-integration_page_)/',$hook_suffix) ){
            wp_enqueue_script( 'mo_idp_admin_settings_script', MSFSI_JS_URL, array('jquery') );
        }
    }

    function mo_plugin_activate()
    {
        /** @var MoDbQueries $dbIDPQueries */
        global $dbIDPQueries;
        $dbIDPQueries->checkTablesAndRunQueries();
        if (!get_site_option("mo_idp_new_certs"))
        {
            MoIDPUtility::useNewCerts();
        }
        $metadata_dir		= MSFSI_DIR . "metadata.xml";
        if (file_exists($metadata_dir) && filesize($metadata_dir) > 0) {
            unlink($metadata_dir);
            MoIDPUtility::createMetadataFile();
        }
        if (get_site_option("idp_keep_settings_intact", NULL) === NULL)
        {
            update_site_option( "idp_keep_settings_intact", TRUE );
        }
    }

    function idp_activate_redirect(){
        exit(wp_safe_redirect(self_admin_url("admin.php?page=freshdesk_integration")));
    }

    function mo_show_message($content,$type)
    {
        new MoIdPDisplayMessages($content, $type);
    }

    function feedback_request()
    {
        if( basename($_SERVER['PHP_SELF'])=="plugins.php" ){
            include MSFSI_DIR . 'controllers/feedback.php';
        }
    }

}