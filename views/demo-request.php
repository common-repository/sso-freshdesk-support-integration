<?php

    echo '
        <form name="demo_request" method="post" action="" id="demorequest">
        <input type="hidden" name="option" value="mo_idp_request_demo">
        '.wp_nonce_field($demononce).'

        <div class="mo_idp_divided_layout mo-idp-full">
        <div class= "mo_idp_table_layout mo-idp-center">
                <h3>REQUEST A DEMO</h3>
                <hr>
                
                <p>
                    Want to try out the paid features before purchasing the license? Let us know about your requirements, and we will set up a demo for you.
                </p>
            
                <table class="mo_idp_settings_table" style="line-height:3em;">
                    <tr>
                        <td><b>PLUGIN :</b></td>
                        <td><b>WordPress Freshdesk Integration Plugin</b></td>
                    </tr>
                    <tr>
                        <td><b><font color="#FF0000">*</font>Email :</b></td>
                        <td>
                            <input class="mo_idp_table_textbox"
                                        type="email"
                                        name="mo_idp_demo_email"
                                        required 
                                        placeholder="We will use this email to setup the demo for you"
                                        value="'.esc_attr($mo_idp_demo_email).'"/>
                        
                        </td>
                    </tr>
                    <tr>
                        <td><b>Description :<b></td>
                        <td>
                            <textarea class="mo_idp_table_textbox" rows="5" cols="8" name="mo_idp_demo_description" required placeholder="Write us about your requirement" value=""></textarea>    
                        </td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr style="text-align:center;">
                        <td colspan="2" >
                            <input type="submit" value="Request a Demo" class="button button-primary button-large"/>
                        </td>
                    </tr>
                </table>
                </div>
            </div>

        </form>
    
    ';



