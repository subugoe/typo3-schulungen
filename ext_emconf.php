<?php

########################################################################
# Extension Manager/Repository config file for ext "schulungen".
#
# Auto generated 20-12-2011 15:43
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Schulungen',
	'description' => 'Schulungen der SUB Goettingen',
	'category' => 'plugin',
	'author' => 'Dominic Simm, Ingo Pfennigstorf',
	'author_email' => 'dominic.simm@sub.uni-goettingen.de, pfennigstorf@sub-goettingen.de',
	'author_company' => 'Goettingen State and University Library, Germany http://www.sub.uni-goettingen.de',
	'shy' => '',
	'dependencies' => 'cms,extbase,fluid,t3jquery',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'alpha',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '0.9.1',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'extbase' => '',
			'fluid' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'suggests' => array(
	),
	'_md5_values_when_last_written' => 'a:95:{s:9:"ChangeLog";s:4:"2bd4";s:9:"build.xml";s:4:"3695";s:16:"ext_autoload.php";s:4:"f67e";s:12:"ext_icon.gif";s:4:"0f17";s:17:"ext_localconf.php";s:4:"c8d8";s:14:"ext_tables.php";s:4:"0d0a";s:14:"ext_tables.sql";s:4:"bf50";s:13:"locallang.xml";s:4:"acaa";s:16:"locallang_db.xml";s:4:"8ca5";s:40:"Classes/Controller/BackendController.php";s:4:"bce9";s:49:"Classes/Controller/BenachrichtigungController.php";s:4:"6eed";s:38:"Classes/Controller/EmailController.php";s:4:"4bae";s:41:"Classes/Controller/SchulungController.php";s:4:"faa3";s:43:"Classes/Controller/TeilnehmerController.php";s:4:"fa58";s:39:"Classes/Controller/TerminController.php";s:4:"af3a";s:33:"Classes/Domain/Model/Schulung.php";s:4:"63bc";s:35:"Classes/Domain/Model/Teilnehmer.php";s:4:"b787";s:31:"Classes/Domain/Model/Termin.php";s:4:"d52a";s:48:"Classes/Domain/Repository/SchulungRepository.php";s:4:"1ada";s:50:"Classes/Domain/Repository/TeilnehmerRepository.php";s:4:"a9d6";s:46:"Classes/Domain/Repository/TerminRepository.php";s:4:"c5dc";s:37:"Classes/Service/SendRemindersTask.php";s:4:"9c82";s:41:"Classes/ViewHelpers/CSVListViewHelper.php";s:4:"fdb9";s:46:"Classes/ViewHelpers/FreiePlaetzeViewHelper.php";s:4:"4ce6";s:47:"Configuration/FlexForms/flexform_schulungen.xml";s:4:"f664";s:30:"Configuration/TCA/Schulung.php";s:4:"a51d";s:32:"Configuration/TCA/Teilnehmer.php";s:4:"3f20";s:28:"Configuration/TCA/Termin.php";s:4:"e951";s:38:"Configuration/TypoScript/constants.txt";s:4:"c674";s:34:"Configuration/TypoScript/setup.txt";s:4:"891d";s:40:"Resources/Private/Language/locallang.xml";s:4:"3025";s:80:"Resources/Private/Language/locallang_csh_tx_schulungen_domain_model_schulung.xml";s:4:"ddb5";s:82:"Resources/Private/Language/locallang_csh_tx_schulungen_domain_model_teilnehmer.xml";s:4:"eed3";s:78:"Resources/Private/Language/locallang_csh_tx_schulungen_domain_model_termin.xml";s:4:"e736";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"2bf5";s:60:"Resources/Private/Language/locallang_schulungsverwaltung.xml";s:4:"3c23";s:37:"Resources/Private/Layouts/backend.csv";s:4:"f719";s:38:"Resources/Private/Layouts/backend.html";s:4:"bfba";s:37:"Resources/Private/Layouts/default.csv";s:4:"7cb6";s:38:"Resources/Private/Layouts/default.html";s:4:"c565";s:37:"Resources/Private/Layouts/default.ics";s:4:"3562";s:42:"Resources/Private/Partials/formErrors.html";s:4:"f5bc";s:51:"Resources/Private/Partials/Schulung/formFields.html";s:4:"26aa";s:51:"Resources/Private/Partials/Schulung/properties.html";s:4:"af17";s:53:"Resources/Private/Partials/Teilnehmer/formFields.html";s:4:"6cda";s:53:"Resources/Private/Partials/Teilnehmer/properties.html";s:4:"50b6";s:49:"Resources/Private/Partials/Termin/formFields.html";s:4:"4f18";s:49:"Resources/Private/Partials/Termin/properties.html";s:4:"7ec0";s:47:"Resources/Private/Templates/Backend/Cancel.html";s:4:"ca57";s:47:"Resources/Private/Templates/Backend/Detail.html";s:4:"f9ab";s:46:"Resources/Private/Templates/Backend/Export.csv";s:4:"618c";s:47:"Resources/Private/Templates/Backend/Export.html";s:4:"c68d";s:46:"Resources/Private/Templates/Backend/Index.html";s:4:"ef74";s:49:"Resources/Private/Templates/Backend/Uncancel.html";s:4:"d41d";s:55:"Resources/Private/Templates/Benachrichtigung/Index.html";s:4:"3c36";s:71:"Resources/Private/Templates/Benachrichtigung/SendeBenachrichtigung.html";s:4:"d2f1";s:51:"Resources/Private/Templates/Email/Bestaetigung.html";s:4:"b305";s:47:"Resources/Private/Templates/Email/Reminder.html";s:4:"bc11";s:58:"Resources/Private/Templates/Email/ReminderCancelation.html";s:4:"bde5";s:60:"Resources/Private/Templates/Email/ReminderParticipation.html";s:4:"794c";s:46:"Resources/Private/Templates/Schulung/Edit.html";s:4:"7c88";s:47:"Resources/Private/Templates/Schulung/Export.csv";s:4:"a661";s:48:"Resources/Private/Templates/Schulung/Export.html";s:4:"484a";s:46:"Resources/Private/Templates/Schulung/List.html";s:4:"368a";s:66:"Resources/Private/Templates/Schulung/ListTermineUndTeilnehmer.html";s:4:"0e22";s:45:"Resources/Private/Templates/Schulung/New.html";s:4:"9bba";s:46:"Resources/Private/Templates/Schulung/Show.html";s:4:"13cc";s:50:"Resources/Private/Templates/Schulung/ShowList.html";s:4:"1ccb";s:48:"Resources/Private/Templates/Schulung/update.html";s:4:"cd9f";s:50:"Resources/Private/Templates/Teilnehmer/Create.html";s:4:"56d8";s:50:"Resources/Private/Templates/Teilnehmer/Delete.html";s:4:"b5fd";s:48:"Resources/Private/Templates/Teilnehmer/Edit.html";s:4:"2718";s:48:"Resources/Private/Templates/Teilnehmer/List.html";s:4:"29b4";s:47:"Resources/Private/Templates/Teilnehmer/New.html";s:4:"b5fd";s:48:"Resources/Private/Templates/Teilnehmer/Show.html";s:4:"9624";s:44:"Resources/Private/Templates/Termin/Edit.html";s:4:"ca19";s:45:"Resources/Private/Templates/Termin/Export.ics";s:4:"079a";s:44:"Resources/Private/Templates/Termin/List.html";s:4:"f048";s:43:"Resources/Private/Templates/Termin/New.html";s:4:"227d";s:44:"Resources/Private/Templates/Termin/Show.html";s:4:"18a4";s:40:"Resources/Public/Icons/icon_calendar.gif";s:4:"d972";s:62:"Resources/Public/Icons/tx_schulungen_domain_model_schulung.gif";s:4:"0f17";s:64:"Resources/Public/Icons/tx_schulungen_domain_model_teilnehmer.gif";s:4:"adbf";s:60:"Resources/Public/Icons/tx_schulungen_domain_model_termin.gif";s:4:"33fc";s:56:"Resources/Public/Icons/tx_schulungen_domain_model_tn.gif";s:4:"adbf";s:62:"Resources/Public/Icons/tx_schulungen_domain_model_tn.gif.r1102";s:4:"adbf";s:62:"Resources/Public/Icons/tx_schulungen_domain_model_tn.gif.r1378";s:4:"f57b";s:40:"Resources/Public/Js/schulungenBackend.js";s:4:"d41d";s:32:"Resources/Public/css/backend.css";s:4:"9228";s:35:"Resources/Public/css/schulungen.css";s:4:"d26e";s:29:"Resources/Public/img/fail.png";s:4:"1df0";s:27:"Resources/Public/img/ok.png";s:4:"f14a";s:31:"Resources/Public/img/t3skin.png";s:4:"b928";s:15:"build/phpcs.xml";s:4:"ab01";s:15:"build/phpmd.xml";s:4:"ab48";}',
);

?>