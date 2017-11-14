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

	private static $allowed_children = array("RhinoModule");

	private static $can_be_root = false;

	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->removeByName('Content');
		return $fields;
	}

	/*****
	* Helper methods to find children
	****/
	public function getModules() {
		return RhinoModule::get()->filter('ParentID', $this->ID);
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

	// /**
	//  *
	//  * Determine if there are any modules with drafts
	//  *
	//  * @return boolean
	//  */
	// public function hasDraftAssignments() {
	// 	$memberID = Member::currentUserID();

	// 	$modules = $this->Modules()->map('ID', 'Title');

	// 	if(!$modules) {
	// 		return false;
	// 	}

	// 	$extra = sprintf("SubmittedByID = '%s'", $memberID);

	// 	if($modules->count() > 0) {

	// 		$draftQuery = sprintf("
	// 			SELECT COUNT(DISTINCT ParentID) FROM SubmittedForm WHERE SubmittedForm.ModuleID IN (%s) AND %s AND IsDraft=1 ",
	// 			 implode(",", $modules->keys()), $extra
	// 		);

	// 		$pending = DB::query($draftQuery);

	// 		return ($pending->value()>0);

	// 	}

	// 	return false;
	// }

}

class RhinoCapability_Controller extends RhinoPage_Controller {

}
