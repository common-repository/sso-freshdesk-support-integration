<?php

namespace MSFSI\Actions;

use MSFSI\Exception\InvalidRequestInstantException;
use MSFSI\Exception\InvalidRequestVersionException;
use MSFSI\Exception\InvalidServiceProviderException;
use MSFSI\Exception\InvalidSSOUserException;
use MSFSI\Handler\ProcessRequestHandler;
use MSFSI\Handler\ReadRequestHandler;
use MSFSI\Handler\SendResponseHandler;
use MSFSI\Helper\Constants\MoIDPConstants;
use MSFSI\Helper\Constants\MoIDPMessages;
use MSFSI\Helper\SAML2\AuthnRequest;
use MSFSI\Helper\Traits\Instance;
use MSFSI\Helper\Utilities\MoIDPUtility;
use MSFSI\Helper\Utilities\SAMLUtilities;

class SSOActions
{
    use Instance;

    /** @var ReadRequestHandler $readRequestHandler  */
    private $readRequestHandler;
    /** @var SendResponseHandler $sendResponseHandler */
    private $sendResponseHandler;
    /** @var ProcessRequestHandler $requestProcessHandler*/
    private $requestProcessHandler;

    private function __construct()
    {
        $this->readRequestHandler = ReadRequestHandler::instance();
        $this->sendResponseHandler = SendResponseHandler::instance();
        $this->requestProcessHandler = ProcessRequestHandler::instance();

        add_action( 'init'					 , array( $this, '_handle_SSO' 					)		);
        add_action( 'wp_login'				 , array( $this, 'mo_idp_handle_post_login'		) , 99	);
    }

	private $requestParams = array (
		'SAMLRequest',
		'option',
		'wtrealm',   	//checking wtrealm instead of clientRequestId as it is optional
	);

	public function _handle_SSO()
	{
		$REQUESTED = array();
		foreach($_REQUEST as $key => $value) {
            $REQUESTED[$key] = sanitize_text_field($value);
        }
		$keys 		=  array_keys($REQUESTED) ?? array();
		$operation 	= array_intersect($keys,$this->requestParams);
		if(count($operation) <= 0) return;
		try{
			$this->_route_data(array_values($operation)[0]);
		}catch(InvalidRequestInstantException $e){
			if(MSFSI_DEBUG) MoIDPUtility::mo_debug("Exception Occurred during SSO " . $e);
			wp_die(esc_html($e->getMessage()));
		}catch(InvalidRequestVersionException $e){
			if(MSFSI_DEBUG) MoIDPUtility::mo_debug("Exception Occurred during SSO " . $e);
			wp_die(esc_html($e->getMessage()));
		}catch(InvalidServiceProviderException $e){
			if(MSFSI_DEBUG) MoIDPUtility::mo_debug("Exception Occurred during SSO " . $e);
			wp_die(esc_html($e->getMessage()));
		}catch(InvalidSSOUserException $e){
			if(MSFSI_DEBUG) MoIDPUtility::mo_debug("Exception Occurred during SSO " . $e);
			wp_die(esc_html($e->getMessage()));
		}catch (\Exception $e){
			if(MSFSI_DEBUG) MoIDPUtility::mo_debug("Exception Occurred during SSO " . $e);
			wp_die(esc_html($e->getMessage()));
		}
	}

    /**
     * @param $op
     * @throws InvalidServiceProviderException
     * @throws InvalidSSOUserException
     */
    public function _route_data($op)
	{
		$REQUESTS = array();
		$GETS = array();
		foreach($_REQUEST as $key => $value) {
            $REQUESTS[$key] = sanitize_text_field($value);
        }
		foreach($_GET as $key => $value) {
            $GETS[$key] = sanitize_text_field($value);
        }
		switch ($op)
		{
			case $this->requestParams[0]:
				$this->readRequestHandler->_read_request($REQUESTS,$GETS,MoIDPConstants::SAML);		        break;
			case $this->requestParams[1]:
				$this->_initiate_saml_response($REQUESTS);								break;
			case $this->requestParams[2]:
				$this->readRequestHandler->_read_request($REQUESTS,$GETS,MoIDPConstants::WS_FED);		    break;
		}
	}

	public function mo_idp_handle_post_login($login)
	{
		if(array_key_exists('response_params', $_COOKIE) && !MoIDPUtility::isBlank(sanitize_text_field($_COOKIE['response_params'])))
		{
			try{
				$moIdpsendSAMLResponse = isset($_COOKIE['moIdpsendSAMLResponse']) ? sanitize_text_field($_COOKIE['moIdpsendSAMLResponse']):null;
				if($moIdpsendSAMLResponse && strcmp( sanitize_text_field($_COOKIE['moIdpsendSAMLResponse']), 'true') == 0)
					$this->sendResponseHandler->mo_idp_send_reponse ([
                            'requestType' => MoIDPConstants::AUTHN_REQUEST,
                            'acs_url' 	  => sanitize_text_field($_COOKIE['acs_url']),
                            'issuer' 	  => sanitize_text_field($_COOKIE['audience']),
                            'relayState'  => sanitize_text_field($_COOKIE['relayState']),
                            'requestID'   => sanitize_text_field($_COOKIE['requestID'])
                    ], $login);
				
				$moIdpsendWsFedResponse = isset($_COOKIE['moIdpsendWsFedResponse']) ? sanitize_text_field($_COOKIE['moIdpsendWsFedResponse']):null;
				if($moIdpsendWsFedResponse && strcmp( sanitize_text_field($_COOKIE['moIdpsendWsFedResponse']), 'true') == 0)
					$this->sendResponseHandler->mo_idp_send_reponse ([
                            'requestType' 		=> MoIDPConstants::WS_FED,
                            'clientRequestId' 	=> sanitize_text_field($_COOKIE['clientRequestId']),
                            'wtrealm' 	  		=> sanitize_text_field($_COOKIE['wtrealm']),
                            'wa'	 			=> sanitize_text_field($_COOKIE['wa']),
                            'relayState' 		=> sanitize_text_field($_COOKIE['relayState']),
                            'wctx' 				=> sanitize_text_field($_COOKIE['wctx'])
                    ], $login);

			}catch(InvalidSSOUserException $e){
				if(MSFSI_DEBUG) MoIDPUtility::mo_debug("Exception Occurred during SSO " . $e);
				wp_die($e->getMessage());
			}
		}
	}

    /**
     * @param $REQUEST
     * @throws InvalidSSOUserException
     */
    private function _initiate_saml_response($REQUEST)
	{
		if ($REQUEST['option']=='testConfig')
			$this->sendSAMLResponseBasedOnRequestData($REQUEST);
		elseif ($REQUEST['option']==='saml_user_login')
			$this->sendSAMLResponseBasedOnSPName($REQUEST['sp'],$REQUEST['relayState']);
		elseif ($REQUEST['option']==='mo_idp_metadata')
			MoIDPUtility::showMetadata();
	}

    /**
     * @param $REQUEST
     * @throws InvalidSSOUserException
     */
    private function sendSAMLResponseBasedOnRequestData($REQUEST)
	{
		$defaultRelayState = !array_key_exists('defaultRelayState',$REQUEST)
								 || MoIDPUtility::isBlank($REQUEST['defaultRelayState']) ? '/' : sanitize_text_field($REQUEST['defaultRelayState']);
		$this->sendResponseHandler->mo_idp_send_reponse ([
            'requestType' => MoIDPConstants::AUTHN_REQUEST,
            'acs_url' 	  => sanitize_text_field($REQUEST['acs']),
            'issuer' 	  => sanitize_text_field($REQUEST['issuer']),
            'relayState'  => $defaultRelayState
        ]);
	}

    /**
     * @param $spName
     * @param $relayState
     * @throws InvalidSSOUserException
     */
    private function sendSAMLResponseBasedOnSPName($spName, $relayState)
	{
        /** @global \MSFSI\Helper\Database\MoDbQueries $dbIDPQueries */
		global $dbIDPQueries;
		$sp = $dbIDPQueries->get_sp_from_name($spName);
		if (!MoIDPUtility::isBlank($sp))
		{
			$defaultRelayState = !MoIDPUtility::isBlank($relayState) ? $relayState
								: ( MoIDPUtility::isBlank($sp->mo_idp_default_relayState) ? '/' : $sp->mo_idp_default_relayState );

            if(!is_user_logged_in()) {
                $requestObj = new AuthnRequest();
                $requestObj = $requestObj->setAssertionConsumerServiceURL($sp->mo_idp_acs_url)
                    ->setIssuer($sp->mo_idp_sp_issuer)
                    ->setRequestID(null);
                $this->requestProcessHandler->setSAMLSessionCookies( $requestObj, $defaultRelayState );
            }

			$this->sendResponseHandler->mo_idp_send_reponse ([
                'requestType' => MoIDPConstants::AUTHN_REQUEST,
                'acs_url' 	 => $sp->mo_idp_acs_url,
                'issuer' 	 => $sp->mo_idp_sp_issuer,
                'relayState' => $defaultRelayState
            ]);
		}
	}
}