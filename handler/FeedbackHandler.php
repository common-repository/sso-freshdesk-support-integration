<?php

namespace MSFSI\Handler;


use MSFSI\Helper\Constants\MoIDPConstants;
use MSFSI\Helper\Traits\Instance;
use MSFSI\Helper\Utilities\MoIDPcURL;
use MSFSI\Helper\Utilities\MoIDPUtility;


final class FeedbackHandler extends BaseHandler
{
    use Instance;

    /** Private constructor to avoid direct object creation */
    private function __construct()
    {
        $this->_nonce = 'mo_idp_feedback';
    }

    public function _mo_send_feedback($POSTED)
    {
        $this->isValidRequest();
        $submitType     = $POSTED['miniorange_feedback_submit'];
        $feedback       = sanitize_textarea_field($POSTED['idp_query_feedback']);
        $ratingvalue    = sanitize_text_field($POSTED['idp_rate']);
        $emailValue     = sanitize_email($POSTED['idp_email']);

        $keepSettingsIntact = array_key_exists('idp_keep_settings_intact', $POSTED);
        $isReplyRequired    = array_key_exists('idp_dnd', $POSTED);

        if( $keepSettingsIntact )
        {
            update_site_option('idp_keep_settings_intact', TRUE);
        }
        else
        {
            update_site_option('idp_keep_settings_intact', FALSE);
        }
        if($submitType!=="Skip & Deactivate")
        {
            $this->_sendEmail($this->_renderEmail($feedback, $ratingvalue, $emailValue, $isReplyRequired)); // render and send deactivation feedback email
        }
      
        deactivate_plugins(MSFSI_PLUGIN_NAME);
        wp_redirect( self_admin_url( "plugins.php?deactivate=true"));
        exit;
    }

    /**
     * @param string $message
     * @return false|mixed|string
     */
    private function _renderEmail($message, $rating, $email, $isReplyRequired = TRUE)
    {
        $feedbackTemplate = file_get_contents(MSFSI_DIR . 'includes/html/emailtemplate.min.html');
        //$email = get_site_option("mo_idp_admin_email");

            $feedbackTemplate = str_replace("{{SERVER}}",sanitize_text_field($_SERVER['SERVER_NAME']),$feedbackTemplate);
            $feedbackTemplate = str_replace("{{EMAIL}}",$email,$feedbackTemplate);
            $feedbackTemplate = str_replace("{{PLUGIN}}",MoIDPConstants::AREA_OF_INTEREST,$feedbackTemplate);
            $feedbackTemplate = str_replace("{{VERSION}}",MSFSI_VERSION,$feedbackTemplate);
            $feedbackTemplate = str_replace("{{TYPE}}","[Plugin Deactivated]",$feedbackTemplate);
        $feedbackTemplate = str_replace("{{QUERY}}","Feedback : " . $message,$feedbackTemplate);
        if(!$isReplyRequired) 
        {
            $feedbackTemplate = str_replace("{{RATING}}","Rating : " . $rating . " [Do Not Reply]",$feedbackTemplate);
        }
        else
        {
            $feedbackTemplate = str_replace("{{RATING}}","Rating : " . $rating,$feedbackTemplate);
        }

        return $feedbackTemplate;
    }


    /**
     * @param string $content
     */
    private function _sendEmail($content)
    {
        $customerKey = sanitize_text_field(get_site_option('mo_idp_admin_customer_key'));
        $apiKey = sanitize_text_field(get_site_option("mo_idp_admin_api_key"));
        MoIDPcURL::notify(
            !$customerKey ? MoIDPConstants::DEFAULT_CUSTOMER_KEY : $customerKey,
            !$apiKey ? MoIDPConstants::DEFAULT_API_KEY : $apiKey,
            MoIDPConstants::SAMLSUPPORT_EMAIL,
            $content,
            "WordPress Freshdesk Integration Support"
        );
    }
}