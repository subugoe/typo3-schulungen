<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "schulungen".
 *
 * Auto generated 30-10-2013 12:37
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
	'title' => 'Schulungen',
	'description' => 'Schulungen der SUB Goettingen',
	'category' => 'plugin',
	'author' => 'Dominic Simm, Ingo Pfennigstorf',
	'author_email' => 'pfennigstorf@sub-goettingen.de',
	'author_company' => 'Goettingen State and University Library, Germany http://www.sub.uni-goettingen.de',
	'shy' => '',
	'dependencies' => 'cms',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '2.0.0',
	'constraints' => [
		'depends' => [
			'cms' => '6.2.0-7.99.99',
			'php' => '5.5.0-5.6.99'
		],
		'conflicts' => [
		],
		'suggests' => [
		],
	],
	'suggests' => [
	],
	'_md5_values_when_last_written' => 'a:120:{s:9:"build.xml";s:4:"b50a";s:9:"ChangeLog";s:4:"2bd4";s:16:"ext_autoload.php";s:4:"c023";s:12:"ext_icon.gif";s:4:"0f17";s:17:"ext_localconf.php";s:4:"adea";s:14:"ext_tables.php";s:4:"e866";s:14:"ext_tables.sql";s:4:"85c4";s:13:"locallang.xml";s:4:"ef36";s:16:"locallang_db.xml";s:4:"8ca5";s:9:"README.md";s:4:"a7d4";s:45:"Classes/Command/ReminderCommandController.php";s:4:"c84d";s:40:"Classes/Controller/BackendController.php";s:4:"e481";s:49:"Classes/Controller/BenachrichtigungController.php";s:4:"c725";s:38:"Classes/Controller/EmailController.php";s:4:"78b5";s:41:"Classes/Controller/SchulungController.php";s:4:"cdab";s:43:"Classes/Controller/TeilnehmerController.php";s:4:"d6ef";s:39:"Classes/Controller/TerminController.php";s:4:"81c4";s:31:"Classes/Domain/Model/Person.php";s:4:"cb66";s:33:"Classes/Domain/Model/Schulung.php";s:4:"119a";s:35:"Classes/Domain/Model/Teilnehmer.php";s:4:"dc07";s:31:"Classes/Domain/Model/Termin.php";s:4:"8920";s:46:"Classes/Domain/Repository/PersonRepository.php";s:4:"dbba";s:48:"Classes/Domain/Repository/SchulungRepository.php";s:4:"1627";s:50:"Classes/Domain/Repository/TeilnehmerRepository.php";s:4:"c50a";s:46:"Classes/Domain/Repository/TerminRepository.php";s:4:"152a";s:45:"Classes/Domain/Validator/CaptchaValidator.php";s:4:"2926";s:56:"Classes/Domain/Validator/CustomEmailAddressValidator.php";s:4:"86fe";s:43:"Classes/Domain/Validator/EmptyValidator.php";s:4:"83f9";s:37:"Classes/Service/SendRemindersTask.php";s:4:"3005";s:42:"Classes/Service/SendRemindersTaskLogic.php";s:4:"bec0";s:25:"Classes/Utility/Array.php";s:4:"3005";s:33:"Classes/Utility/HelperUtility.php";s:4:"429b";s:23:"Classes/Utility/Uri.php";s:4:"4c4b";s:41:"Classes/ViewHelpers/CaptchaViewhelper.php";s:4:"52f7";s:41:"Classes/ViewHelpers/CSVListViewHelper.php";s:4:"bdeb";s:41:"Classes/ViewHelpers/EndSoonViewHelper.php";s:4:"ba89";s:46:"Classes/ViewHelpers/FreiePlaetzeViewHelper.php";s:4:"1368";s:38:"Classes/ViewHelpers/SortViewHelper.php";s:4:"d84a";s:44:"Classes/ViewHelpers/TerminatedViewHelper.php";s:4:"4369";s:38:"Configuration/FlexForms/Schulungen.xml";s:4:"73be";s:30:"Configuration/TCA/Schulung.php";s:4:"0866";s:32:"Configuration/TCA/Teilnehmer.php";s:4:"509f";s:28:"Configuration/TCA/Termin.php";s:4:"fba5";s:38:"Configuration/TypoScript/constants.txt";s:4:"a044";s:34:"Configuration/TypoScript/setup.txt";s:4:"ef01";s:40:"Resources/Private/Language/locallang.xml";s:4:"3368";s:80:"Resources/Private/Language/locallang_csh_tx_schulungen_domain_model_schulung.xml";s:4:"fb2b";s:82:"Resources/Private/Language/locallang_csh_tx_schulungen_domain_model_teilnehmer.xml";s:4:"eed3";s:78:"Resources/Private/Language/locallang_csh_tx_schulungen_domain_model_termin.xml";s:4:"e736";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"8a43";s:60:"Resources/Private/Language/locallang_schulungsverwaltung.xml";s:4:"3c23";s:37:"Resources/Private/Layouts/Backend.csv";s:4:"f719";s:38:"Resources/Private/Layouts/Backend.html";s:4:"bfba";s:37:"Resources/Private/Layouts/Default.csv";s:4:"7cb6";s:38:"Resources/Private/Layouts/Default.html";s:4:"c565";s:37:"Resources/Private/Layouts/Default.ics";s:4:"3562";s:42:"Resources/Private/Partials/FieldError.html";s:4:"5bb3";s:42:"Resources/Private/Partials/formErrors.html";s:4:"f5bc";s:48:"Resources/Private/Partials/Schulung/contact.html";s:4:"1d9d";s:51:"Resources/Private/Partials/Schulung/formFields.html";s:4:"d609";s:51:"Resources/Private/Partials/Schulung/properties.html";s:4:"7804";s:53:"Resources/Private/Partials/Teilnehmer/formFields.html";s:4:"52c7";s:55:"Resources/Private/Partials/Teilnehmer/formFieldsBE.html";s:4:"53d9";s:53:"Resources/Private/Partials/Teilnehmer/properties.html";s:4:"50b6";s:49:"Resources/Private/Partials/Termin/formFields.html";s:4:"3007";s:49:"Resources/Private/Partials/Termin/properties.html";s:4:"7ec0";s:47:"Resources/Private/Templates/Backend/Cancel.html";s:4:"ca57";s:47:"Resources/Private/Templates/Backend/Detail.html";s:4:"487a";s:46:"Resources/Private/Templates/Backend/Export.csv";s:4:"618c";s:47:"Resources/Private/Templates/Backend/Export.html";s:4:"c68d";s:46:"Resources/Private/Templates/Backend/Index.html";s:4:"9f7e";s:49:"Resources/Private/Templates/Backend/Uncancel.html";s:4:"d41d";s:55:"Resources/Private/Templates/Benachrichtigung/Index.html";s:4:"3c36";s:71:"Resources/Private/Templates/Benachrichtigung/SendeBenachrichtigung.html";s:4:"d2f1";s:77:"Resources/Private/Templates/Benachrichtigung/SendeBenachrichtigungSofort.html";s:4:"d2f1";s:51:"Resources/Private/Templates/Email/Bestaetigung.html";s:4:"aa74";s:54:"Resources/Private/Templates/Email/Bestaetigung_en.html";s:4:"8677";s:53:"Resources/Private/Templates/Email/Deregistration.html";s:4:"6ab7";s:47:"Resources/Private/Templates/Email/Reminder.html";s:4:"d13e";s:58:"Resources/Private/Templates/Email/ReminderCancelation.html";s:4:"91a7";s:60:"Resources/Private/Templates/Email/ReminderParticipation.html";s:4:"f6fa";s:54:"Resources/Private/Templates/Email/TransactionMail.html";s:4:"2922";s:46:"Resources/Private/Templates/Schulung/Edit.html";s:4:"d4cf";s:47:"Resources/Private/Templates/Schulung/Export.csv";s:4:"a661";s:48:"Resources/Private/Templates/Schulung/Export.html";s:4:"484a";s:46:"Resources/Private/Templates/Schulung/List.html";s:4:"1f16";s:50:"Resources/Private/Templates/Schulung/ListSlim.html";s:4:"ec50";s:66:"Resources/Private/Templates/Schulung/ListTermineUndTeilnehmer.html";s:4:"0e22";s:45:"Resources/Private/Templates/Schulung/New.html";s:4:"9bba";s:46:"Resources/Private/Templates/Schulung/Show.html";s:4:"30ff";s:48:"Resources/Private/Templates/Schulung/update.html";s:4:"32b4";s:50:"Resources/Private/Templates/Teilnehmer/Create.html";s:4:"56d8";s:50:"Resources/Private/Templates/Teilnehmer/Delete.html";s:4:"56d8";s:54:"Resources/Private/Templates/Teilnehmer/Deregister.html";s:4:"051d";s:48:"Resources/Private/Templates/Teilnehmer/Edit.html";s:4:"0fe8";s:48:"Resources/Private/Templates/Teilnehmer/List.html";s:4:"9fba";s:47:"Resources/Private/Templates/Teilnehmer/New.html";s:4:"4bb7";s:48:"Resources/Private/Templates/Teilnehmer/Show.html";s:4:"9624";s:44:"Resources/Private/Templates/Termin/Edit.html";s:4:"ca19";s:45:"Resources/Private/Templates/Termin/Export.ics";s:4:"079a";s:44:"Resources/Private/Templates/Termin/List.html";s:4:"f048";s:43:"Resources/Private/Templates/Termin/New.html";s:4:"227d";s:44:"Resources/Private/Templates/Termin/Show.html";s:4:"18a4";s:40:"Resources/Public/Icons/icon_calendar.gif";s:4:"d972";s:62:"Resources/Public/Icons/tx_schulungen_domain_model_schulung.gif";s:4:"0f17";s:64:"Resources/Public/Icons/tx_schulungen_domain_model_teilnehmer.gif";s:4:"adbf";s:60:"Resources/Public/Icons/tx_schulungen_domain_model_termin.gif";s:4:"33fc";s:56:"Resources/Public/Icons/tx_schulungen_domain_model_tn.gif";s:4:"adbf";s:40:"Resources/Public/Js/schulungenBackend.js";s:4:"d41d";s:32:"Resources/Public/css/backend.css";s:4:"1508";s:35:"Resources/Public/css/schulungen.css";s:4:"0c60";s:39:"Resources/Public/css/schulungen_mod.css";s:4:"2df4";s:29:"Resources/Public/img/fail.png";s:4:"1df0";s:36:"Resources/Public/img/nav_logo107.png";s:4:"92d8";s:27:"Resources/Public/img/ok.png";s:4:"f14a";s:31:"Resources/Public/img/t3skin.png";s:4:"b928";s:48:"Tests/Unit/Controller/SchulungControllerTest.php";s:4:"1699";s:51:"Tests/Unit/ViewHelpers/TerminatedViewHelperTest.php";s:4:"a83f";s:15:"build/phpcs.xml";s:4:"ab01";s:15:"build/phpmd.xml";s:4:"ab48";}',
];
