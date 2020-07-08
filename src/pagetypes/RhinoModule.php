<?php

namespace DNADesign\Rhino\Pagetypes;

use DNADesign\Rhino\Controllers\RhinoModuleController;
use DNADesign\Rhino\Pagetypes\RhinoAccount;
use DNADesign\Rhino\Pagetypes\RhinoAssessment;
use DNADesign\Rhino\Pagetypes\RhinoCapability;
use DNADesign\Rhino\Pagetypes\RhinoPage;

/**
 * A Module is a set of information that is shown to the user along with an
 * {@link Assignment} for the user to complete. This instance sits under a
 * {@link Capability} instance.
 *
 * @package rhino
 */
class RhinoModule extends RhinoPage
{

	private static $default_parent = RhinoCapability::class;

	private static $controller_name = RhinoModuleController::class;

	/**
	 * Helper methods to find children
	 *
	 */
	public function getAssessments()
	{
		return RhinoAssessment::get()->filter('ParentID', $this->ID);
	}

	/**
	 * Helper methods to find parent
	 *
	 */
	public function getCapability()
	{
		return RhinoCapability::get()->byID($this->ParentID);
	}

	public function getAccount()
	{
		$cap = $this->getCapability();
		if ($cap) {
			$cat = $cap->getCategory();
			if ($cat) {
				return $cat->getAccount();
			}
		}
		return RhinoAccount::singleton();
	}
}
