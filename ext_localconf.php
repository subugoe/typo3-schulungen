<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

Tx_Extbase_Utility_Extension::configurePlugin(
		$_EXTKEY,
		'Schulungen',
		array(
			'Schulung' => 'list, listTermineUndTeilnehmer, show, new, create, edit, update, delete, export',
			'Termin' => 'list, show, new, create, edit, update, delete',
			'Teilnehmer' => 'list, show, new, create, edit, update, delete',
			'Benachrichtigung' => 'sendeBenachrichtigung'
		)
);

Tx_Extbase_Utility_Extension::configurePlugin(
		$_EXTKEY,
		'scheduler',
		array(
			'Benachrichtigung' => 'sendeBenachrichtigung'
		)
);

Tx_Extbase_Utility_Extension::configurePlugin(
		$_EXTKEY,
		'calendar',
		array(
			'Termin' => 'export'
		)
);

Tx_Extbase_Utility_Extension::configurePlugin(
		$_EXTKEY,
		'csvexport',
		array(
			'Backend' => 'export'
		)
);

// Scheduler fuer die Erinnerungen konfigurieren
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['Tx_Schulungen_Service_SendRemindersTask'] = array(
	'extension' => $_EXTKEY,
	'title' => 'Erinnerungen versenden',
	'description' => 'Vor einer Schulung Erinnerungen verschicken'
);

//$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'Tx_Schulungen_Command_ReminderCommandController';

?>