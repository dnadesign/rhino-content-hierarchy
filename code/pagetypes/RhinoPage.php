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
}

class RhinoPage_Controller extends Page_Controller {

}