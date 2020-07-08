<?php

namespace DNADesign\Rhino\Pagetypes;

use DNADesign\Rhino\Controllers\RhinoPageController;
use Page;

class RhinoPage extends Page
{
	private static $hide_ancestor = RhinoPage::class;

	private static $controller_name = RhinoPageController::class;

	private static $db = array(
		'Description' => 'Text'
	);

	private static $summary_fields = array(
		'Title' => 'Name',
		'Description' => 'Description'
	);

	public function getCMSFields()
	{
		$fields = parent::getCMSFields();

		// Do not add the description here
		// Add it on each object on the project specific extensions
		// NOTE: not sure this is still relevant

		return $fields;
	}
}
