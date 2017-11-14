<?php

/**
 * controls permission checks for the module
 * created as singleton by Injector
 * could have been implemented statically, but instances mean injector can override
 */

class RhinoPermissionCheck {

	public function can(Member $member = null, $action = null , DataObject $object = null) {
		if (!$member) {
			$member = Member::currentUser();
		}

		$class = get_class($object);
		if ($object instanceof RhinoAssessment) {
			$class = 'RhinoAssessment';
		}
		$method = 'can' . ucfirst($action) . $class;
		if (Permission::checkMember($member, 'ADMIN')) {
			return true;
		}
		return $this->$method($object, $member);
	}

	/**
	 * Rhino Account
	 *
	 * Everyone under the account can view
	 * Only CMS admin can edit, create or delete
	 */
	public function canViewRhinoAccount(RhinoAccount $object, Member $member) {
		if ($object->hasSomePerms($member)) {
			return true;
		}
		return false;
	}

	public function canCreateRhinoAccount(RhinoAccount $object, Member $member) {
		return false;
	}

	public function canEditRhinoAccount(RhinoAccount $object, Member $member) {
		if ($member->isOrganizationAdmin($object->ID)) {
			return true;
		}
		return false;
	}

	public function canDeleteRhinoAccount(RhinoAccount $object, Member $member) {
		return $this->canEditRhinoAccount($object, $member);
	}

	public function canAddChildrenToRhinoAccount(RhinoAccount $object, Member $member) {
		if ($member->isOrganizationAdmin($object->ID)) {
			return true;
		}
		return false;
	}

	/**
	 * Rhino Category
	 *
	 * Super can view, edit, create & delete if they manage the account it links to
	 * Everyone else can view if they're in a group/team that links to category's account
	 */
	public function canViewRhinoCategory(RhinoCategory $object, Member $member) {
		if ($object->getAccount()->hasSomePerms($member)) {
			return true;
		}
		return false;
	}

	public function canCreateRhinoCategory(RhinoCategory $object, Member $member) {
		if ($member->isOrganizationAdmin()) {
			return true;
		}
		return true;
	}

	public function canEditRhinoCategory(RhinoCategory $object, Member $member) {
		if ($member->isOrganizationAdmin($object->ParentID)) {
			return true;
		}
		return false;
	}

	public function canDeleteRhinoCategory(RhinoCategory $object, Member $member) {
		return $this->canEditRhinoCategory($object, $member);
	}

	/*
	 * Rhino Capability
	 *
	 * Super can view, edit, create & delete if they manage the account it links to
	 * Everyone else can view if they're in a group/team that links to category's account
	 */
	public function canViewRhinoCapability(RhinoCapability $object, Member $member) {
		if ($object->getAccount()->hasSomePerms($member)) {
			return true;
		}
		return false;
	}

	public function canCreateRhinoCapability(RhinoCapability $object, Member $member) {
		if ($member->isOrganizationAdmin()) {
			return true;
		}
		return false;
	}

	public function canEditRhinoCapability(RhinoCapability $object, Member $member) {
		if ($member->isOrganizationAdmin($object->getAccount()->ID)) {
			return true;
		}
		return false;
	}

	public function canDeleteRhinoCapability(RhinoCapability $object, Member $member) {
		return $this->canEditRhinoCapability($object, $member);
	}

	/*
	 * Rhino Module
	 *
	 * Super can view, edit, create & delete if they manage the account it links to
	 * Everyone else can view if they're in a group/team that links to category's account
	 */
	public function canViewRhinoModule(RhinoModule $object, Member $member) {
		if ($object->getAccount()->hasSomePerms($member)) {
			return true;
		}
		return false;
	}

	public function canCreateRhinoModule(RhinoModule$object, Member $member) {
		if ($member->isOrganizationAdmin()) {
			return true;
		}
		return false;
	}

	public function canEditRhinoModule(RhinoModule $object, Member $member) {
		if ($member->isOrganizationAdmin($object->getAccount()->ID)) {
			return true;
		}
		return false;
	}

	public function canDeleteRhinoModule(RhinoModule $object, Member $member) {
		return $this->canEditRhinoModule($object, $member);
	}

	/*
	 * Rhino Assignment
	 *
	 * Super can view, edit, create & delete if they manage the account it links to
	 * Everyone else can view if they're in a group/team that links to category's account
	 */

	public function canViewRhinoAssessment(RhinoAssessment $object, Member $member) {
		if ($object->getAccount()->hasSomePerms($member)) {
			return true;
		}
		return false;
	}

	public function canCreateRhinoAssessment(RhinoAssessment $object, Member $member) {
		if ($member->isOrganizationAdmin()) {
			return true;
		}
		return false;
	}

	public function canEditRhinoAssessment(RhinoAssessment $object, Member $member) {
		if ($member->isOrganizationAdmin($object->getAccount()->ID)) {
			return true;
		}
		return false;
	}

	public function canDeleteRhinoAssessment(RhinoAssessment $object, Member $member) {
		return $this->canEditRhinoAssessment($object, $member);
	}

	/*
	 * Rhino Group
	 *
	 * Super can view, edit, create & delete if they manage the account it links to
	 * Group leaders can view
	 * Everyone else can't do squat
	 */
	public function canViewRhinoGroup(RhinoGroup $object, Member $member) {
		if ($member->isOrganizationAdmin($object->AccountID)) {
			return true;
		} else if ($object->Leaders()->filter('ID', $member->ID)->exists()) {
			return true;
		}
		return false;
	}

	public function canCreateRhinoGroup(RhinoGroup $object, Member $member) {
		if ($member->isOrganizationAdmin()) {
			return true;
		}
		return false;
	}

	public function canEditRhinoGroup(RhinoGroup $object, Member $member) {
		if ($member->isOrganizationAdmin($object->AccountID)) {
			return true;
		}
		return false;
	}

	public function canDeleteRhinoGroup(RhinoGroup $object, Member $member) {
		if ($member->isOrganizationAdmin($object->AccountID)) {
			return true;
		}
		return false;
	}

	/*
	 * Rhino Team
	 *
	 * Super can view, edit, create & delete if they manage the account it links to
	 * Team leaders can view
	 * Group leaders from the parent group can view
	 * Everyone else can't do squat
	 */
	public function canViewRhinoTeam(RhinoTeam $object, Member $member) {
		if ($member->isOrganizationAdmin($object->AccountID)) {
			return true;
		} else if ($object->Group()->Leaders()->exists() && $object->Group()->Leaders()->filter('ID', $member->ID)->exists()) {
			return true;
		} else if ($object->Leaders()->filter('ID', $member->ID)->exists()) {
			return true;
		}
		return false;
	}

	public function canCreateRhinoTeam(RhinoTeam $object, Member $member) {
		if ($member->isOrganizationAdmin()) {
			return true;
		}
		return false;
	}

	public function canEditRhinoTeam(RhinoTeam $object, Member $member) {
		if ($member->isOrganizationAdmin($object->AccountID)) {
			return true;
		}
		return false;
	}

	public function canDeleteRhinoTeam(RhinoTeam $object, Member $member) {
		if ($member->isOrganizationAdmin($object->AccountID)) {
			return true;
		}
		return false;
	}

	/*
	 * Rhino Member
	 *
	 * Anyone can view as long as they have some connection
	 * Only Supers can edit if they're the members admin
	 * Only Supers get extra perms to create along with ADMINs
	 */
	public function canViewMember(Member $object, Member $member) {
		if(!$object->isInDB() && $member->isOrganizationAdmin()) {
			return true;
		}
		if ($object->RhinoUser == false || $member->RhinoUser == false) {
			return null;
		}

		$teamMemberships = $object->getTeams();
		$teamLeaderships = $object->getManagedTeams();
		$groupLeaderships = $object->ManagedGroups();
		$accountLeaderships = $object->ManagedAccounts();
		if ($object->ID == $member->ID) {
			return true;
		}
		if ($groupLeaderships->count() > 0) {
			foreach($groupLeaderships as $groupLeadership) {
				if ($member->isOrganizationAdmin($groupLeadership->AccountID)) {
					return true;
				}
			}
		}
		if ($teamLeaderships->count() > 0) {
			foreach($teamLeaderships as $teamLeadership) {
				if ($member->isOrganizationAdmin($teamLeadership->AccountID)) {
					return true;
				} else if ($teamLeadership->Group()->Leaders()->filter('ID', $member->ID)->exists()) {
					return true;
				}
			}
		}
		if ($teamMemberships->count() > 0) {
			foreach($teamMemberships as $teamMembership) {
				if ($member->isOrganizationAdmin($teamMembership->AccountID)) {
					return true;
				} else if ($teamMembership->Group()->Leaders()->filter('ID', $member->ID)->exists()) {
					return true;
				} else if ($teamMembership->Leaders()->filter('ID', $member->ID)->exists()) {
					return true;
				}
			}
		}
	}

	public function canCreateMember(Member $object, Member $member) {
		if ($member->isOrganizationAdmin()) {
			return true;
		}
	}

	public function canEditMember(Member $object, Member $member) {
		if(!$object->isInDB() && $member->isOrganizationAdmin()) {
			return true;
		}
		if ($object->ID == $member->ID) {
			return true;
		}
		$accounts = $object->getAccounts();
		foreach($accounts as $account) {
			if ($account->Administrators()->filter('ID', $member->ID)->exists()) {
				return true;
			}
		}
		return false;
	}

}