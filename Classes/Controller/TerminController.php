<?php
namespace Subugoe\Schulungen\Controller;

/***************************************************************
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
 ***************************************************************/
use Subugoe\Schulungen\Domain\Model\Termin;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;


/**
 * Controller for the Termin object
 */
class TerminController extends ActionController
{

    /**
     * terminRepository
     *
     * @var \Subugoe\Schulungen\Domain\Repository\TerminRepository
     * @inject
     */
    protected $terminRepository;

    /**
     * Displays a single Termin
     *
     * @param Termin $termin the Termin to display
     * @return string The rendered view
     */
    public function showAction(Termin $termin)
    {
        $this->view->assign('termin', $termin);
    }

    /**
     * action new
     *
     * @return string The rendered new action
     */
    public function newAction()
    {
        $this->view->assign('termin', 'termin');
    }

    /**
     * action export
     *
     * @param int $uid
     */
    public function exportAction($uid = null)
    {
        $parameter = GeneralUtility::_GP('tx_schulungen');
        $termin = $this->terminRepository->findByUid($parameter['uid']);
        $this->view->assign('termin', $termin);
    }
}
