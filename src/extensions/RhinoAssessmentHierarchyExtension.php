<?php

namespace DNADesign\Rhino\Extensions;

use DNADesign\Rhino\Pagetypes\RhinoAccount;
use DNADesign\Rhino\Pagetypes\RhinoCapability;
use DNADesign\Rhino\Pagetypes\RhinoCategory;
use DNADesign\Rhino\Pagetypes\RhinoModule;
use SilverStripe\ORM\DataExtension;

class RhinoAssessmentHierarchyExtension extends DataExtension
{

	/**
	 * Helper methods
	 */
	public function getModule()
	{
		return $this->owner->getParentOfType(RhinoModule::class);
	}

	public function getCapability()
	{
		return $this->owner->getParentOfType(RhinoCapability::class);
	}

	public function getCategory()
	{
		return $this->owner->getParentOfType(RhinoCategory::class);
	}

	public function getAccount()
	{
		return $this->owner->getParentOfType(RhinoAccount::class);
	}

	public function getParentOfType($type)
	{
		$parents = $this->owner->getBreadcrumbItems();
		$parents->remove($this);

		return $parents->find('ClassName', $type);
	}
}
