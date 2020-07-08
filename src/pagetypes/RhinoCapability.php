<?php

namespace DNADesign\Rhino\Pagetypes;

use DNADesign\Rhino\Controllers\RhinoCapabilityController;
use DNADesign\Rhino\Pagetypes\RhinoAccount;
use DNADesign\Rhino\Pagetypes\RhinoAssessment;
use DNADesign\Rhino\Pagetypes\RhinoCategory;
use DNADesign\Rhino\Pagetypes\RhinoModule;
use DNADesign\Rhino\Pagetypes\RhinoPage;

/**
 * Capability is the top level grouping of a collection of {@link Module} that a
 * user team completes on the site.
 *
 * Capabilities can be assigned across {@link RhinoAccount} and within
 * that, assigned to a {@link RhinoGroup}.
 *
 * @package rhino
 */
class RhinoCapability extends RhinoPage
{
	private static $default_parent = RhinoCategory::class;

	private static $controller_name = RhinoCapabilityController::class;


	/**
	 * Helper methods to find children
	 *
	 */
	public function getModules()
	{
		return RhinoModule::get()->filter('ParentID', $this->ID);
	}

	public function getAssessments()
	{
		$modules = $this->getModules();
		if ($modules && $modules->Count() > 0) {
			return RhinoAssessment::get()->filter('ParentID', $modules->column('ID'));
		}

		return null;
	}

	/**
	 * Helper methods to find parent
	 *
	 */
	public function getCategory()
	{
		return RhinoCategory::get()->byID($this->ParentID);
	}

	public function getAccount()
	{
		$cat = $this->getCategory();
		if ($cat) {
			return $cat->getAccount();
		}

		return RhinoAccount::singleton();
	}
}
