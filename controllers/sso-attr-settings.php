<?php

    /** @global \MSFSI\Helper\Database\MoDbQueries $dbIDPQueries */
	global $dbIDPQueries;
	$sp_list 				= $dbIDPQueries->get_sp_list();
	$disabled				= !$registered || !$verified ? "" : NULL;
	$sp                     = empty($sp_list) ? '' : $sp_list[0];

	include MSFSI_DIR . 'views/attr-settings.php';