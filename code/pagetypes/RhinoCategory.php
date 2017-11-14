<?php
/**
 * Category is the top level grouping
 *
 * @package rhino
 */
class RhinoCategory extends RhinoPage {

	private static $default_parent = 'RhinoAccount';

	private static $allowed_children = array("RhinoCapability");

	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->removeByName('Content');
		return $fields;
	}

	/*****
	* Helper methods to find children
	****/

	public function getCapabilities() {
		return RhinoCapability::get()->filter('ParentID', $this->ID);
	}

	public function getModules() {
		$capabilities = $this->getCapabilities();
		if ($capabilities && $capabilities->Count() > 0) {
			return RhinoModule::get()->filter('ParentID', $capabilities->column('ID'));
		}

		return null;
	}

	public function getAssignments() {
		$modules = $this->getModules();
		if ($modules && $modules->Count() > 0) {
			return RhinoAssessment::get()->filter('ParentID', $modules->column('ID'));
		}

		return null;
	}

	/*****
	* Helper methods to find parent
	****/
	public function getAccount() {
		return DataObject::get_by_id('RhinoAccount', $this->ParentID);
	}


	// /**
	//  *
	//  */
	// public function onAfterDelete() {
	// 	parent::onAfterDelete();

	// 	foreach($this->Capabilities() as $capability) {
	// 		$capability->delete();
	// 	}
	// }

	// /**
	// * Return next module
	// * @return RhinoModule
	// */
	// public function getNextCapability($currentCapabilityID) {
	// 	if (!$currentCapabilityID) return null;

	// 	$capabilities = $this->Capabilities();
	// 	if ($capabilities->Last()->ID == $currentCapabilityID) return null;

	// 	$return = false;
	// 	foreach($capabilities as $capability) {
	// 		if ($return && $capability->Modules()->Count() > 0) {
	// 			return $capability;
	// 		}
	// 		if ($capability->ID == $currentCapabilityID) {
	// 			$return = true;
	// 		}
	// 	}

	// 	return null;
	// }

	// *
	// * Return previous module
	// * @return RhinoModule

	// public function getPreviousCapability($currentCapabilityID) {
	// 	if (!$currentCapabilityID) return null;

	// 	$capabilities = $this->Capabilities()->reverse();
	// 	if ($capabilities->Last()->ID == $currentCapabilityID) return null;

	// 	$return = false;
	// 	foreach($capabilities as $capability) {
	// 		if ($return && $capability->Modules()->Count() > 0) {
	// 			return $capability;
	// 		}
	// 		if ($capability->ID == $currentCapabilityID) {
	// 			$return = true;
	// 		}
	// 	}

	// 	return null;
	// }
}

class RhinoCategory_Controller extends RhinoPage_Controller {

}