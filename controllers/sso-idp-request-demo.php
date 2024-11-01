<?php

use MSFSI\Handler\DemoRequestHandler;

$email              = get_site_option('mo_idp_admin_email');
$mo_idp_demo_email  = isset($email) ? $email : "";
$demononce          = DemoRequestHandler::instance()->_nonce;

include MSFSI_DIR. 'views/demo-request.php';
