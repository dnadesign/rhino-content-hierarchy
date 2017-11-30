<?php

class RhinoAssessmentHierarchyExtension extends DataExtension {

	/**
	* Helper methods
	*/
	public function getModule() {
		return $this->owner->getParentOfType('RhinoModule');
	}

	public function getCapability() {
		return $this->owner->getParentOfType('RhinoCapability');
	}

	public function getCategory() {
		return $this->owner->getParentOfType('RhinoCategory');
	}

	public function getAccount() {
		return $this->owner->getParentOfType('RhinoAccount');
	}

	public function getParentOfType($type) {
		$parents = $this->owner->getBreadcrumbItems();
		$parents->remove($this);

		return $parents->find('ClassName', $type);
	}
}