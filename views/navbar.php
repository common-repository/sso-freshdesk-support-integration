<?php
echo'
    <div class="mo-visual-tour-overlay" id="overlay" hidden></div>
    <div class="wrap">
            <div><img style="float:left;" src="'.MSFSI_LOGO_URL.'"></div>
            <h1>
                WP Freshdesk Support and SSO Integration 
                <div id="idp-quicklinks">
                    <a class="add-new-h2" href="'.esc_url($help_url).'" target="_blank">FAQs</a>
                    <a class="license-button add-new-h2" href="'.esc_url($license_url).'">Upgrade</a>
                    <a class="add-new-h2" href="'.esc_url($support_url).'">Stuck? Need Help?</a>
                </div>
            </h1>			
    </div>';

    check_is_curl_installed();

echo'<div id="tab">
        <h2 class="nav-tab-wrapper">
            <a  class="nav-tab 
                '.($active_tab == $freshdeskIntDetails->_menuSlug ? 'nav-tab-active' : '').'" 
                href="'.esc_url($freshdesk_url).'">
                '.esc_attr($freshdeskIntDetails->_tabName).'
            </a>
            
            <a  class="nav-tab 
                '.($active_tab == $metadataTabDetails->_menuSlug ? 'nav-tab-active' : '').'" 
                href="'.esc_url($sp_settings).'">
                '.esc_attr($metadataTabDetails->_tabName).'
            </a>
            <a  class="nav-tab 
                '.($active_tab == $attrMapTabDetails->_menuSlug ? 'nav-tab-active' : '').'" 
                href="'.esc_url($attr_settings).'">
                '.esc_attr($attrMapTabDetails->_tabName).'
            </a>
            <a  class="nav-tab 
                '.($active_tab == $settingsTabDetails->_menuSlug ? 'nav-tab-active' : '').'" 
                href="'.esc_url($login_settings).'">
                '.esc_attr($settingsTabDetails->_tabName).'
            </a>
            <a class="nav-tab
                '.($active_tab == $demoRequestTabDetails->_menuSlug ? 'nav-tab-active' : '').'"
                href="'.esc_url($demoRequest_url).'">
                '.esc_attr($demoRequestTabDetails->_tabName).'
            </a>
            <a class="nav-tab 
                '.($active_tab == $licenseTabDetails->_menuSlug	? 'nav-tab-active' : '').'" 
                href="'.esc_url($license_url).'">
                '.esc_attr($licenseTabDetails->_tabName).'
            </a>
            <a class="nav-tab 
                '.($active_tab == $profileTabDetails->_menuSlug ? 'nav-tab-active' : '').'" 
                href="'.esc_url($register_url).'">
                '.esc_attr($profileTabDetails->_tabName).'
            </a>
    </div>';

    if (!get_site_option("mo_idp_new_certs"))    
    echo"<div style='display:block; width:62%; margin:auto; margin-top:10px; color:black; background-color:rgba(251, 232, 0, 0.15); 
    padding:15px 15px 15px 15px; border:solid 1px rgba(204, 204, 0, 0.36); font-size:large; line-height:normal'>
    <span style='color:red;'><span class='dashicons dashicons-warning'></span> <b>WARNING</b>:</span> The existing certificates have expired. Please update the certificates ASAP to secure your SSO.<br> Go to the <a href='admin.php?page=idp_metadata'><b>IDP Metadata</b></a> tab
    of the plugin to update your certificates. Make sure to update your Service Provider with the new certificate to ensure your SSO does not break.
</div>";