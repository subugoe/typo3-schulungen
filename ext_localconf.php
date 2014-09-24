<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Subugoe. ' . $_EXTKEY,
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
		'Teilnehmer' => 'new, create, list, deregister'
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Subugoe. ' . $_EXTKEY,
	'SchulungenSideMenu',
	array(
		'Schulung' => 'listSlim',
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Subugoe. ' . $_EXTKEY,
	'Scheduler',
	array(
		'Benachrichtigung' => 'sendeBenachrichtigung',
		'Termin' => 'update'
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Subugoe. ' . $_EXTKEY,
	'Calendar',
	array(
		'Termin' => 'export'
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Subugoe. ' . $_EXTKEY,
	'Csvexport',
	array(
		'Schulung' => 'export'
	)
);

// Scheduler fuer die Erinnerungen konfigurieren
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Subugoe\\Schulungen\\Service\\SendRemindersTask'] = array(
	'extension' => $_EXTKEY,
	'title' => 'Erinnerungen versenden',
	'description' => 'Vor einer Schulung Erinnerungen verschicken'
);

/* not correctly working solution for cli_mode (scheduler) */
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'Subugoe\\Schulungen\\Command\\ReminderCommandController';

// Set up Hooks
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['nkwsubmenu']['extendMoreOnThesePages'][$_EXTKEY] = 'EXT:' . $_EXTKEY . '/Classes/Controller/SchulungController.php:SchulungController->listSlimAction';
