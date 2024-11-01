<?php

namespace MSFSI\Actions;

use MSFSI\Exception\InvalidPhoneException;
use MSFSI\Exception\OTPRequiredException;
use MSFSI\Exception\OTPSendingFailedException;
use MSFSI\Exception\OTPValidationFailedException;
use MSFSI\Exception\PasswordMismatchException;
use MSFSI\Exception\PasswordResetFailedException;
use MSFSI\Exception\PasswordStrengthException;
use MSFSI\Exception\RegistrationRequiredFieldsException;
use MSFSI\Handler\RegistrationHandler;
use MSFSI\Helper\Traits\Instance;
use MSFSI\Helper\Utilities\MoIDPUtility;

class RegistrationActions extends BasePostAction
{
    use Instance;

    /** @var RegistrationHandler $handler */
    private $handler;

    public function __construct()
    {
        $this->handler = RegistrationHandler::instance();
        parent::__construct();
    }

    private $funcs = array (
		'mo_idp_register_customer',
		'mo_idp_validate_otp',
		'mo_idp_phone_verification',
		'mo_idp_connect_verify_customer',
		'mo_idp_forgot_password',
		'mo_idp_go_back',
		'mo_idp_resend_otp',
        'remove_idp_account'
	);

	public function handle_post_data()
	{
		if ( current_user_can( 'manage_options' ) and isset( $_POST['option'] ) )
		{
			$option = trim( sanitize_text_field( $_POST['option'] ) );
			try{
				$this->route_post_data($option);
			}catch(RegistrationRequiredFieldsException $e){
				do_action('mo_idp_show_message',$e->getMessage(),'ERROR');
			}catch(PasswordStrengthException $e){
				do_action('mo_idp_show_message',$e->getMessage(),'ERROR');
			}catch (PasswordMismatchException $e){
				do_action('mo_idp_show_message',$e->getMessage(),'ERROR');
			}catch(InvalidPhoneException $e){
				update_site_option('mo_idp_registration_status','MO_OTP_DELIVERED_FAILURE');
				do_action('mo_idp_show_message',$e->getMessage(),'ERROR');
			}catch(OTPRequiredException $e){
				update_site_option('mo_idp_registration_status','MO_OTP_VALIDATION_FAILURE');
				do_action('mo_idp_show_message',$e->getMessage(),'ERROR');
			}catch(OTPValidationFailedException $e){
				update_site_option('mo_idp_registration_status','MO_OTP_VALIDATION_FAILURE');
				do_action('mo_idp_show_message',$e->getMessage(),'ERROR');
			}catch(OTPSendingFailedException $e){
				update_site_option('mo_idp_registration_status'	, 'MO_OTP_DELIVERED_FAILURE');
				do_action('mo_idp_show_message',$e->getMessage(),'ERROR');
			}catch(PasswordResetFailedException $e){
				do_action('mo_idp_show_message',$e->getMessage(),'ERROR');
			}catch (\Exception $e){
				if(MSFSI_DEBUG) MoIDPUtility::mo_debug("Exception Occurred during SSO " . $e);
				wp_die(esc_html($e->getMessage()));
			}
		}
	}

    /**
     * @param $option
     * @throws InvalidPhoneException
     * @throws OTPRequiredException
     * @throws OTPSendingFailedException
     * @throws OTPValidationFailedException
     * @throws PasswordMismatchException
     * @throws PasswordResetFailedException
     * @throws PasswordStrengthException
     * @throws RegistrationRequiredFieldsException
     * @throws \MSFSI\Exception\RequiredFieldsException
     */
    public function route_post_data($option)
	{
		switch($option)
		{
			case $this->funcs[0]:
				$this->handler->_idp_register_customer($_POST);									break;
			case $this->funcs[1]:
				$this->handler->_idp_validate_otp($_POST);										break;
			case $this->funcs[2]:
				$this->handler->_mo_idp_phone_verification($_POST);								break;
			case $this->funcs[3]:
				$this->handler->_mo_idp_verify_customer($_POST);								break;
			case $this->funcs[4]:
				$this->handler->_mo_idp_forgot_password();										break;
			case $this->funcs[5]:
            case $this->funcs[7]:
				$this->handler->_mo_idp_go_back();												break;
			case $this->funcs[6]:
				$this->handler->_send_otp_token(
				    get_site_option('mo_idp_admin_email'),
                    "",
                    'EMAIL'
                );	                                                                            break;
		}
	}
}