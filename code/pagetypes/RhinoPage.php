<?php

class RhinoPage extends Page {

	private static $hide_ancestor = 'RhinoPage';

	private static $db = array(
		'Description' => 'Text'
	);

	private static $summary_fields = array(
		'Title' => 'Name',
		'Description' => 'Description'
	);

	public function getCMSFields() {
		$fields = parent::getCMSFields();

		$this->beforeUpdateCMSFields( function() {
			// Move specific fields
			$urlSegment = $fields->fieldByName('Root.Main.URLSegment');
			$menuTitle = $fields->fieldByName('Root.Main.MenuTitle');
			$content = $fields->fieldByName('Root.Main.Content');
			$metadata = $fields->fieldByName('Root.Main.Metadata');
			$fields->removeByName('Metadata');

			$fields->addFieldsToTab('Root.Main', array($urlSegment, $menuTitle, $content, $metadata));

			// Rename Page Name to Name to avoid confusion
			$title = $fields->fieldBYName('Root.Main.Title');
			$title->setTitle('Name');
		});
		
		// Do not add the description here
		// Add it on each object on the project specific extensions
		
		return $fields;
	}

	public function canView($member = null) {
		if(!$member || !(is_a($member, 'Member'))) {
			$member = Member::currentUser();
		} else if (is_numeric($member)) {
			$member = Member::get_by_id('Member', $member);
		}
		if (!parent::canView($member)) {
			return false;
		}
		$pc = Injector::inst()->get('RhinoPermissionCheck');
		return $pc->can($member, 'view', $this);cd
	}

	public function canCreate($member = null) {
		if(!$member || !(is_a($member, 'Member'))) {
			$member = Member::currentUser();
		} else if (is_numeric($member)) {
			$member = Member::get_by_id('Member', $member);
		}
		$context = func_num_args() > 1 ? func_get_arg(1) : array();
		if (!parent::canCreate($member, $context)) {
			return false;
		}
		$pc = Injector::inst()->get('RhinoPermissionCheck');
		return $pc->can($member, 'create', $this);
	}

	public function canEdit($member = null) {
		if(!$member || !(is_a($member, 'Member'))) {
			$member = Member::currentUser();
		} else if (is_numeric($member)) {
			$member = Member::get_by_id('Member', $member);
		}
		if (!parent::canEdit($member)) {
			return false;
		}
		$pc = Injector::inst()->get('RhinoPermissionCheck');
		return $pc->can($member, 'edit', $this);
	}

	public function canDelete($member = null) {
		if(!$member || !(is_a($member, 'Member'))) {
			$member = Member::currentUser();
		} else if (is_numeric($member)) {
			$member = Member::get_by_id('Member', $member);
		}
		if (!parent::canDelete($member)) {
			return false;
		}
		$pc = Injector::inst()->get('RhinoPermissionCheck');
		return $pc->can($member, 'delete', $this);
	}
}

class RhinoPage_Controller extends Page_Controller {

}