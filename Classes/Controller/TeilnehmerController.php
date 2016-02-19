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
use Subugoe\Schulungen\Domain\Model\Teilnehmer;
use Subugoe\Schulungen\Domain\Model\Termin;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Controller for the Teilnehmer object
 *
 */
class TeilnehmerController extends ActionController
{

    /**
     * @var \Subugoe\Schulungen\Domain\Repository\TeilnehmerRepository
     * @inject
     */
    protected $teilnehmerRepository;
    /**
     *
     * @var \Subugoe\Schulungen\Domain\Repository\TerminRepository
     * @inject
     */
    protected $terminRepository;
    /**
     *
     * @var \Subugoe\Schulungen\Controller\EmailController
     * @inject
     */
    protected $emailController;

    /**
     * @var \Subugoe\Schulungen\Controller\SchulungController
     * @inject
     */
    protected $schulungController;

    /**
     * Displays all Teilnehmers
     *
     * @param Schulung $schulung
     * @param string $status
     * @return string The rendered list view
     */
    public function listAction(Schulung $schulung, $status)
    {
        $participants = $this->teilnehmerRepository->findAll();
        $this->view->assign('teilnehmers', $participants);

        $this->view->assign('schulungsTitel', $schulung->getTitel());
        $this->view->assign('status', $status);
        $this->view->assign('contacts', $schulung->getContact());
    }

    /**
     * Displays a single Teilnehmer
     *
     * @param Teilnehmer $teilnehmer the Teilnehmer to display
     * @return string The rendered view
     */
    public function showAction(Teilnehmer $teilnehmer)
    {
        $this->view->assign('teilnehmer', $teilnehmer);
    }

    /**
     * Creates a new Teilnehmer and forwards to the list action.
     *
     * @param Teilnehmer $newTeilnehmer a fresh Teilnehmer object which has not yet been added to the repository
     * @param Termin $termin
     * @return string An HTML form for creating a new Teilnehmer
     * @dontvalidate $newTeilnehmer
     */
    public function newAction(Teilnehmer $newTeilnehmer = null, Termin $termin = null)
    {

        $termin = intval($this->request->getArgument('termin'));
        /** @var Termin $terminObj */
        $terminObj = $this->terminRepository->findByUid($termin);
        $schulung = $terminObj->getSchulung();
        $schulungsTitel = $schulung->getTitel();
        $contacts = $schulung->getContact();

        $this->view->assign('teilnehmerTermin', $terminObj);
        $this->view->assign('newTeilnehmer', $newTeilnehmer);
        $this->view->assign('schulungsTitel', $schulungsTitel);
        $this->view->assign('contacts', $contacts);

        if ($terminObj->getAnzahlTeilnehmer() >= $terminObj->getSchulung()->getTeilnehmerMax()) {
            $this->redirect('show', 'Schulung', null, ['schulung' => $schulung]);
        }

    }

    /**
     * Creates a new Teilnehmer and forwards to the list action.
     *
     * @param Teilnehmer $newTeilnehmer a fresh Teilnehmer object which has not yet been added to the repository
     */
    public function createAction(Teilnehmer $newTeilnehmer)
    {
        $status = 'fail';
        $time = new \DateTime('now');
        $termin = intval($this->request->getArgument('termin'));
        /** @var Termin $termin */
        $termin = $this->terminRepository->findByUid($termin);
        $schulung = $termin->getSchulung();

        $newTeilnehmer->setTermin($termin);
        // secret-identifier: timestamp,lastname,email,prename
        $identifier = md5($time->getTimestamp() . $newTeilnehmer->getNachname() .
            $newTeilnehmer->getEmail() . $newTeilnehmer->getVorname());
        $newTeilnehmer->setSecret($identifier);

        if ($termin->getAnzahlTeilnehmer() < $schulung->getTeilnehmerMax()) {

            if (count($this->teilnehmerRepository->teilnehmerAngemeldet($newTeilnehmer, $termin)) == 0) {

                try {
                    $this->teilnehmerRepository->add($newTeilnehmer);
                    $error = false;
                } catch (IllegalObjectTypeException $e) {
                    $error = true;
                }
                if ($error === false) {
                    //mail versenden
                    $this->controllerContext->getFlashMessageQueue()->getAllMessagesAndFlush();
                    $this->addFlashMessage(LocalizationUtility::translate('tx_schulungen_controller_teilnehmer_create.bestaetigung.text',
                        'schulungen'));

                    if ($sender = $this->sendeBestaetigungsMail($newTeilnehmer)) {
                        $this->addFlashMessage(LocalizationUtility::translate('tx_schulungen_controller_teilnehmer_create.bestaetigung.email',
                            'schulungen', [$newTeilnehmer->getEmail()]));
                        $status = 'success';
                    } else {
                        $this->addFlashMessage(LocalizationUtility::translate('tx_schulungen_email_versand.fail',
                            'schulungen'));
                    }
                }
                // Teilnehmer existiert schon
            } else {
                $this->controllerContext->getFlashMessageQueue()->getAllMessagesAndFlush();
                $this->addFlashMessage(LocalizationUtility::translate('tx_schulungen_controller_teilnehmer_create.already_registered',
                    'schulungen'));
            }
            // Termin ist voll
        } else {
            $this->controllerContext->getFlashMessageQueue()->getAllMessagesAndFlush();
            $this->addFlashMessage(LocalizationUtility::translate('tx_schulungen_controller_teilnehmer_create.fail',
                'schulungen'));
        }

        //an die list-action weiterleiten
        $this->redirect('list', 'Teilnehmer', null, ["schulung" => $schulung, "status" => $status]);
    }

    /**
     * Mailsendemethode
     * @todo Termin usw in Mail mit angeben. View?
     * @param Teilnehmer $teilnehmer
     * @return boolean
     */
    private function sendeBestaetigungsMail(Teilnehmer $teilnehmer)
    {
        $time = new \DateTime('now');
        $recipient = $teilnehmer->getEmail();
        $sender = $this->settings['mail']['fromMail'];
        $senderName = $this->settings['mail']['fromName'];
        $mailcopy = [];
        $contacts = $teilnehmer->getTermin()->getSchulung()->getContact();
        foreach ($contacts as $contact) {
            array_push($mailcopy, $contact->getEmail());
        }

        $variables = [
            'nachname' => $teilnehmer->getNachname(),
            'vorname' => $teilnehmer->getVorname(),
            'email' => $teilnehmer->getEmail(),
            'studienfach' => $teilnehmer->getStudienfach(),
            'bemerkung' => $teilnehmer->getBemerkung(),
            'schulungsTitel' => $teilnehmer->getTermin()->getSchulung()->getTitel(),
            'startZeit' => $teilnehmer->getTermin()->getStartzeit(),
            'ende' => $teilnehmer->getTermin()->getEnde(),
            'timestamp' => $time,
            'identifier' => [$teilnehmer->getSecret()],
            'mailcopy' => $mailcopy,
            'copy' => true,
        ];

        $templateName = LocalizationUtility::translate('tx_schulungen_email_bestaetigung_template', 'schulungen');

        $title = explode(':', $teilnehmer->getTermin()->getSchulung()->getTitel());
        $subject = LocalizationUtility::translate('tx_schulungen_email_bestaetigung_subject',
                'schulungen') . " (" . $title[0] . " - " . $teilnehmer->getTermin()->getStartzeit()->format('d.m.Y H:i') . ")";

        return $this->emailController->sendeMail($recipient, $sender, $senderName, $subject, $templateName, $variables);

    }

    /**
     * Updates an existing Teilnehmer and forwards to the index action afterwards.
     *
     * @param Teilnehmer $teilnehmer the Teilnehmer to display
     * @return string A form to edit a Teilnehmer
     */
    public function editAction(Teilnehmer $teilnehmer)
    {
        $this->view->assign('teilnehmer', $teilnehmer);
    }

    /**
     * Updates an existing Teilnehmer and forwards to the list action afterwards.
     *
     * @param Teilnehmer $teilnehmer the Teilnehmer to display
     */
    public function updateAction(Teilnehmer $teilnehmer)
    {
        $this->teilnehmerRepository->update($teilnehmer);
        $this->addFlashMessage('Your Teilnehmer was updated.');
        $this->redirect('list');
    }

    /**
     * Updates an existing Teilnehmer and forwards to the list action afterwards.
     *
     * @param Teilnehmer $teilnehmer the Teilnehmer to display
     */
    public function updateBackendAction(Teilnehmer $teilnehmer)
    {
        $this->teilnehmerRepository->update($teilnehmer);
        $this->addFlashMessage('Your Teilnehmer was updated.');
        $this->redirect('detail', 'Backend', 'schulungen', ["termin" => $teilnehmer->getTermin()]);
    }

    /**
     * De-Registration of a Teilnehmer by Mail-Notification
     *
     * @param array $identifier Teilnehmer to be de-registered
     * @ignorevalidation $identifier
     */
    public function deregisterAction($identifier)
    {
        $time_format = LocalizationUtility::translate('tx_schulungen_format.date', 'schulungen');
        $now = new \DateTime();
        GeneralUtility::devlog("De-Registration: Passed value " . $identifier[0], "Schulungen", 1, $identifier);

        /** @var Teilnehmer $teilnehmer */
        if (count($teilnehmer = $this->teilnehmerRepository->findOneBySecret($identifier[0])) > 0) {
            /** @var Schulung $schulung */
            $schulung = $teilnehmer->getTermin()->getSchulung();

            // create deep copy of termin to prevent db-update
            $termin = unserialize(serialize($teilnehmer->getTermin()));
            $this->view->assign('schulungsTitel',
                $schulung->getTitel() . ' (' . $termin->getStartzeit()->format($time_format) . ')');
            $this->view->assign('contacts', $schulung->getContact());

            // Deadline is the start time of the event
            $limit = $termin->getStartzeit();

            // Only delete Teilnehmer, if deadline isn't crossed
            if ($now < $limit) {
                $this->teilnehmerRepository->remove($teilnehmer);
                $flashMsg = LocalizationUtility::translate('tx_schulungen_domain_model_teilnehmer.deregister.success.flash',
                    'schulungen');
                $this->addFlashMessage($flashMsg . ' ' . $teilnehmer->getVorname() . ' ' . $teilnehmer->getNachname() . ' (' . $teilnehmer->getEmail() . ')');

                // Mail notification
                $sender = $this->settings['mail']['fromMail'];
                $senderName = $this->settings['mail']['fromName'];
                $title = explode(':', $teilnehmer->getTermin()->getSchulung()->getTitel());
                $subject = LocalizationUtility::translate('tx_schulungen_domain_model_teilnehmer.deregister',
                        'schulungen') . " (" . $title[0] . " - " . $teilnehmer->getTermin()->getStartzeit()->format('d.m.Y H:i') . ")";
                $templateName = LocalizationUtility::translate('tx_schulungen_email_deregistration_template',
                    'schulungen');
                $result = $this->emailController->sendeTransactionMail($sender, $senderName, $subject, $templateName,
                    [
                        'teilnehmer' => $teilnehmer,
                        'schulung' => $schulung->getTitel(),
                        'termin' => $termin->getStartzeit(),
                        'ende' => $termin->getEnde()
                    ]
                );
                GeneralUtility::devlog("De-Registration: TransactionMail sent?", "Schulungen", 1, [$result]);

                $this->view->assign('status', 'success');
            } else {
                $flashMsg = LocalizationUtility::translate('tx_schulungen_domain_model_teilnehmer.deregister.fail.flash',
                    'schulungen');
                $this->addFlashMessage(str_replace('###TEILNEHMER###',
                    $teilnehmer->getVorname() . ' ' . $teilnehmer->getNachname() . ' (' . $teilnehmer->getEmail() . ')',
                    $flashMsg));
                $this->addFlashMessage(str_replace('###TEILNEHMER###',
                    $teilnehmer->getVorname() . ' ' . $teilnehmer->getNachname() . ' (' . $teilnehmer->getEmail() . ')',
                    $flashMsg));
                $this->view->assign('status', 'fail');
            }
        } else {
            $this->redirect('list', 'Schulung');
        }
    }

    /**
     * Deletes an existing Teilnehmer
     *
     * @param Teilnehmer $teilnehmer the Teilnehmer to be deleted
     */
    public function deleteAction(Teilnehmer $teilnehmer)
    {
        $this->teilnehmerRepository->remove($teilnehmer);
        $this->addFlashMessage('Your Teilnehmer was removed.');
        $this->redirect('list');
        $this->redirect('detail', 'Backend', 'schulungen', ["termin" => $teilnehmer->getTermin()]);
    }

    /**
     * Deletes an existing Teilnehmer
     *
     * @param Teilnehmer $teilnehmer the Teilnehmer to be deleted
     */
    public function deleteBackendAction(Teilnehmer $teilnehmer)
    {
        $this->teilnehmerRepository->remove($teilnehmer);
        $this->addFlashMessage('Your Teilnehmer was removed.');
        $this->redirect('detail', 'Backend', 'schulungen', ["termin" => $teilnehmer->getTermin()]);
    }

}
