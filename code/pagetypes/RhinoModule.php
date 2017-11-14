<?php

/**
 * A Module is a set of information that is shown to the user along with an
 * {@link Assignment} for the user to complete. This instance sits under a
 * {@link Capability} instance.
 *
 * @package rhino
 */
class RhinoModule extends RhinoPage {

	private static $default_parent = 'RhinoCapability';

	private static $allowed_children = array("RhinoAssessment");

	/*****
	* Helper methods to find children
	****/
	public function getAssignments() {
		return RhinoAssessment::get()->filter('ParentID', $this->ID);
	}

	/*****
	* Helper methods to find parent
	****/
	public function getCapability() {
		return DataObject::get_by_id('RhinoCapability',  $this->ParentID);
	}

	public function getAccount() {
		$cap = $this->getCapability();
		if ($cap) {
			$cat = $cap->getCategory();
			if ($cat) {
				return $cat->getAccount();
			}
		}
		return singleton('RhinoAccount');
	}

}

class RhinoModule_Controller extends RhinoPage_Controller {

}