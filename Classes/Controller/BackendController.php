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
     * Initializes the current action
     *
     * @return void
     */
    protected function initializeAction()
    {
        $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
        $this->settings = $extbaseFrameworkConfiguration;
        $GLOBALS['TT'] = new NullTimeTracker();

    }

    /**
     * Displays all Schulungen
     *
     * @return string The rendered list view
     */
    public function indexAction()
    {

        // workaround if findAll-method of schulungsRepository doesn't work
        // => no data is displayed in Backend
        $schulungs = $this->schulungRepository->findByPid('1648');
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

        // include JQUERY
        // checks if t3jquery is loaded
        if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('t3jquery')) {
            require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('t3jquery') . 'class.tx_t3jquery.php');
            $path_to_lib = \tx_t3jquery::getJqJSBE();
            $script_to_lib = \tx_t3jquery::getJqJSBE(true);
        }

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
     * @return void
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
     * @return void
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
     *
     * @return void
     */
    public function exportAction()
    {
        $schulungs = $this->schulungRepository->findAll();
        $this->view->assign('fluidVarsObject', $schulungs);
    }

}
