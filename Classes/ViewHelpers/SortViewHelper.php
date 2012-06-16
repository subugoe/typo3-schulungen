<?php

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

/**
 * Helper-Class for automatical flexform inclusion
 *
 * @version $Id: SortViewHelper.php 1590 2012-01-13 17:38:19Z simm $
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Schulungen_ViewHelpers_SortViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractTagBasedViewHelper {

	private $orderBy;
	private $order;

	/**
	 * Sorts the given elements
	 *
	 * @param Tx_Extbase_Persistence_ObjectStorage $objects
	 * @param string $orderBy
	 * @param string $order
	 * @return Tx_Extbase_Persistence_ObjectStorage sorted Objects
	 * @api
	 */
	public function render(Tx_Extbase_Persistence_ObjectStorage $objects, $orderBy=NULL, $order=NULL) {
		
		$this->orderBy = $orderBy;
		$this->order = $order;
		
		$sort_objects = $this->sortObjectStorage($objects);
		return $sort_objects;
	}


	protected function sortObjectStorage($storage) {

		$sorted_array = array();
		foreach ($storage as $item) {
			$sorted_array[] = $item;
		}

		usort($sorted_array,array($this,compare));

		$this->objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');
		$sorted_storage = $this->objectManager->get('Tx_Extbase_Persistence_ObjectStorage');
		foreach ($sorted_array as $item) {
			$sorted_storage->attach($item);
		}

		return $sorted_storage;
	}

	protected function compare($object1, $object2){

		if ($this->getSortValue($object1) == $this->getSortValue($object2)) {
			return 0;
		}
		if ($this->order === 'ASC')
			return ($this->getSortValue($object1) < $this->getSortValue($object2)) ? -1 : 1;

		return ($this->getSortValue($object1) > $this->getSortValue($object2)) ? -1 : 1;
	}

	protected function getSortValue($object) {

		if ($this->orderBy) {
			$getter = 'get' . ucfirst($this->orderBy);
		} else {
			$getter = "getUid";
		}

		if (method_exists($object, $getter)) {
			$value = $object->$getter();
		} else if (is_object ($object)) {
			$value = $object->$field;
		} else if (is_array($object)) {
			$value = $object[$field];
		}

		if ($value instanceof DateTime) {
			$value = $value->getTimestamp();
		}

		return $value;
	}
}

?>