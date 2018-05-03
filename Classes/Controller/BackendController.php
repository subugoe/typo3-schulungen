<?php
namespace Subugoe\Schulungen\Controller;

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
use Subugoe\Schulungen\Domain\Model\Schulung;
use Subugoe\Schulungen\Domain\Model\Termin;
use TYPO3\CMS\Core\TimeTracker\NullTimeTracker;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Extensionmanager\Exception\MissingExtensionDependencyException;

/**
 * Controller for the Schulung object
 */
class BackendController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * schulungRepository
     *
     * @var \Subugoe\Schulungen\Domain\Repository\SchulungRepository
     * @inject
     */
    protected $schulungRepository;
    /**
     * terminRepository
     *
     * @var \Subugoe\Schulungen\Domain\Repository\TerminRepository
     * @inject
     */
    protected $terminRepository;
    /**
     * Teilnehmer
     * @var \Subugoe\Schulungen\Domain\Repository\TeilnehmerRepository
     * @inject
     */
    protected $teilnehmerRepository;

    /**
     * @var \Subugoe\Schulungen\Controller\BenachrichtigungController
     */
    protected $benachrichtigung;

    /**
     * @var string
     */
    const teilnehmerlisteFilename = 'Teilnehmerliste.csv';

    /**
     * @var string
     */
    const teilnehmer = 'Teilnehmer (Name, Vorname)';

    /**
     * @var string
     */
    const email = 'E-Mail';

    /**
     * @var string
     */
    const fachrichtungStudiengang = 'Fachrichtung/Studiengang';

    /**
     * @var string
     */
    const anmeldestatus = 'Anmeldestatus';

    /**
     * @var string
     */
    const bemerkung = 'Bemerkung';

    /**
     * @var string
     */
    const warteliste = 'Warteliste';

    /**
     * @var string
     */
    const angemeldet = 'Angemeldet';

    /**
     * Initializes the current action
     */
    protected function initializeAction()
    {
        $this->settings = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $GLOBALS['TT'] = new NullTimeTracker();
    }

    /**
     * Displays all Schulungen
     *
     * @return string The rendered list view
     */
    public function indexAction()
    {
        /** @var Schulung $schulungs */
        $schulungs = $this->schulungRepository->findByPid($this->settings['persistence']['storagePid']);
        $termine = $this->terminRepository->findAll();
        /** @var Schulung $schulung */
        foreach ($schulungs as $schulung) {
            /** @var ObjectStorage $schulungTermine */
            $schulungTermine = GeneralUtility::makeInstance(ObjectStorage::class);
            foreach ($termine as $termin) {
                try {
                    if ($schulung->getTitel() == $termin->getSchulung()->getTitel()) {
                        $schulungTermine->attach($termin);
                    }
                } catch (\Exception $e) {
                    GeneralUtility::devlog($e->getMessage(), "Schulungen", 3, [$termin]);
                }

            }
            $schulung->setSchulungTermine($schulungTermine);
        }
        $numberOfTeilnehmer = $this->teilnehmerRepository->countAll();
        $numberOfTermine = $this->terminRepository->countAll();
        $script_to_lib = $this->includeJquery();
        $values = [
            'schulungs' => $schulungs,
            'termine' => $numberOfTermine,
            'teilnehmer' => $numberOfTeilnehmer,
            'jquery' => $script_to_lib
        ];
        $this->view->assignMultiple($values);
    }

    /**
     * Displays a single Termin with Details
     *
     * @param Termin $termin the Schulung to display
     * @return string The rendered view
     */
    public function detailAction(Termin $termin)
    {
        $numberOfWaitinglistTeilnehmer = $this->teilnehmerRepository->findNumberOfWaitinglistTeilnehmer($termin->getUid());
        $numberOfRegisteredTeilnehmer = ($termin->getAnzahlTeilnehmer() - $numberOfWaitinglistTeilnehmer);
        $this->view->assign('numberOfWaitinglistTeilnehmer', $numberOfWaitinglistTeilnehmer);
        $this->view->assign('numberOfRegisteredTeilnehmer', $numberOfRegisteredTeilnehmer);
        $this->view->assign('termin', $termin);
    }

    /**
     * Creates a new Schulung and forwards to the list action.
     *
     * @param Schulung $newSchulung a fresh Schulung object which has not yet been added to the repository
     * @return string An HTML form for creating a new Schulung
     * @dontvalidate $newSchulung
     */
    public function newAction(Schulung $newSchulung = null)
    {
        $this->view->assign('newSchulung', $newSchulung);
    }

    /**
     * Creates a new Schulung and forwards to the list action.
     *
     * @param Schulung $newSchulung a fresh Schulung object which has not yet been added to the repository
     */
    public function createAction(Schulung $newSchulung)
    {
        $this->schulungRepository->add($newSchulung);
        $this->addFlashMessage('Your new Schulung was created.');
        $this->redirect('list');
    }

    /**
     * Deletes an existing Schulung
     *
     * @param Schulung $schulung the Schulung to be deleted
     */
    public function deleteAction(Schulung $schulung)
    {
        $this->schulungRepository->remove($schulung);
        $this->addFlashMessage('Your Schulung was removed.');
        $this->redirect('list');
    }

    /**
     * Absagen eines Schulungstermins
     *
     * @param Termin $termin
     */
    public function cancelAction(Termin $termin)
    {
        $time = new \DateTime();
        $time->setTimestamp(time());
        if ($termin->getStartzeit() > $time) {
            $termin->setAbgesagt(true);
            $this->terminRepository->update($termin);
            $this->benachrichtigung = $this->objectManager->get(BenachrichtigungController::class);
            $teilnehmer = $termin->getTeilnehmer();
            $result = $this->benachrichtigung->sendeBenachrichtigungSofortAction($teilnehmer, $termin, $this);
            $this->addFlashMessage(LocalizationUtility::translate('tx_schulungen_controller_backend_cancel.success',
                'schulungen'));
        } else {
            $this->addFlashMessage(LocalizationUtility::translate('tx_schulungen_controller_backend_timeout',
                'schulungen'));
        }
        $this->redirect('index');
    }

    /**
     * Wieder Zusagen eines Schulungstermins
     * @param Termin $termin
     */
    public function uncancelAction(Termin $termin)
    {
        $time = new \DateTime();
        $time->setTimestamp(time());
        if ($termin->getStartzeit() > $time) {
            $termin->setAbgesagt(false);
            $this->terminRepository->update($termin);
            /** @var \Subugoe\Schulungen\Controller\BenachrichtigungController benachrichtigung */
            $this->benachrichtigung = $this->objectManager->get(BenachrichtigungController::class);
            $teilnehmer = $termin->getTeilnehmer();
            $result = $this->benachrichtigung->sendeBenachrichtigungSofortAction($teilnehmer, $termin, $this);
            $this->addFlashMessage(LocalizationUtility::translate('tx_schulungen_controller_backend_uncancel.success',
                'schulungen'));
        } else {
            $this->addFlashMessage(LocalizationUtility::translate('tx_schulungen_controller_backend_timeout',
                'schulungen'));
        }
        $this->redirect('index');
    }

    /**
     * action update
     *
     * @return string The rendered update action
     * @param $schulung
     */
    public function updateAction(Schulung $schulung)
    {
        $this->schulungRepository->update($schulung);
    }

    /**
     * Just a test for fed
     */
    public function exportAction()
    {
        $schulungs = $this->schulungRepository->findAll();
        $this->view->assign('fluidVarsObject', $schulungs);
    }

    /**
     * @return string
     * @throws MissingExtensionDependencyException
     */
    protected function includeJquery()
    {
        // checks if t3jquery is loaded
        if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('t3jquery')) {
            require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('t3jquery') . 'class.tx_t3jquery.php');
            $script_to_lib = \T3Ext\T3jquery\Utility\T3jqueryUtility::getJqJSBE(true);
            return $script_to_lib;
        } else {
            throw new MissingExtensionDependencyException();
        }
    }

    /**
     * exports a Termin
     *
     * @param Termin $termin
     */
    public function exportterminAction(Termin $termin) {
        $rawTeilnehmerListe = $termin->getTeilnehmer();
        $teilnehmerListe = array();
        $startzeit = $termin->getStartzeit();
        $schulung = $termin->getSchulung();
        $schulungsTitel = $schulung->getTitel();
        foreach($rawTeilnehmerListe as $key => $value){
            $teilnehmerListe[$key]['vorname'] = $value->getVorname();
            $teilnehmerListe[$key]['nachname'] = $value->getNachname();
            $teilnehmerListe[$key]['email'] = $value->getEmail();
            $teilnehmerListe[$key]['studienfach'] = $value->getStudienfach();
            $teilnehmerListe[$key]['substitution'] = $value->getSubstitution() > 0 ? self::warteliste:self::angemeldet;
            $teilnehmerListe[$key]['bemerkung'] = $value->getBemerkung();
        }
        $this->setCSVHeaders(self::teilnehmerlisteFilename);
        $outstream = fopen("php://output", "w");
        //description
        fputcsv($outstream, array($schulungsTitel . ' am ' . $startzeit->format('d.m.Y'), ), ';');
        //headline
        fputcsv($outstream, array(self::teilnehmer, self::email, self::fachrichtungStudiengang, self::anmeldestatus, self::bemerkung), ';');
        //data
        foreach($teilnehmerListe as $teilnehmer){
            fputcsv(
                    $outstream,
                    array(
                            $teilnehmer['nachname'] . ', ' . $teilnehmer['vorname'],
                            $teilnehmer['email'],
                            $teilnehmer['studienfach'],
                            $teilnehmer['substitution'],
                            str_replace(array('\r\n', '\r', '\n'), ' ', $teilnehmer['bemerkung'])
                    ),
                    ';'
            );
        }
        fclose($outstream);
        exit;
    }

    /**
     * sets headers for the csv file
     *
     * @param string $teilnehmerlisteFilename
     */
    protected function setCSVHeaders($teilnehmerlisteFilename){
        $headers = array(
                'Pragma' => 'public',
                'Expires' => 0,
                'Cache-Control' => 'public, must-revalidate, post-check=0, pre-check=0',
                'Content-Description' => 'File Transfer',
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename=' . $teilnehmerlisteFilename,
                );
        foreach ($headers as $header => $data){
            $this->response->setHeader($header, $data);
        }
        $this->response->sendHeaders();
    }

}