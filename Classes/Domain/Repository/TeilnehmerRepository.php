<?php
namespace Subugoe\Schulungen\Domain\Repository;

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
use Subugoe\Schulungen\Domain\Model\Teilnehmer;
use Subugoe\Schulungen\Domain\Model\Termin;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Repository for Teilnehmer
 */

class TeilnehmerRepository extends Repository
{
    protected $defaultOrderings = [
        'nachname' => QueryInterface::ORDER_ASCENDING
    ];

    /**
     * @param Teilnehmer $teilnehmer
     * @param Termin $termin
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function teilnehmerAngemeldet(Teilnehmer $teilnehmer, Termin $termin)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('termin', $termin),
                $query->equals('email', $teilnehmer->getEmail()),
                $query->equals('vorname', $teilnehmer->getVorname()),
                $query->equals('nachname', $teilnehmer->getNachname())
            )
        );
        $query->setOrderings(['nachname' => QueryInterface::ORDER_ASCENDING]);
        return $query->execute();
    }

    /*
     * @param int $terminUid
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findTeilnehmerToBeSubstituted($terminUid)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('termin', $terminUid),
                $query->equals('substitution', 1)
            )
        );
        $query->setOrderings(['uid' => QueryInterface::ORDER_ASCENDING]);
        $query->setLimit(1);
        return $query->execute();
    }

    /*
     * @param int $terminUid
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findNumberOfWaitinglistTeilnehmer($terminUid)
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('termin', $terminUid),
                $query->equals('substitution', 1)
            )
        );
        $query->setOrderings(['uid' => QueryInterface::ORDER_ASCENDING]);
        return $query->execute()->count();
    }

}