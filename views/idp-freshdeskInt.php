<?php

echo '
<div class="mo_idp_divided_layout mo-idp-full" >
	<div class= "mo_idp_table_layout mo-idp-center" id="form-container">

		
		
		<form name="f" method="post" action="'.esc_url($post_url).'" enctype="multipart/form-data">
			<fieldset>
				<legend><h2>WORDPRESS FRESHDESK INTEGRATION</h2></legend>
			<input type="hidden" name="option" value="mo_idp_freshdesk_int" />
			<p>You can use the form here to integrate your WordPress site with the Freshdesk Support. Please fill the required fields in the form in order to integrate your WordPress site with Freshdesk.</p>
			<br/>
			<table class="mo_idp_settings_table">
				<tr>
					<td><strong>Freshdesk API <span style="color:red;">*</span>:</strong></td>
					<td>
						<input class="mo_idp_table_textbox"
							type="text"
							name="mo_idp_freshdesk_api"
							value="'.esc_attr($fd_api).'"
							/><br/>
						<span>
							Freshdesk API key can be retrieved from the Freshdesk profile settings.
						</span>
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td><strong>Freshdesk URL <span style="color:red;">*</span>:</strong></td>
					<td>
						<input class="mo_idp_table_textbox"
							type="text"
							value="'.esc_url_raw($fd_url).'"
							name="mo_idp_freshdesk_url"
							placeholder="https://yourdomain.freshdesk.com"
							/></br>
						<span>
							You can get your Freshdesk URL from Freshdesk settings tab.
						<span>
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td><strong><font color="#FF0000"></font>Comments as tickets:</strong></td>
					<td>
						<input  
						type="checkbox"
						name="mo_idp_wordpress_comments_tickets"
						'; if($wp_comments_as_tickets === 'on'){ echo 'checked'; } echo '>
						<span>Enable this check-box to raise WordPress Post comments as Freshdesk tickets.</span>
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td>
						<strong>Anonymous users tickets :</strong>
					</td>
					<td>
						<input type="checkbox"
						name="mo_idp_fd_anonymous_ticket"
						'; if($anonymous_tickets === 'on'){echo 'checked' ;}  echo '>
						<span>Enable this checkbox to allow Anonymous users to raise tickets in Freshdesk.</span>
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td><strong><font color="#FF0000"></font>Freshdesk support widget :</strong></td>
					<td>
						<input 
						type="checkbox"
						name="mo_idp_freshdesk_widget_1"
						'; if($fd_widget_check === 'on'){ echo 'checked'; } echo '>
						<span>Enable this check-box to add the support widget to the webpages.</span>
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
				
					<td><strong>Freshdesk Widget Code :</strong></td>
					<td>
						<textarea  
							style="width:80%;height:100px;font-family:monospace;" class="copyBody"
							class="fd_ui_element"
							rows = "6"
							name="mo_idp_freshdesk_widget_code"
							>'.esc_attr($fd_widget_code).'</textarea>
							<br/>
						<span>
						You can get the widget embed code from the Widget section in the Freshdesk admin dashboard.
						</span>
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
			   
				<tr>
					<td colspan="2">
						<div class="mo_idp_note">
							You can add <b style="color:blue">[MO_FD_CONTACT_US]</b> short-code to your web-page to add the FreshDesk support widget.
						</div>
					</td>
				</tr>
			
		
				</table>	
			</fieldset>
			
			
				<fieldset>
					<legend><h2>SSO CONFIGURATION</h2></legend>
				
				
				<table>
				<tr>
					<td style="width: 50%;">
					<input onclick="displayManualConfigFields(); hidetestdeletebuttons()" type="button"  value="Enter SP Metadata Manually" class="button button-primary button-large" style="width: 95%;background-color:#e0e0e0; border: 1px solid #e0e0e0;color: rgb(80, 80, 80);font-weight:bold;"/>
					</td>
					<td><strong> OR</strong></span></td>
					<td style="width: 78%;">
					<input  onclick="displayAutoConfigFields(); hidetestdeletebuttons()"  type="button" name="load_form" value="Upload SP Metadata" class="button button-primary button-large" style="margin-left:5%;width: 95%;background-color:#e0e0e0; border: 1px solid #e0e0e0;color: rgb(80, 80, 80);font-weight:bold;;"/>
				</td>
				</tr
				</table>
				';
if(isset($sp) && !empty($sp) || $sp_exists)
{

echo	$sp_exists  ? '
		<input type="hidden" name="option" value="mo_add_idp" />'
		: '<input type="hidden" name="option" value="mo_edit_idp" />';

echo	'<input type="hidden" name="service_provider" value="'.(isset($sp) && !empty($sp) ? esc_attr($sp->id) : "").'" />
		<input type="hidden" name="mo_idp_protocol_type" value="SAML">
		<input type="hidden" name="mo_idp_freshdesk_api_save" value="">

		<table class="mo_idp_settings_table" id="manual_id" style="display: none";>
			
		    <div style="display: block; width : 160%">	
					<tr><td>&nbsp;</td></tr>
					<tr> 
						<td colspan="2">
							Please note down the following information from your Service Provider admin screen and keep it handy to configure your Identity provider.
						</td>
					</tr>
					<tr>
						<td colspan="2">
						<ol>
							<li><b>SP Entity ID / Issuer</b></li>
							<li><b>ACS URL</b></li>
							<li><b>X.509 Certificate for Signing if you are using HTTP-POST Binding. (Optional)</b></li>
							<li><b>X.509 Certificate for Encryption. (Optional)</b></li>
							<li><b>NameID Format</b></li>
							
						</ol>
						</td>	
					</tr>
				
				<tr>
					<td colspan="2">
						<div class="mo_idp_note ">
						New to SAML? &nbsp;Looking for a documentation? &nbsp;
						<a href="'.esc_attr($saml_doc).'" target="_blank">Click Here to view the guides.</a>
						</div>
					</td>
				</tr>

				<tr><td>&nbsp;</td></tr> 

				<tr>
					<td style="width:20%;">
						<strong>Service Provider Name <span style="color:red;">*</span>:</strong>
					</td>
					<td>
						<input  type="text"
								class="manual_config_field "
								name="idp_sp_name"
								id="idpName"
								placeholder="Service Provider Name"
								style="width: 100%;"
								value="'.(!empty($sp) ? esc_attr($sp->mo_idp_sp_name) : '').'"
								required
								pattern="^\w*$"
								title="Only alphabets, numbers and underscore is allowed"/>
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td>
						<strong>SP Entity ID or Issuer <span style="color:red;">*</span>:</strong>
					</td>
					<td>
						<input  type="text"
								class="manual_config_field "
								name="idp_sp_issuer"
								placeholder="Service Provider Entity ID or Issuer"
								style="width: 100%;"
								value="'.(!empty($sp) ? esc_attr($sp->mo_idp_sp_issuer) : '').'"
								required />
					</td>
				</tr>
			<tr><td>&nbsp;</td></tr>
				<tr>
					<td>
						<strong>ACS URL <span style="color:red;">*</span>:</strong>
					</td>
					<td>
						<input  type="text"
								class="manual_config_field "
								name="idp_acs_url"
								placeholder="AssertionConsumerService URL"
								style="width: 100%;"
								value="'.(!empty($sp) ? esc_url_raw($sp->mo_idp_acs_url) : '').'"
								required />
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td><strong>NameID format <span style="color:red;">*</span>:</strong></td>
					<td>
						<!-- todo: Optimize this and get values from controller -->
						<select style="width:110%;" name="idp_nameid_format" class="manual_config_field " required >
							<option value="">Select a NameID Format</option>
							<option value="1.1:nameid-format:emailAddress"
									'.(!empty($sp) && strpos($sp->mo_idp_nameid_format,'emailAddress') ? 'selected' : '').'>
									urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress
							</option>
							<option value="1.1:nameid-format:unspecified"
									'.(!empty($sp) && strpos($sp->mo_idp_nameid_format,'unspecified') ? 'selected' : '').'>
									urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified
							</option>
							<option value="2.0:nameid-format:transient"
									'.(!empty($sp) && strpos($sp->mo_idp_nameid_format,'transient') ? 'selected' : '').'>
									urn:oasis:names:tc:SAML:2.0:nameid-format:transient
							</option>
							<option value="2.0:nameid-format:persistent"
									'.(!empty($sp) && strpos($sp->mo_idp_nameid_format,'persistent') ? 'selected' : '').'>
									urn:oasis:names:tc:SAML:2.0:nameid-format:persistent
							</option>
						</select>
						<br>
						<i>
							(<span style="color:red">NOTE: </span> Select urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress by default)
						</i>
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td>
						<strong>Default Relay State (optional)</span>:</strong>
					</td>
					<td>
						<input  type="text"
								class="manual_config_field "
								name="idp_default_relayState"
								placeholder="Default Relay State"
								style="width: 100%;"
								value="'.(!empty($sp) ? esc_url_raw($sp->mo_idp_default_relayState) : '').'"
								/>
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
				<td><strong>Assertion Signed:</strong></td>
				<td>
					<input  type="checkbox"
							name="idp_assertion_signed"
							class="manual_config_field"
							value="1"
							'.(!empty($sp) && esc_attr($sp->mo_idp_assertion_signed) ? 'checked' : '').'
							/>
					Check if you want to sign the SAML Assertion.
				</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr><td>				
				
				</td></tr>






				</div>
			</table>


			<div id="automatic_id" style="display: none;">
		
			<table style="width:100%;">
				<tr>
					<td colspan="3">
						<tr><td>&nbsp;</td></tr>
					</td>
				</tr>
				<tr><td colspan="4"></td></tr>
				<tr>

					<input type="hidden" name="mo_idp_protocol_type" value="SAML">

					<tr>
						<td width="30%"><strong>Service Provider Name<span style="color:red;">*</span>:</strong></td>
						<td><input class="auto_config_field" type="text" name="idp_sp_name" placeholder="Service Provider name " pattern="\w+" title="Only alphabets, numbers and underscore is allowed" style="width: 100%;" value="" required /></td>
						</tr>
			
					<tr>

						<input type="hidden" class="auto_config_field" name="option" value="saml_idp_upload_metadata" />
						<input type="hidden" class="auto_config_field" name="action" value="upload_idp_metadata" />
			
							<td>Upload Metadata  :</td>
							<td colspan="2"><input type="file" class="auto_config_field" name="metadata_file" />
							<!-- <input type="submit" class="button button-primary button-large" value="Upload"/> -->
						</td>
					</tr>
							
					<tr>
						<td colspan="2"><p style="font-size:13pt;text-align:center;"><b>OR</b></p></td>
					</tr>
			
					<tr>
						<input type="hidden" class="auto_config_field" name="option" value="saml_idp_upload_metadata" />
						<input type="hidden" class="auto_config_field" name="action" value="fetch_metadata" />
						<td width="20%">Enter metadata URL:</td>
						<td><input type="url" name="metadata_url" class="auto_config_field" placeholder="Enter metadata URL of your SP." style="width:100%" /></td>
						
					</tr>
					<tr>
					</tr>
					<tr><td>&nbsp;</td></tr>
			</table>

		</div> 

		</fieldset>

			<table class="mo_idp_settings_table">		
				<tr><td>&nbsp;</td></tr>
						   
			<tr class="hide_button_top" id="configbuttons" >
			
				<td style="display:flex;justify-content:center;width:100%;gap:15px">
					<input  type="submit"
							name="submit"
							id="Save"
							value="Save"
							class="button button-primary button-large"
							style="width:100px"
							/>
					<input  type="button"
							name="test"
							id="testConfig"
							title="You can only test your Configuration after saving your Service Provider Settings."
							onclick="showTestWindow(\''.esc_url($test_window).'\'); "
							'.(empty($sp) ? 'disabled' : '').'
							value="Test configuration"
							style="display:none"
							class="button button-primary button-large"
							/>		
				
					<input  type="button"
					name="delete"
					id="deleteconfig"
					class="button button-primary button-large"
					'.(empty($sp) ? "disabled" : "").'
					value="Delete SP Configuration"
					style="display:none"
					onclick = "window.location=\''.esc_url($delete_url).( !empty($sp) ? esc_attr($sp->id) : '' ).'\'"/>
				</td>
			</tr>
		
		</table>
		</form>

			<form id="add_sp" method="get" action="'.esc_url($post_url).'">
				<input type="hidden" name="action" value="add_wsfed_sp"/>
			</form>';
	}
	else
	{
		echo	'<p>Invalid SP. No Such Service Provider Found.</p>';	
	}
	echo'</div>
		</div>';


echo'

<script>
	function preSaveProcessing() {
		document.getElementById("mo_idp_freshdesk_api_save").value = document.getElementById("mo_idp_freshdesk_api").value;
	}
	function displayAutoConfigFields() {
		document.getElementById("automatic_id").style.display="block";

		var x = document.getElementById("automatic_id");
		var y = document.getElementById("manual_id");

		if (x.style.display === "block") {
		  y.style.display = "none";
		} else {
		  x.style.display = "none";
		}
		document.getElementById("manual_id").style.display="none";

		jQuery(".manual_config_field").attr("disabled", true);

		jQuery(".auto_config_field").attr("disabled", false);
		
	}
	function displayManualConfigFields() {
		document.getElementById("manual_id").style.display="table";
		document.getElementById("automatic_id").style.display="none";

		jQuery(".manual_config_field").attr("disabled", false);

		jQuery(".auto_config_field").attr("disabled", true);
	  
	}

	window.onload = hideconfigbuttons;

	function hideconfigbuttons(){
		
		
		jQuery(".manual_config_field").attr("disabled", true);
		jQuery(".auto_config_field").attr("disabled", true);
		
	}

	function hidetestdeletebuttons(){
		document.getElementById("testConfig").style.display="block";
		document.getElementById("deleteconfig").style.display="block";
	}
	</script>

';

?>