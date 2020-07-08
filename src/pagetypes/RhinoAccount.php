<?php

namespace DNADesign\Rhino\Pagetypes;

use DNADesign\Rhino\Pagetypes\RhinoAssessment;
use DNADesign\Rhino\Pagetypes\RhinoCapability;
use DNADesign\Rhino\Pagetypes\RhinoCategory;
use DNADesign\Rhino\Pagetypes\RhinoModule;
use DNADesign\Rhino\Pagetypes\RhinoPage;
use SilverStripe\Assets\Image;
use SilverStripe\Core\Convert;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Security\Group;
use SilverStripe\Security\Member;

/**
 * A high level wrapper around a Rhino account.
 *
 * The administrators can control all the content around the account including
 * the groups, membership and are the only ones that can modify the
 * capabilities / module content.
 *
 * @package rhino
 */
class RhinoAccount extends RhinoPage
{

	private static $has_one = array(
		'Logo' => Image::class,
		'UserGroup' => Group::class
	);

	private static $many_many = array(
		'Administrators' => Member::class
	);

	private static $allowed_children = [
		RhinoCategory::class,
		RhinoCapability::class,
		RhinoModule::class,
		RhinoAssessment::class
	];

	public function getCMSFields()
	{
		$fields = parent::getCMSFields();

		$gridFieldConfig = GridFieldConfig_RelationEditor::create();
		$grid = GridField::create(
			'Administrators',
			'Administrators',
			$this->Administrators(),
			$gridFieldConfig
		);

		$fields->addFieldToTab('Root.Administrators', $grid);
		return $fields;
	}

	/**
	 * Creates a new UserGroup for this Account
	 * Makes sure the UserGroup Code is unique
	 */
	public function onBeforeWrite()
	{
		parent::onBeforeWrite();

		if ($this->URLSegment) {
			$groupcode = $this->URLSegment;

			$group = Group::get()->filter('Code', $groupcode);

			if (!$group) {
				$group = new Group();
				$group->Title = $this->title;
				$group->Code = Convert::raw2url($this->URLSegment);
				$group->write();
			}

			$this->UserGroupID = $group->ID;
		}
	}

	/**
	 * Helper methods to find children
	 *
	 */
	public function getCategories()
	{
		$categories = RhinoCategory::get()->filter('ParentID', $this->ID);
		$this->extend('updateCategories', $categories);
		return $categories;
	}

	public function getCapabilities()
	{
		$categories = $this->getCategories();
		if ($categories && $categories->Count() > 0) {
			$capabilities =  RhinoCapability::get()->filter('ParentID', $categories->column('ID'));
			$this->extend('updateCapabilities', $capabilities);
			return $capabilities;
		}

		return null;
	}

	public function getModules()
	{
		$capabilities = $this->getCapabilities();
		if ($capabilities && $capabilities->Count() > 0) {
			$modules = RhinoModule::get()->filter('ParentID', $capabilities->column('ID'));
			$this->extend('updateModules', $modules);
			return $modules;
		}

		return null;
	}

	public function getAssessments()
	{
		$modules = $this->getModules();
		if ($modules && $modules->Count() > 0) {
			$assignments = RhinoAssessment::get()->filter('ParentID', $modules->column('ID'));
			$this->extend('updateAssessments', $assignments);
			return $assignments;
		}

		return null;
	}
}
