jQuery(document).ready(function () {
    $idp = jQuery;

    // when the service provider dropdown value changes then
    // submit the change service provider form
    // so that the current service provider can be set
    $idp("select[name='service_provider']").change(function(){
    	 $idp("input[name='service_provider']").val($idp(this).val());
    	 $idp("#change_sp").submit();
    });

    // clicking any element with mo_idp_help_title class will trigger a
    // slidetoggle animation on nearest element having the mo_idp_help_desc
    // class
    $idp(".mo_idp_help_title").click(function(e){
    	e.preventDefault();
    	$idp(this).next('.mo_idp_help_desc').slideToggle(400);
    });

    $idp(".mo_idp_checkbox").click(function(){
        $idp(this).next('.mo_idp_help_desc').slideToggle(400);
    });

    // this is the ribbon styletopbar to choose between protocols
    $idp("div[class^='protocol_choice_'").click(function(){
        if(!$idp(this).hasClass("selected")){
            $idp(this).parent().parent().next("form").fadeOut();
            $idp("#add_sp input[name=\"action\"]").val($idp(this).data('toggle'));
            $idp(".loader").fadeIn();
            $idp("#add_sp").submit();
        }
    });

    // any element with copyClip class will
    // copy the text in the element having
    // copyBody class
    $idp(".copyClip").click(function(){
        $idp(this).next(".copyBody").select();
        document.execCommand('copy');
    });

    $idp('a[aria-label="Deactivate WP Freshdesk Integration"]').click(function(e){
        e.preventDefault();
        $idp("#mo_idp_feedback_modal").show();        
    });
        // user is trying to remove his account
    $idp('#remove_accnt').click(function(e){
        $idp("#remove_accnt_form").submit();
    });

    // adminis trying to goback to the login page
    $idp("#goToLoginPage").click(function (e) {
        $idp("#goToLoginPageForm").submit();
    });

});

function showTestWindow(url) {
    var myWindow = window.open(url, "TEST SAML IDP", "scrollbars=1 width=800, height=600");
}

function deleteSpSettings() {
    jQuery("#mo_idp_delete_sp_settings_form").submit();
}

function mo_valid_query(f) {
    !(/^[a-zA-Z?,.\(\)\/@ 0-9]*$/).test(f.value) ? f.value = f.value.replace(
            /[^a-zA-Z?,.\(\)\/@ 0-9]/, '') : null;
}

function mo2f_upgradeform(planType){
    jQuery("#requestOrigin").val(planType);
    jQuery("#mocf_loginform").submit();
}

//------------------------------------------------------
// FEEDBACK FORM FUNCTIONS
//------------------------------------------------------

//feedback forms stuff
function mo_idp_feedback_goback() {
    $idp("#mo_idp_feedback_modal").hide();
}

function copyToClipboard(copyButton, element, copyelement) {
    var temp = jQuery("<input>");
    jQuery("body").append(temp);
    temp.val(jQuery(element).text()).select();
    document.execCommand("copy");
    temp.remove();
    jQuery(copyelement).text("Copied");

    jQuery(copyButton).mouseout(function(){
        jQuery(copyelement).text("Copy to Clipboard");
    });
}

function gatherplaninfo(name,users){
    document.getElementById("plan-name").value=name;
    document.getElementById("plan-users").value=users;
    document.getElementById("mo_idp_request_quote_form").submit();
}

function toggleContactForm() {
    var contact_text = jQuery(".mo-idp-contact-container");
    var contact_form = jQuery("#idp-contact-button-form");
    if(contact_text.is(":hidden")){
        contact_text.show();
        contact_form.slideToggle();
    } else {
        contact_text.hide();
        contact_form.slideToggle();
    }
}
