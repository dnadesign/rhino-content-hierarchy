<?php
/**
 * Category is the top level grouping
 *
 * @package rhino
 */
class RhinoCategory extends RhinoPage {

	private static $default_parent = 'RhinoAccount';

	private static $allowed_children = array("RhinoCapability");

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

	public function getAssessments() {
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
	
}

class RhinoCategory_Controller extends RhinoPage_Controller {

}