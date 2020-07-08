<?php

namespace DNADesign\Rhino\Pagetypes;

use DNADesign\Rhino\Controllers\RhinoCapabilityController;
use DNADesign\Rhino\Pagetypes\RhinoAccount;
use DNADesign\Rhino\Pagetypes\RhinoAssessment;
use DNADesign\Rhino\Pagetypes\RhinoCapability;
use DNADesign\Rhino\Pagetypes\RhinoModule;
use DNADesign\Rhino\Pagetypes\RhinoPage;

/**
 * Category is the top level grouping
 *
 * @package rhino
 */
class RhinoCategory extends RhinoPage
{

	private static $default_parent = RhinoAccount::class;

	private static $controller_name = RhinoCapabilityController::class;

	/**
	 * Helper methods to find children
	 *
	 */
	public function getCapabilities()
	{
		return RhinoCapability::get()->filter('ParentID', $this->ID);
	}

	public function getModules()
	{
		$capabilities = $this->getCapabilities();
		if ($capabilities && $capabilities->Count() > 0) {
			return RhinoModule::get()->filter('ParentID', $capabilities->column('ID'));
		}

		return null;
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
	public function getAccount()
	{
		return RhinoAccount::get()->byID($this->ParentID);
	}
}
