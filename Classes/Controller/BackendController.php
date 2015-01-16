<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Ingo Pfennigstorf <pfennigstorf@sub-goettingen.de>, Goettingen State Library
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

/**
 * Controller for the Schulung object
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Schulungen_Controller_BackendController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * schulungRepository
	 *
	 * @var Tx_Schulungen_Domain_Repository_SchulungRepository
	 * @inject
	 */
	protected $schulungRepository;
	/**
	 * terminRepository
	 *
	 * @var Tx_Schulungen_Domain_Repository_TerminRepository
	 * @inject
	 */
	protected $terminRepository;
	/**
	 * Teilnehmer
	 * @var Tx_Schulungen_Domain_Repository_TeilnehmerRepository
	 * @inject
	 */
	protected $teilnehmerRepository;

	/**
	 * Initializes the current action
	 *
	 * @return void
	 */
	protected function initializeAction() {
		$configurationManager = t3lib_div::makeInstance('Tx_Extbase_Configuration_ConfigurationManager');
		$extbaseFrameworkConfiguration = $configurationManager->getConfiguration(Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
		$this->settings = $extbaseFrameworkConfiguration;
	}

	/**
	 * Displays all Schulungen
	 *
	 * @return string The rendered list view
	 */
	public function indexAction() {

		// workaround if findAll-method of schulungsRepository doesn't work
		// => no data is displayed in Backend
		$schulungs = $this->schulungRepository->findByPid('1648');
		$termine = $this->terminRepository->findAll();
		foreach ($schulungs as $schulung) {
			$schulungTermine = t3lib_div::makeInstance('Tx_Extbase_Persistence_ObjectStorage');
			foreach ($termine as $termin) {
				try {
					if ($schulung->getTitel() == $termin->getSchulung()->getTitel()) {
						$schulungTermine->attach($termin);
					}
				} catch (Exception $e) {
					t3lib_div::devlog($e->getMessage(), "Schulungen", 3, array($termin));
				}

			}
			$schulung->setSchulungTermine($schulungTermine);
		}

		$numberOfTeilnehmer = $this->teilnehmerRepository->countAll();
		$numberOfTermine = $this->terminRepository->countAll();

		// include JQUERY
		// checks if t3jquery is loaded
		if (t3lib_extMgm::isLoaded('t3jquery')) {
			require_once(t3lib_extMgm::extPath('t3jquery') . 'class.tx_t3jquery.php');
			$path_to_lib = tx_t3jquery::getJqJSBE();
			$script_to_lib = tx_t3jquery::getJqJSBE(true);
		}

		$values = array(
			'schulungs' => $schulungs,
			'termine' => $numberOfTermine,
			'teilnehmer' => $numberOfTeilnehmer,
			'jquery' => $script_to_lib
		);

		$this->view->assignMultiple($values);

	}

	/**
	 * Displays a single Termin with Details
	 *
	 * @param Tx_Schulungen_Domain_Model_Termin $termin the Schulung to display
	 * @return string The rendered view
	 */
	public function detailAction(Tx_Schulungen_Domain_Model_Termin $termin) {
		$this->view->assign('termin', $termin);
	}

	/**
	 * Creates a new Schulung and forwards to the list action.
	 *
	 * @param Tx_Schulungen_Domain_Model_Schulung $newSchulung a fresh Schulung object which has not yet been added to the repository
	 * @return string An HTML form for creating a new Schulung
	 * @dontvalidate $newSchulung
	 */
	public function newAction(Tx_Schulungen_Domain_Model_Schulung $newSchulung = null) {
		$this->view->assign('newSchulung', $newSchulung);
	}

	/**
	 * Creates a new Schulung and forwards to the list action.
	 *
	 * @param Tx_Schulungen_Domain_Model_Schulung $newSchulung a fresh Schulung object which has not yet been added to the repository
	 * @return void
	 */
	public function createAction(Tx_Schulungen_Domain_Model_Schulung $newSchulung) {
		$this->schulungRepository->add($newSchulung);
		$this->flashMessageContainer->add('Your new Schulung was created.');
		$this->redirect('list');
	}

	/**
	 * Deletes an existing Schulung
	 *
	 * @param Tx_Schulungen_Domain_Model_Schulung $schulung the Schulung to be deleted
	 * @return void
	 */
	public function deleteAction(Tx_Schulungen_Domain_Model_Schulung $schulung) {
		$this->schulungRepository->remove($schulung);
		$this->flashMessageContainer->add('Your Schulung was removed.');
		$this->redirect('list');
	}

	/**
	 * Absagen eines Schulungstermins
	 *
	 * @param Tx_Schulungen_Domain_Model_Termin $termin
	 */
	public function cancelAction(Tx_Schulungen_Domain_Model_Termin $termin) {

		$time = new DateTime();
		$time->setTimestamp(time());

		if ($termin->getStartzeit() > $time) {
			$termin->setAbgesagt(true);
			$this->terminRepository->update($termin);

			/** @var Tx_Schulungen_Controller_BenachrichtigungController benachrichtigung */
			$this->benachrichtigung = t3lib_div::makeInstance('Tx_Schulungen_Controller_BenachrichtigungController');
			$teilnehmer = $termin->getTeilnehmer();
			$result = $this->benachrichtigung->sendeBenachrichtigungSofortAction($teilnehmer, $termin, $this);

			$this->flashMessageContainer->add(Tx_Extbase_Utility_Localization::translate('tx_schulungen_controller_backend_cancel.success', 'schulungen'));
		} else {
			$this->flashMessageContainer->add(Tx_Extbase_Utility_Localization::translate('tx_schulungen_controller_backend_timeout', 'schulungen'));
		}

		$this->redirect('index');

	}

	/**
	 * Wieder Zusagen eines Schulungstermins
	 * @param Tx_Schulungen_Domain_Model_Termin $termin
	 */
	public function uncancelAction(Tx_Schulungen_Domain_Model_Termin $termin) {

		$time = new DateTime();
		$time->setTimestamp(time());

		if ($termin->getStartzeit() > $time) {
			$termin->setAbgesagt(false);
			$this->terminRepository->update($termin);

			/** @var Tx_Schulungen_Controller_BenachrichtigungController benachrichtigung */
			$this->benachrichtigung = t3lib_div::makeInstance('Tx_Schulungen_Controller_BenachrichtigungController');
			$teilnehmer = $termin->getTeilnehmer();
			$result = $this->benachrichtigung->sendeBenachrichtigungSofortAction($teilnehmer, $termin, $this);

			$this->flashMessageContainer->add(Tx_Extbase_Utility_Localization::translate('tx_schulungen_controller_backend_uncancel.success', 'schulungen'));
		} else {
			$this->flashMessageContainer->add(Tx_Extbase_Utility_Localization::translate('tx_schulungen_controller_backend_timeout', 'schulungen'));
		}

		$this->redirect('index');

	}

	/**
	 * action update
	 *
	 * @return string The rendered update action
	 * @param $schulung
	 */
	public function updateAction(Tx_Schulungen_Domain_Model_Schulung $schulung) {
		$this->schulungRepository->update($schulung);
	}

	/**
	 * Just a test for fed
	 *
	 * @return void
	 */
	public function exportAction() {
		$schulungs = $this->schulungRepository->findAll();
		$this->view->assign('fluidVarsObject', $schulungs);
	}
    
    /**
     * export a Termin
     * 
     * @param Tx_Schulungen_Domain_Model_Termin $termin
     */
    public function exportterminAction(Tx_Schulungen_Domain_Model_Termin $termin) {
        $teilnehmerListe = array();
        
        $temp = $termin->getTeilnehmer();
        $startzeit = $termin->getStartzeit();
        
        $schulung = $termin->getSchulung();
        
        $schulungsTitel = $schulung->getTitel();
        
        
        foreach($temp as $key => $row){
            $teilnehmerListe['teilnehmer'][$key]['vorname'] = $row->getVorname();
            $teilnehmerListe['teilnehmer'][$key]['name'] = $row->getNachname();
            $teilnehmerListe['teilnehmer'][$key]['email'] = $row->getEmail();
            $teilnehmerListe['teilnehmer'][$key]['studienfach'] = $row->getStudienfach();
            $teilnehmerListe['teilnehmer'][$key]['bemerkung'] = $row->getBemerkung();
        }
        
        $headers = array(
            'Pragma' => 'public',
            'Expires' => 0,
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Cache-Control' => 'public',
            'Content-Description' => 'File Transfer',
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=Export.csv',
            //'Content-Transfer-Encoding' => 'ASCII'
        );
        
        foreach ($headers as $header => $data){
            $this->response->setHeader($header, $data);
        }
        
        $this->response->sendHeaders();

        $outstream = fopen("php://output", "w");
        
        //description
        fputcsv($outstream, array($schulungsTitel.' am '.$startzeit->format('d.m.Y'), ), ';');
        //headline
        fputcsv($outstream, array('Teilnehmer (Name, Vorname)', 'E-Mail', 'Fachrichtung/Studiengang', 'Bemerkung'), ';');
        
        //data
        foreach($teilnehmerListe['teilnehmer'] as $teilnehmer){
            
            fputcsv(
                $outstream, 
                array(
                    $this->getEncodedString($teilnehmer['name']).', '.$this->getEncodedString($teilnehmer['vorname']),
                    $this->getEncodedString($teilnehmer['email']),
                    $this->getEncodedString($teilnehmer['studienfach']),
                    str_replace(array('\r\n', '\r', '\n'), ' ', $this->getEncodedString($teilnehmer['bemerkung']))
                ), 
                ';' 
            );
        }

        fclose($outstream);
        
        exit;
	}
    
    /**
     * encode string to easy import in Excel
     * 
     * @param type $string
     * @return type
     */
    private function getEncodedString($string){
        //return mb_convert_encoding ( $string , 'ASCII' , mb_internal_encoding($string) );
        
        //für direkte Öffnen in Excel, ohne Dialog
        return mb_convert_encoding ( $string , 'ISO-8859-1', 'UTF-8' );
    }
    
    /**
     * 
     * @param Tx_Schulungen_Domain_Repository_SchulungRepository $schulungRepository
     */
    public function injectSchulungRepository(Tx_Schulungen_Domain_Repository_SchulungRepository $schulungRepository){
        $this->schulungRepository = $schulungRepository;             
    }
    
    /**
     * 
     * @param Tx_Schulungen_Domain_Repository_TerminRepository $terminRepository
     */
    public function injectTerminRepository(Tx_Schulungen_Domain_Repository_TerminRepository $terminRepository){
        $this->terminRepository = $terminRepository;             
    }
    
    /**
     * 
     * @param Tx_Schulungen_Domain_Repository_TeilnehmerRepository $teilnehmerRepository
     */
    public function injectTeilnehmerRepository(Tx_Schulungen_Domain_Repository_TeilnehmerRepository $teilnehmerRepository){
        $this->teilnehmerRepository = $teilnehmerRepository;             
    }
}

?>