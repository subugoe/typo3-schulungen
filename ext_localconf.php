<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'Schulungen',
	array(
		'Schulung' => 'list, listSlim, listTermineUndTeilnehmer, show, new, create, edit, update, delete, export',
		'Termin' => 'list, show, new, create, edit, update, delete',
		'Teilnehmer' => 'list, show, new, create, edit, update, delete, deregister',
		'Benachrichtigung' => 'sendeBenachrichtigung'
	),
	// notCacheAbleActions
	array(
		'Schulung' => 'show',
		'Teilnehmer' => 'new, create, list'
	)
);

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'SchulungenSideMenu',
	array(
		'Schulung' => 'listSlim',
	)
);

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'Scheduler',
	array(
		'Benachrichtigung' => 'sendeBenachrichtigung',
		'Termin' => 'update'
	)
);

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'Calendar',
	array(
		'Termin' => 'export'
	)
);

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'Csvexport',
	array(
		'Schulung' => 'export'
	)
);

// Scheduler fuer die Erinnerungen konfigurieren
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Tx_Schulungen_Service_SendRemindersTask'] = array(
	'extension' => $_EXTKEY,
	'title' => 'Erinnerungen versenden',
	'description' => 'Vor einer Schulung Erinnerungen verschicken'
);

/* not correctly working solution for cli_mode (scheduler) */
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'Tx_Schulungen_Command_ReminderCommandController';

// Set up Hooks
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['nkwsubmenu']['extendMoreOnThesePages'][$_EXTKEY] = 'EXT:' . $_EXTKEY . '/Classes/Controller/SchulungController.php:Tx_Schulungen_Controller_SchulungController->listSlimAction';
?>