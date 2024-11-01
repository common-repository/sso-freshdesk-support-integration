<?php

namespace MSFSI\Handler;

use MSFSI\Helper\Constants\MoIDPMessages;
use MSFSI\Helper\Traits\Instance;
use MSFSI\Helper\Utilities\MoIDPUtility;
use MSFSI\Helper\Utilities\SAMLUtilities;
use MSFSI\Helper\SAML2\MetadataReader;
use MSFSI\Exception\MetadataFileException;
use MSFSI\Exception\RequiredSpNameException;
use MSFSI\Handler\FreshDeskIntegrationHandler;

final class SPSettingsHandler extends SPSettingsUtility
{
    use Instance;

    /** Private constructor to avoid direct object creation */
    private function __construct(){}

	/**
     * @param $POSTED
     * @throws \MSFSI\Exception\MetadataFileException
     * @throws \MSFSI\Exception\RequiredSpNameException
     */
	public function _mo_idp_metadata_new_sp($POSTED)
	{
		$file ="";
		if (!empty( $POSTED['idp_sp_name'])) 
		{
			if (isset( $POSTED['metadata_url'] ) and !empty( $POSTED['metadata_url'] ) ) 
			{
				if(!MoIDPUtility::isCurlInstalled()) 
				{
					do_action('mo_idp_show_message',MoIDPMessages::showMessage('CURL_ERROR'),'ERROR'); 
				}
				
				$url = filter_var( $POSTED['metadata_url'], FILTER_SANITIZE_URL );
				$response = MoIDPUtility::mo_saml_wp_remote_get($url, array('sslverify'=>false));
				if(!is_null($response))
				{
					$file = $response['body'];
				}
				else 
				{
					throw new MetadataFileException();
				}
			if(!is_null($file))
				$this->upload_metadata($file, $POSTED);
			}else if(isset( $_FILES['metadata_file'] ) and !empty( $_FILES['metadata_file'] )) 
			{
				$file = @file_get_contents(sanitize_textarea_field($_FILES['metadata_file']['tmp_name']));
				if(!is_null($file))
				$this->upload_metadata($file, $POSTED);
			}
			else 
			{
				throw new MetadataFileException();
			}
		}
		else 
		{
			throw new RequiredSpNameException();
		}	
	}

	function upload_metadata($file, $POSTED)
	{

		global $dbIDPQueries;
		$old_error_handler = set_error_handler( array( $this, 'handleXmlError' ) );
		$document = new \DOMDocument();
		$document->loadXML($file);
		restore_error_handler();

		$first_child = $document->firstChild;

		if ( ! empty( $first_child ) ) {

			$metadata = new MetadataReader($document);
			$service_providers = $metadata->getServiceProviders();
			
			if ( ! preg_match( "/^\w*$/", $POSTED['idp_sp_name'] ) ) {
				do_action('mo_idp_show_message',MoIDPMessages::showMessage('SP_NAME_INVALID'),'ERROR');
				return;
			}
			if ( empty( $service_providers ) && !empty($POSTED['metadata_url']) ) {
				do_action('mo_idp_show_message',MoIDPMessages::showMessage('METADATA_URL_INVALID'),'ERROR');
				return;
			}
			
			foreach ( $service_providers as $key => $sp ) {
				$entityID = $sp->getEntityID();
				$acsUrl = $sp->getAcsURL();
				$nameID = $sp->getNameID();
				$signed = $sp->getSignedRequest();				
			} 
			
			$where = $data = array();
			$check = $where['mo_idp_sp_name'] = $data['mo_idp_sp_name'] = sanitize_text_field($POSTED['idp_sp_name']);
	
			$this->checkNameALreadyInUse($check);
			$this->checkIssuerAlreadyInUse($entityID,NULL,$check);


			$data['mo_idp_protocol_type']		= sanitize_text_field($POSTED['mo_idp_protocol_type']);
			$data['mo_idp_sp_issuer']			= $entityID;
			$data['mo_idp_acs_url'] 			= $acsUrl;
			$data['mo_idp_nameid_format'] 		= $nameID;
	
			$data['mo_idp_logout_url'] 			= NULL;
			$data['mo_idp_cert'] 				= NULL;
			$data['mo_idp_cert_encrypt'] 		= NULL;
			$data['mo_idp_default_relayState'] 	= NULL;
			$data['mo_idp_logout_binding_type'] = 'HttpRedirect';
	
			$data['mo_idp_response_signed'] 	= NULL;
			$data['mo_idp_assertion_signed'] 	= ($signed == "true") 		? 1 		: NULL;
			$data['mo_idp_encrypted_assertion'] = NULL;

			$count=$dbIDPQueries->get_sp_count();
			if($count>=1){
				$dbIDPQueries->update_metdata_data();
			}

			$insert = $dbIDPQueries->insert_sp_data($data);

			do_action('mo_idp_show_message',MoIDPMessages::showMessage('SETTINGS_SAVED'),'SUCCESS'); 
		} 
		else{
			if(!empty($POSTED['metadata_url'])){
				do_action('mo_idp_show_message',MoIDPMessages::showMessage('METADATA_URL_INVALID'),'ERROR');
			}
		}
		
	}

    /**
     * @param $POSTED
     * @throws \MSFSI\Exception\IssuerValueAlreadyInUseException
     * @throws \MSFSI\Exception\RequiredFieldsException
     * @throws \MSFSI\Exception\SPNameAlreadyInUseException
     * @throws \MSFSI\Exception\InvalidEncryptionCertException
     */
    public function _mo_idp_save_new_sp($POSTED) 
	{
        /** @global \MSFSI\Helper\Database\MoDbQueries $dbIDPQueries */
		global $dbIDPQueries;

		$fd_settings_handler = FreshDeskIntegrationHandler::instance();
		
		if(isset($POSTED['idp_sp_name'])) 
		{
			$this->checkIfValidPlugin();
			$this->checkIfRequiredFieldsEmpty(array('idp_sp_name'=>$POSTED, 'idp_sp_issuer'=>$POSTED,
													'idp_acs_url'=>$POSTED, 'idp_nameid_format'=>$POSTED));

			$where = $data = array();
			$check = $where['mo_idp_sp_name'] = $data['mo_idp_sp_name'] = sanitize_text_field($POSTED['idp_sp_name']);
			$issuer = $data['mo_idp_sp_issuer'] = sanitize_text_field($POSTED['idp_sp_issuer']);

			$this->checkIssuerAlreadyInUse($issuer, NULL, $check);
			$sp_exists = $this->checkSPAlreadyExists($check);


			$data = $this->collectData($POSTED, $data);

			$insert = $dbIDPQueries->insert_sp_data($data);
		}

		$fd_settings_handler->mo_idp_fd_integrator($POSTED);
		do_action('mo_idp_show_message', MoIDPMessages::showMessage('SETTINGS_SAVED'), 'SUCCESS');
	}

    /**
     * @param $POSTED
     * @throws \MSFSI\Exception\IssuerValueAlreadyInUseException
     * @throws \MSFSI\Exception\NoServiceProviderConfiguredException
     * @throws \MSFSI\Exception\RequiredFieldsException
     * @throws \MSFSI\Exception\SPNameAlreadyInUseException
     * @throws \MSFSI\Exception\InvalidEncryptionCertException
     */
    public function _mo_idp_edit_sp($POSTED)
	{
        /** @global \MSFSI\Helper\Database\MoDbQueries $dbIDPQueries */
		global $dbIDPQueries;

		$fd_settings_handler = FreshDeskIntegrationHandler::instance();

		if(isset($POSTED['idp_sp_name'])) {
			$this->checkIfValidPlugin();
			$this->checkIfRequiredFieldsEmpty(array('idp_sp_name'=>$POSTED, 'idp_sp_issuer'=>$POSTED,
													'idp_acs_url'=>$POSTED, 'idp_nameid_format'=>$POSTED));
			$this->checkIfValidServiceProvider($POSTED,TRUE,'service_provider');

			$data 		= $where 					= array();
			$id 		= $where['id'] 				= sanitize_text_field( $POSTED['service_provider']);
			$check 		= $data['mo_idp_sp_name'] 	= sanitize_text_field( $POSTED['idp_sp_name']	);
			$issuer 	= $data['mo_idp_sp_issuer'] = sanitize_text_field( $POSTED['idp_sp_issuer']	);

			$this->checkIfValidServiceProvider($dbIDPQueries->get_sp_data($id));
			$this->checkIssuerAlreadyInUse($issuer, $id, NULL);
			$this->checkNameALreadyInUse($check, $id);

			$data = $this->collectData($POSTED, $data);

			$dbIDPQueries->update_sp_data($data, $where);
		}

		$fd_settings_handler->mo_idp_fd_integrator($POSTED);

		do_action('mo_idp_show_message', MoIDPMessages::showMessage('SETTINGS_SAVED'), 'SUCCESS');
	}

	public function mo_idp_delete_sp_settings($POSTED)
	{
        /** @global \MSFSI\Helper\Database\MoDbQueries $dbIDPQueries */
		global $dbIDPQueries;

		MoIDPUtility::startSession();
		$this->checkIfValidPlugin();

		$spWhere 					= array();
		$spWhere['id'] 				= sanitize_text_field($POSTED['sp_id']);
		$spAttrWhere['mo_sp_id'] 	= sanitize_text_field($POSTED['sp_id']);

		$dbIDPQueries->delete_sp($spWhere, $spAttrWhere);

		if(array_key_exists('SP',$_SESSION)) unset($_SESSION['SP']);

		do_action('mo_idp_show_message', MoIDPMessages::showMessage('SP_DELETED'), 'SUCCESS');
	}

    /**
     * @param $POSTED
     * @throws \MSFSI\Exception\NoServiceProviderConfiguredException
     */
    public function mo_idp_change_name_id($POSTED)
	{
        /** @global \MSFSI\Helper\Database\MoDbQueries $dbIDPQueries */
		global $dbIDPQueries;

		$this->checkIfValidPlugin();
		$this->checkIfValidServiceProvider($POSTED,TRUE,'service_provider');

		$data 						= $where 		= array();
		$sp_id 						= $where['id'] 	= sanitize_text_field($POSTED['service_provider']);
		$data['mo_idp_nameid_attr'] = sanitize_text_field($POSTED['idp_nameid_attr']);
		$dbIDPQueries->update_sp_data($data, $where);
		do_action('mo_idp_show_message', MoIDPMessages::showMessage('SETTINGS_SAVED'), 'SUCCESS');
	}

    /**
     * @param $POSTED
     * @throws \MSFSI\Exception\NoServiceProviderConfiguredException
     */
    public function _mo_sp_change_settings($POSTED)
	{
		$this->checkIfValidPlugin();
		$this->checkIfValidServiceProvider($POSTED, TRUE, 'service_provider');
	}

    /**
     * @param $POSTED
     * @param $data
     * @return mixed
     * @throws \MSFSI\Exception\InvalidEncryptionCertException
     */
    private function collectData($POSTED, $data)
	{
		$data['mo_idp_acs_url'] 			= esc_url_raw($POSTED['idp_acs_url']);
		$data['mo_idp_nameid_format'] 		= sanitize_text_field($POSTED['idp_nameid_format']);
		$data['mo_idp_protocol_type']		= sanitize_text_field($POSTED['mo_idp_protocol_type']);

		$data['mo_idp_logout_url'] 			= NULL;
		$data['mo_idp_cert'] 				= !empty($POSTED['mo_idp_cert']) 				? SAMLUtilities::sanitize_certificate(trim($POSTED['mo_idp_cert'])) 		: NULL;
		$data['mo_idp_cert_encrypt'] 		= NULL;
		$data['mo_idp_default_relayState'] 	= !empty($POSTED['idp_default_relayState']) 	? esc_url_raw($POSTED['idp_default_relayState'])                            : NULL;
		$data['mo_idp_logout_binding_type'] = !empty($POSTED['mo_idp_logout_binding_type']) ? sanitize_text_field($POSTED['mo_idp_logout_binding_type']) 									: 'HttpRedirect';

		$data['mo_idp_response_signed'] 	= NULL;
		$data['mo_idp_assertion_signed'] 	= isset($POSTED['idp_assertion_signed']) 		? sanitize_text_field($POSTED['idp_assertion_signed']) 		: NULL;
		$data['mo_idp_encrypted_assertion'] = NULL;

		$this->checkIfValidEncryptionCertProvided($data['mo_idp_encrypted_assertion'], $data['mo_idp_cert_encrypt']);

		return $data;
	}

	function handleXmlError( $errno, $errstr, $errfile, $errline ) {
		if ( $errno == E_WARNING && ( substr_count( $errstr, "DOMDocument::loadXML()" ) > 0 ) ) {
			return;
		} else {
			return false;
		}
	}
}