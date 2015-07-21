<?php
namespace Subugoe\Schulungen\ViewHelpers;

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Dominic Simm <dominic.simm@sub.uni-goettingen.de>, Goettingen State Library
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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * Sort ViewHelper
 */
class SortViewHelper extends AbstractTagBasedViewHelper
{

    private $orderBy;
    private $order;

    /**
     * Sorts the given elements
     *
     * @param ObjectStorage $objects
     * @param string $orderBy
     * @param string $order ASCor DESC
     * @return ObjectStorage sorted Objects
     */
    public function render(ObjectStorage $objects, $orderBy = null, $order = null)
    {

        $this->orderBy = $orderBy;
        $this->order = $order;

        $sortObjects = $this->sortObjectStorage($objects);
        return $sortObjects;
    }

    /**
     * @param $storage
     * @return object
     */
    protected function sortObjectStorage($storage)
    {

        $sortedArray = [];
        foreach ($storage as $item) {
            $sortedArray[] = $item;
        }

        usort($sortedArray, [$this, 'compare']);
        /** @var ObjectManager $objectManager */
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);

        /** @var ObjectStorage $sorted_storage */
        $sorted_storage = $objectManager->get(ObjectStorage::class);
        foreach ($sortedArray as $item) {
            $sorted_storage->attach($item);
        }

        return $sorted_storage;
    }

    /**
     * @param $object1
     * @param $object2
     * @return int
     */
    protected function compare($object1, $object2)
    {

        if ($this->getSortValue($object1) == $this->getSortValue($object2)) {
            return 0;
        }
        if ($this->order === 'ASC') {
            return ($this->getSortValue($object1) < $this->getSortValue($object2)) ? -1 : 1;
        }

        return ($this->getSortValue($object1) > $this->getSortValue($object2)) ? -1 : 1;
    }

    /**
     * @param $object
     * @return mixed
     */
    protected function getSortValue($object)
    {

        if ($this->orderBy) {
            $getter = 'get' . ucfirst($this->orderBy);
        } else {
            $getter = "getUid";
        }

        if (method_exists($object, $getter)) {
            $value = $object->$getter();
        } else {
            if (is_object($object)) {
                $value = $object->$field;
            } else {
                if (is_array($object)) {
                    $value = $object[$field];
                }
            }
        }

        if ($value instanceof \DateTime) {
            $value = $value->getTimestamp();
        }

        return $value;
    }
}
