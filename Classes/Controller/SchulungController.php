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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Controller for the Schulung object
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class SchulungController extends ActionController
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
     * Person
     * @var \Subugoe\Schulungen\Domain\Repository\PersonRepository
     * @inject
     */
    protected $personRepository;

    /**
     * Displays all Schulungs
     *
     * @return string The rendered list view
     */
    public function listAction()
    {

        $schulungSet = [];
        for ($i = 0; $i < 3; $i++) {
            if ($this->schulungRepository->countByKategorie($i) > 0) {
                $schulungSet[$i] = $this->schulungRepository->findByKategorie($i);
            }
        }
        $contact = $this->personRepository->findByUid($this->settings['contact']);

        $values = [
            "schulungs" => $schulungSet,
            "contact" => $contact
        ];

        $this->view->assignMultiple($values);

    }

    /**
     * Displays all Schulungs in slim format
     *
     * @param string &$tmp
     * @param object &$obj
     */
    public function listSlimAction(&$tmp = null, &$obj = null)
    {

        $extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);

        $templateRootPath = GeneralUtility::getFileAbsFileName($extbaseFrameworkConfiguration['plugin.']['tx_schulungen.']['settings.']['view.']['templateRootPath']);
        $templatePathAndFilename = $templateRootPath . 'Schulung/ListSlim.html';
        /** @var StandaloneView $view */
        $view = $this->objectManager->get(StandaloneView::class);
        $view->setTemplatePathAndFilename($templatePathAndFilename);
        $view->setFormat('html');

        $schulungSet = [];
        for ($i = 0; $i < 3; $i++) {
            if ($this->schulungRepository->countByKategorie($i) > 0) {
                $schulungSet[$i] = $this->schulungRepository->findByKategorie($i);
            }
        }
        $values = [
            "schulungs" => $schulungSet,
        ];

        $view->assignMultiple($values);

        $tmp = $view->render() . $tmp;

    }

    /**
     * Insert into TOC-menu-section: english appreciation for no translation
     *
     * @param string &$tmp
     * @param object &$obj
     */
    public function modTOCAction(&$tmp = null, &$obj = null)
    {

        if ($GLOBALS['TSFE']->lang == "en") {
            $tmp = "<li>We regret that this page is not available in English.</li>";
        }

    }

    /**
     * Termine und Teilnehmer pro Schulung anzeigen
     */
    public function listTermineUndTeilnehmerAction()
    {

        $schulung = $this->schulungRepository->findAll();
        $this->view->assign('schulung', $schulung);
    }

    /**
     * Displays a single Schulung
     *
     * @param Schulung $schulung the Schulung to display
     * @return string The rendered view
     */
    public function showAction(Schulung $schulung)
    {

        $termine = $this->terminRepository->errechneAnstehendeSchulungTermine($schulung);

        $time = new \DateTime();
        $time->setTimestamp(time());

        $this->view->assign('time', $time);
        if (count($termine) > 0) {
            $this->view->assign('termine', $termine);
        }
        $this->view->assign('schulung', $schulung);
        $this->view->assign('contacts', $schulung->getContact());
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
     * action edit
     *
     * @return string The rendered edit action
     * @param $schulung
     */
    public function editAction(Schulung $schulung)
    {
        $this->view->assign('schulung', $schulung);
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
     * action export
     *
     * @return void
     * @param int $uid
     */
    public function exportAction($uid = null)
    {
        $schulungs = $this->schulungRepository->findAll();
        $this->view->assign('fluidVarsObject', $schulungs);
    }

}
