<?php

namespace MSFSI\Actions;

use MSFSI\Exception\InvalidEncryptionCertException;
use MSFSI\Exception\InvalidOperationException;
use MSFSI\Exception\IssuerValueAlreadyInUseException;
use MSFSI\Exception\JSErrorException;
use MSFSI\Exception\NoServiceProviderConfiguredException;
use MSFSI\Exception\RequiredFieldsException;
use MSFSI\Exception\SPNameAlreadyInUseException;
use MSFSI\Exception\MetadataFileException;
use MSFSI\Exception\RequiredSpNameException;
use MSFSI\Handler\IDPSettingsHandler;
use MSFSI\Handler\SPSettingsHandler;
use MSFSI\Helper\Traits\Instance;
use MSFSI\Helper\Utilities\MoIDPUtility;
use MSFSI\Handler\FreshDeskIntegrationHandler;
use MSFSI\Handler\FeedbackHandler;
use MSFSI\Handler\SupportHandler;
use MSFSI\Handler\DemoRequestHandler; 


class SettingsActions extends BasePostAction
{
    use Instance;

    /** @var SPSettingsHandler $handler */
    private $handler;
    /** @var IDPSettingsHandler $idpSettingsHandler */
    private $idpSettingsHandler;
    /** @var FreshDeskIntegrationHandler $freshdeskIntegrationHandler */
    private $freshdeskIntegrationHandler;
    /** @var FeedbackHandler $feedbackHandler */
    private $feedbackHandler;
    /** @var SupportHandler $supportHandler */
    private $supportHandler;
    /** @var DemoRequestHandler $demoRequestHandler */ 
	private $demoRequestHandler; 

    public function __construct()
    {
		$this->handler = SPSettingsHandler::instance();
		$this->idpSettingsHandler = IDPSettingsHandler::instance();
        $this->feedbackHandler = FeedbackHandler::instance();
        $this->supportHandler = SupportHandler::instance();
        $this->demoRequestHandler = DemoRequestHandler::instance(); 
        $this->fd_settings_handler = FreshDeskIntegrationHandler::instance();
		$this->_nonce = 'idp_settings';
		parent::__construct();
    }

    private $funcs = array(
		'mo_add_idp',
        'mo_edit_idp',
        'mo_show_sp_settings',
        'mo_idp_delete_sp_settings',
        'mo_idp_entity_id',
        'change_name_id',
        'mo_idp_feedback_option',
        'mo_idp_use_new_cert',
        'saml_idp_upload_metadata', 
        'mo_idp_contact_us_query_option',
        'mo_idp_clear_pointers',
        'mo_idp_request_demo'
	);

	public function handle_post_data()
    {
		if ( current_user_can( 'manage_options' ) and isset( $_POST['option'] ) )
		{
			$option = sanitize_text_field(trim($_POST['option']));
			try{
				$this->route_post_data($option);
				$this->changeSPInSession($_POST);
			}catch (NoServiceProviderConfiguredException $e){
				do_action('mo_idp_show_message',$e->getMessage(),'ERROR');
			}catch (JSErrorException $e){
				do_action('mo_idp_show_message',$e->getMessage(),'ERROR');
			}catch (RequiredFieldsException $e){
				do_action('mo_idp_show_message',$e->getMessage(),'ERROR');
			}catch (SPNameAlreadyInUseException $e){
				do_action('mo_idp_show_message',$e->getMessage(),'ERROR');
			}catch (IssuerValueAlreadyInUseException $e){
				do_action('mo_idp_show_message',$e->getMessage(),'ERROR');
			}catch (InvalidEncryptionCertException $e){
				do_action('mo_idp_show_message',$e->getMessage(),'ERROR');
			}catch (InvalidOperationException $e){
				do_action('mo_idp_show_message',$e->getMessage(),'ERROR');
			}catch (MetadataFileException $e){
				do_action('mo_idp_show_message',$e->getMessage(),'ERROR');
			}catch (RequiredSpNameException $e){
				do_action('mo_idp_show_message',$e->getMessage(),'ERROR');
			}catch (\Exception $e){
				if(MSFSI_DEBUG) MoIDPUtility::mo_debug("Exception Occurred during SSO " . $e);
				wp_die(esc_html($e->getMessage()));
			}
		}
	}

    /**
     * @param $option
     * @throws InvalidEncryptionCertException
     * @throws IssuerValueAlreadyInUseException
     * @throws NoServiceProviderConfiguredException
     * @throws RequiredFieldsException
     * @throws SPNameAlreadyInUseException
     * @throws \MSFSI\Exception\SupportQueryRequiredFieldsException
     * @throws \MSFSI\Exception\MetadataFileException
     * @throws \MSFSI\Exception\RequiredSpNameException
     */
    public function route_post_data($option)
    {   
        switch ($option) {
            case $this->funcs[0]:
                $this->handler->_mo_idp_save_new_sp($_POST);
                break;
            case $this->funcs[1]:
                $this->handler->_mo_idp_edit_sp($_POST);
                break;
            case $this->funcs[2]:
                $this->handler->_mo_sp_change_settings($_POST);
                break;
            case $this->funcs[3]:
                $this->handler->mo_idp_delete_sp_settings($_POST);
                break;
            case $this->funcs[4]:
                $this->idpSettingsHandler->mo_change_idp_entity_id($_POST);
                break;
            case $this->funcs[5]:
                $this->handler->mo_idp_change_name_id($_POST);
                break;
            case $this->funcs[6]:
                $this->feedbackHandler->_mo_send_feedback($_POST); 
                break;
            case $this->funcs[7]:
                MoIDPUtility::useNewCerts();
                break;
            case $this->funcs[8]:
                $this->handler->_mo_idp_metadata_new_sp($_POST);
                break;
            case $this->funcs[9]:
                $this->supportHandler->_mo_idp_support_query($_POST);
                break;
            case $this->funcs[10]:
                $this->visualTourHandler->_mo_restart_tour($_POST);
                break;
            case $this->funcs[11]: 
                $this->demoRequestHandler->mo_idp_demo_Request_function($_POST);	 
                break;	
        }
    }

	public function changeSPInSession($POSTED)
    {
        MoIDPUtility::startSession();
        $_SESSION['SP'] = array_key_exists('service_provider', $POSTED) &&
        !MoIDPUtility::isBlank($POSTED['service_provider']) ? sanitize_text_field($POSTED['service_provider']) : 1;
    }
}