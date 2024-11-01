<?php

namespace MSFSI\Handler;
use MSFSI\Helper\Traits\Instance;
use MSFSI\Helper\Constants\MoIDPMessages;


class FreshDeskIntegrationHandler{

    use instance;
    private $freshdesk_API, $freshdesk_URL;

    /**
     * Hooks added here as it is not a necessary hook for the plugins core functionality.
     * These hooks should be registered only if user wants to Integrate with the freshdesk.
     */
    public function __construct()
    {
        add_action( 'comment_post',                         array($this,    'moFreshDesk_get_comment_data'  ), 10, 3);
        add_shortcode( 'MO_FD_CONTACT_US',                  array($this,     'freshdesk_footer_demo'        )       );
    }

    function freshdesk_footer_demo()
    {
        add_action( 'wp_footer',                            array($this,        'mo_contact_freshdesk'       )      );
    }


    /**
     * Function responsible to get the FreshDesk details ftom the users.
     * Save the configuration in the database.
     * Does not involve in raising the tickets.
     */
    public function mo_idp_fd_integrator($POSTED) 
    {
        $freshdesk_API          = sanitize_text_field($POSTED['mo_idp_freshdesk_api']);
        $freshdesk_URL          = esc_url_raw($POSTED['mo_idp_freshdesk_url']);
        $freshdesk_Widget_Code  = htmlspecialchars(stripslashes($POSTED['mo_idp_freshdesk_widget_code']));
        $freshdesk_wordpress_comments       = isset($POSTED['mo_idp_wordpress_comments_tickets']) ? sanitize_text_field($POSTED['mo_idp_wordpress_comments_tickets']) : "" ;
        $show_widget            = isset($POSTED['mo_idp_freshdesk_widget_1']) ? sanitize_text_field($POSTED['mo_idp_freshdesk_widget_1']) : "" ;
        $allow_anonymous_to_raise_tickets   = isset($POSTED['mo_idp_fd_anonymous_ticket']) ? sanitize_text_field($POSTED['mo_idp_fd_anonymous_ticket']) :"";

        update_site_option('mo_idp_freshdesk_api', $freshdesk_API);
        update_site_option('mo_idp_freshdesk_url', $freshdesk_URL);
        update_site_option('mo_idp_freshdesk_widget', $show_widget);
        update_site_option('mo_idp_freshdesk_widget_code', $freshdesk_Widget_Code);
        update_site_option('mo_idp_wp_comments_tickets', $freshdesk_wordpress_comments);
        update_site_option('mo_idp_allow_anonymous_tickets', $allow_anonymous_to_raise_tickets);
    }


    /**
     * Function responsible for creating ticket in Freshdesk dashboard.
     */
    static function moFreshDesk_create_ticket($email, $subject, $description) 
    {
        $datafields     = array ("helpdesk_ticket" => array( "subject" => $subject,
                                                             "description_html" => $description,
                                                             "email" => $email,
                                                             "priority" => 1,
                                                             "status" => 2,
                                                             "source" => 2,
                                                             "ticket_type" => "Incident" ));
        $jsondata       = json_encode($datafields);
        $freshdesk_URL  = get_site_option('mo_idp_freshdesk_url');
        $response       = self::moFreshDesk_make_request($freshdesk_URL."/helpdesk/tickets.json", $jsondata);
        return $response;
    }
    

    /**
     * Function responsible for API headers, body.
     * Sends the ticket over the API.
     */
    static function moFreshDesk_make_request($requestUri, $jsondata) 
    {
        $freshdesk_API  = get_site_option('mo_idp_freshdesk_api');
        $yourAPIkeyX    = $freshdesk_API.":X";
    
        $args = [
                'headers' => [
                    'Authorization' => "Basic ".base64_encode($yourAPIkeyX),
                    'Content-Type'  => "application/json",
                ],
                'body'    => $jsondata,
            ];
        $response = wp_remote_post( $requestUri, $args );
        if ( is_wp_error( $response ) ) {
            do_action('mo_idp_show_message', MoIDPMessages::showMessage('ERROR_OCCURRED'), 'ERROR');
        }
        return $response;
    }


    /**
     * Hooked function excecutes when comment is posted on any post. 
     * PS: is called for every posted comment, whether approved or not.
     * Function responsible for collecting the comment details.
     * Details again forwarded to create_ticket function to start ticket create function
     */
    function moFreshDesk_get_comment_data($comment_ID, $comment_approved,$commentdata )
    {      
        $allow_anonymous_to_raise_tickets = get_site_option('mo_idp_allow_anonymous_tickets');
    
        /**
         * Check for the anonymous users to create tickets.
         */
        if(!is_user_logged_in() and empty($allow_anonymous_to_raise_tickets))
        {
            return;
        }
    
        $id         = $comment_ID;
        $comment    = get_comment($id);
        $comment_link   = get_comment_link( $comment, 'all' );
        $email      = $comment->comment_author_email;
        $post_name  = get_the_title($comment->comment_post_ID);
        
        $description= $comment->comment_content . "<br/><br/><a href=" . htmlentities($comment_link) . ">Go to comment</a>";
        $site_url   = get_bloginfo('name') ;
        $subject    = $site_url . " | " . $post_name . ' - ' .$comment_ID ;
        
        if(get_site_option('mo_idp_wp_comments_tickets') !== "")
            self::moFreshDesk_create_ticket($email, $subject, $description);
    }


    /**
     * Function responsible to show the widget on Wordpress site.
     * Shows the Freshdesk widget on the page where the short code [MO_FD_CONTACT_US] is added
     */
    function mo_contact_freshdesk()
    {
        if(get_site_option('mo_idp_freshdesk_widget') !== ""){
            $widget = htmlspecialchars_decode(get_site_option('mo_idp_freshdesk_widget_code'));
            echo $widget;
        }
    }
}