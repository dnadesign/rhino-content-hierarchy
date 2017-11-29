<?php

/**
 * Capability is the top level grouping of a collection of {@link Module} that a
 * user team completes on the site.
 *
 * Capabilities can be assigned across {@link RhinoAccount} and within
 * that, assigned to a {@link RhinoGroup}.
 *
 * @package rhino
 */
class RhinoCapability extends RhinoPage {

	private static $default_parent = 'RhinoCategory';

	/*****
	* Helper methods to find children
	****/
	public function getModules() {
		return RhinoModule::get()->filter('ParentID', $this->ID);
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
	public function getCategory() {
		return DataObject::get_by_id('RhinoCategory', $this->ParentID);
	}

	public function getAccount() {
		$cat = $this->getCategory();
		if ($cat) {
			return $cat->getAccount();
		}
		return singleton('RhinoAccount');
	}

}

class RhinoCapability_Controller extends RhinoPage_Controller {

}
