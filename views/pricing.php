<?php

    echo'<div class="mo_idp_divided_layout mo-idp-full">';
            is_customer_registered_idp($registered);
	echo'   <form style="display:none;" id="mo_idp_request_quote_form" action="admin.php?page=idp_support" method="post">
                <input type="text" name="plan_name" id="plan-name" value="" />
                <input type="text" name="plan_users" id="plan-users" value="" />
            </form>
            
            <form style="display:none;" id="mocf_loginform" action="'.esc_url($login_url).'" target="_blank" method="post">
				<input type="email" name="username" value="'.esc_attr($username).'" />
				<input type="text" name="redirectUrl" value="'.esc_url_raw($payment_url).'" />
				<input type="text" name="requestOrigin" id="requestOrigin"  />
			</form>
            
            <div class="mo_idp_pricing_layout mo-idp-center" style="padding:5px 20px 25px 20px">
                <h2>LICENSING PLANS
                    <span style="float:right">
                        <input  type="button" 
                                name="ok_btn" 
                                id="ok_btn" 
                                class="button button-primary button-large" 
                                value="OK, Got It" 
                                onclick="window.location.href=\''.esc_url($okgotit_url).'\'" />
                    </span>
                </h2>
                <hr>  
                <br>                  
        <table class="mo-idp-pricing-table">
            <thead>
                <tr>
                    <th id="mo-idp-pricing-rcorners" class="mo-idp-pricing-background-color mo-idp-pricing-th" style="width: 15%;"><b>Licensing Plan Name</b></th>
                    <th id="mo-idp-pricing-rcorners" class="mo-idp-pricing-heading-background-color mo-idp-pricing-th"
                        style="color:#fff;width: 42%;vertical-align:top!important;background-color:#529fa3">PREMIUM PLAN
                        <br> <br>
                        <p style="font-size: 18px">(Users stored in your own WordPress database)</p>
                    </th>
                    <th id="mo-idp-pricing-rcorners" class="mo-idp-pricing-heading-background-color mo-idp-pricing-th"
                        style="color:#fff;vertical-align:top!important;background-color:#529fa3">ALL-INCLUSIVE PLAN
                        <br><br>
                        <p style="font-size: 18px">(Users hosted in miniOrange or Enterprise Directory like Azure AD,
                            Active Directory, LDAP, Office365, Google Apps or any 3<sup>rd</sup> party providers using SAML, OAuth,
                            Database, APIs etc)</p>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="mo-idp-pricing-heading-td-color">
                    <td style="border: 2px solid #fff;" class="mo-idp-pricing-background-color mo-idp-pricing-td">User Slabs / Pricing</td>
                    <td class="mo-idp-pricing-td" style="border: 2px solid #fff;line-height:2;background-color:#b0dddf">Yearly Pricing

                        <br>
                        <p style="color:red;">(<span>50%</span> from 2<sup>nd</sup> year onwards)</p>
                    </td>
                    <td class="mo-idp-pricing-td" style="border: 2px solid #fff;background-color:#b0dddf">Monthly/Yearly Pricing </td>
                </tr>   
                <tr class="pr-row">
                    <td class="mo-idp-pricing-background-color mo-idp-pricing-td" rowspan="2">Pricing</td>
                    <td class="wp-idp-pricing-dropdown mo-idp-pricing-td">
                      <table class="idp-inner-table" style="margin-bottom:0px;padding:0px 40px;">
                        <tr>
                          <td class="idp-table-border" style="text-align:left">
                      <div class="price-tr1">
                        <div class="sub-price-tr1">
                        <label for="users" class="wp-idp-pricing-label">Select SSO Users : </label>
                      </div>
                    </td>
                    <td class="idp-table-border mo-idp-pricing-td" >
                      <div class="idp-sub-price-tr1">
                        <select id="idp-pricing-users" class="usr-slb" name="users" style="border-radius: 6px;">
                            <option value="1">1-100 Users</option>
                            <option value="2">101-200 Users</option>
                            <option value="3">201-300 Users</option>
                            <option value="4">301-400 Users</option>
                            <option value="5">401-500 Users</option>
                            <option value="6">501-1000 Users</option>
                            <option value="7">1001-2000 Users</option>
                            <option value="8">2001-3000 Users</option>
                            <option value="9">3001-4000 Users</option>
                            <option value="10">4001-5000 Users</option>
                            <option value="11">5000+ Users</option>
                        </select>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="idp-table-border mo-idp-pricing-td" id="idp-border1"style="text-align:left;border-bottom:1px solid #dfdfdf">
                        <div id="sub-price-tr2">
                          <span style="font-weight: 600;font-size:14px;">1<sup>st</sup> Year Price</span>
                        </div>
                      </td>
                      <td class="idp-table-border mo-idp-pricing-td" id="idp-border2" style="border-bottom:1px solid #dfdfdf">
                        <div id="price-head" ><span id="full-price"
                          >$500</span> 
                        </div>
                      </td>
                    </tr>
                    <tr>
                        <td class="idp-table-border mo-idp-pricing-td" style="text-align:left">
                        <div id="sub-price-tr3">
                        <span style="font-weight:600;font-size:14px;">2<sup>nd</sup>
                          Year Onwards Price</span>
                        </div>
                      </td>
                      <td class="idp-table-border mo-idp-pricing-td">
                        <div id="price-head1"><span id="half-price"
                          >$250</span> 
                        </div>
                      </td>
                      </tr>                                                 
                      </table>  
                      <div id="idp-final-pricing"><u><a id="wp-idp-final-pricing-link"
                      onclick="gatherplaninfo(\'wp_yearly\',\'5K\')" target="_blank">Request a Quote</a></u></div>
                    </td>
                    <td class="wp-idp-pricing-dropdown mo-idp-pricing-td"  style="font-size: 18px;line-height:1.6"><b>Starts from <span>$2</span>/user/month<br></b></td>
                    </tr>
 <tr>
 <td class="mo_license_upgrade_button mo-idp-pricing-td"><a onclick="mo2f_upgradeform(\'wp_saml_idp_premium_plan\')" style="display: block; width: 100%; text-decoration: none; color:white;"><b style="font-weight:700; letter-spacing:2px;">UPGRADE NOW</b></a></td>

 <td class="mo_license_upgrade_button mo-idp-pricing-td"><a onclick="gatherplaninfo(\'all_inclusive\',\'5K\')" style="display: block; width: 100%; text-decoration: none; color:white;"><b style="font-weight:700; letter-spacing:2px;">REQUEST A QUOTE</b></a></td>
 </tr>
                <tr>
                    <td class="mo-idp-pricing-background-color mo-idp-pricing-td">Multiple Service Providers</td>
                    <td class="mo-idp-pricing-data-background-color mo-idp-pricing-td"><span 
                            style="font-size:22px">$50</span> per additional SP <span class="dashicons dashicons-info mo-info-icon" style="line-height:1.4"><span class="mo-info-text">If you want more than one Service Provider,<br> then you will be charged $50 additional per SP</span></span></td>
                    <td class="mo-idp-pricing-data-background-color mo-idp-pricing-td">No additional charges for SP</td>
                </tr>
                <tr>
                    <td class="mo-idp-pricing-background-color mo-idp-pricing-td">User Storage Location</td>
                    <td class="mo-idp-pricing-data-background-color mo-idp-pricing-td">Keep Users in WordPress Database</td>
                    <td class="mo-idp-pricing-data-background-color mo-idp-pricing-td">Keep Users in miniOrange Database or Enterprise Directory like
                        Azure AD, Active Directory, LDAP, Office 365, Google Apps or any 3<sup>rd</sup> party providers using SAML,
                        OAuth, Database, APIs etc.</td>
                </tr>
                <tr>
                    <td class="mo-idp-pricing-background-color mo-idp-pricing-td">Password Management</td>
                    <td class="mo-idp-pricing-data-background-color mo-idp-pricing-td">Password will be stored in your WordPress Database</td>
                    <td class="mo-idp-pricing-data-background-color mo-idp-pricing-td">Password can be manage by miniOrange or by the 3<sup>rd</sup> party Identity
                        Provider</td>
                </tr>
                <tr>
                    <td class="mo-idp-pricing-background-color mo-idp-pricing-td">SSO Support </td>
                    <td class="mo-idp-pricing-data-background-color mo-idp-pricing-td">SAML<br>WS-FED<br>JWT</td>
                    <td class="mo-idp-pricing-data-background-color mo-idp-pricing-td">SAML <br> WS-FED <br> OAUTH <br> OIDC <br> JWT <br> RADIUS</td>
                </tr>
                <tr>
                    <td class="mo-idp-pricing-background-color mo-idp-pricing-td">User Registration</td>
                    <td class="mo-idp-pricing-data-background-color mo-idp-pricing-td">Use your own existing WordPress Sign-up Form</td>
                    <td class="mo-idp-pricing-data-background-color mo-idp-pricing-td">Sign-up via miniOrange Login Page</td>
                </tr>
                <tr>
                    <td class="mo-idp-pricing-background-color mo-idp-pricing-td">Login Page</td>
                    <td class="mo-idp-pricing-data-background-color mo-idp-pricing-td">Use your own existing WordPress Login Page</td>
                    <td class="mo-idp-pricing-data-background-color mo-idp-pricing-td">Fully customizable miniOrange Login Page</td>
                </tr>
                <tr>
                    <td class="mo-idp-pricing-background-color mo-idp-pricing-td">Custom Domains</td>
                    <td class="mo-idp-pricing-data-background-color mo-idp-pricing-td">Use your own WordPress Domain</td>
                    <td class="mo-idp-pricing-data-background-color mo-idp-pricing-td">Fully custom Domain Provided</td>
                </tr>
                <tr>
                    <td class="mo-idp-pricing-background-color mo-idp-pricing-td">Social Providers</td>
                    <td class="mo-idp-pricing-data-background-color mo-idp-pricing-td"><a
                            href="https://plugins.miniorange.com/social-login-social-sharing#pricing" class="text-color"
                            target="_blank"><u>Click here</u></a> to purchase Social Login Plugin seperately</td>
                    <td class="mo-idp-pricing-data-background-color mo-idp-pricing-td">Included <br>(Facebook, Twitter, Google+, etc)</td>
                </tr>
                <tr>
                    <td class="mo-idp-pricing-background-color mo-idp-pricing-td">Multi-Factor Authentication </td>
                    <td class="mo-idp-pricing-data-background-color mo-idp-pricing-td"><a
                            href="https://plugins.miniorange.com/2-factor-authentication-for-wordpress#pricing"
                            class="text-color" target="_blank"><u>Click here</u></a> to purchase Multi-Factor Authentication Plugin
                        seperately</td>
                    <td class="mo-idp-pricing-data-background-color mo-idp-pricing-td">Included</td>
                </tr>
                <tr class="lst-rw">
                    <td class="mo-idp-pricing-background-color mo-idp-pricing-td">User Provisioning</td>
                    <td class="mo-idp-pricing-data-background-color mo-idp-pricing-td">Not Included</td>
                    <td class="mo-idp-pricing-data-background-color mo-idp-pricing-td">Included</td>
                </tr>
            </tbody>
        </table>

            </div>
            <div class="mo_idp_pricing_layout mo-idp-center">
                <h3>How our Licensing works?</h3>
                <p>
                The <b>WordPress IDP Premium</b> plugin is an <b>annual subscription</b>, and the pricing depends upon the number of <b>SSO provisioned users</b> and the number of <b>Service Providers</b>. Any user who performs the SSO atleast once, is an SSO provisioned user. You can free up the used SSO seats by deleting the users from the WordPress admin dashboard.  
                <br>
                Once you reach the <b>80%</b> of your purchased user license limit, you will automatically recieve email notification on your registerd email address.
                At this point, you can reach out to us at <a href="mailto:samlsupport@xecurify.com">samlsupport@xecurify.com</a> or using the support form in the plugin, and we will assist you with the upgrade.<br>
                Post expiry there will be 15 days grace period to renew your license, otherwise the <span style="color:red;"><b>SSO will be disabled</b></span>.  
                </p>
            </div>
            <div id="disclamer" class="mo_idp_pricing_layout mo-idp-center">
                <h3>* Steps to Upgrade to Premium Plugin -</h3>
                <p>
                    1. You will be redirected to miniOrange Login Console. 
                    Enter your password with which you created an account with us. 
                    After that you will be redirected to payment page.
                </p>
                <p>
                    2. Enter you card details and complete the payment. 
                    On successful payment completion, you will see the link to download the premium plugin.
                </p>
                <p>
                    3. Once you download the premium plugin, just unzip it and replace the folder with existing plugin. <br>
                    <b>Note: Do not first delete and upload again from WordPress admin panel as your already saved settings will get lost.</b></p>
                    <p>4. From this point on, do not update the plugin from the WordPress store.</p>
                    <h3>** End to End Integration - </h3>
                    <p> 
                        We will setup a Conference Call / Gotomeeting and do end to end configuration for you. 
                        We provide services to do the configuration on your behalf. 
                    </p>
                    If you have any doubts regarding the licensing plans, you can mail us at 
                    <a href="mailto:info@xecurify.com"><i>info@xecurify.com</i></a> 
                    or submit a query using the <b>support form</b>.
                </p>
            </div>
            <div class="mo_idp_pricing_layout mo-idp-center">
                <h3>10 Days Return Policy</h3>
                <p>
                    At miniOrange, we want to ensure you are 100% happy with your purchase.  If the Premium plugin you purchased is not working as
                    advertised and you have attempted to resolve any feature issues with our support team, which couldn\'t get resolved, then we will
                    refund the whole amount within 10 days of the purchase. Please email us at
                    <a href="mailto:info@xecurify.com">info@xecurify.com</a> for any queries regarding the return policy.
                    <br> If you have any doubts regarding the licensing plans, you can mail us at 
                    <a href="mailto:info@xecurify.com">info@xecurify.com</a> or submit a query using the support form.
                </p>
            </div>
        </div>';