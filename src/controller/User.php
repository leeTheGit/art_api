<?php
namespace src\controller;

use src\router\Request;
use src\service\l;

class User extends Base_controller
{

	protected $resourceArray = ['domain', 'class', 'id'];
	protected $users = null;

	public function __construct(Request $request, \src\model\Users $users)
	{
		parent::__construct($request);

		$this->users = $users;
	}


	public function get($input)
	{

		$resource 	= $this->getResourceFromUrl();

		$urlParameters = [
			'group' => null,
		];

		$this->set_input_defaults($urlParameters, $input);

		if (empty($resource['id'])) {
			if ($this->auth_user->access != 'admin') { //if user is not admin, they can see only themselves

				$user = $this->users->getUser($this->auth_user->userid, $this->auth_user->groupid);

			} else {

				if ($this->auth_user->access != 'admin') { //if group is not admin can see only that groups memebrs

					$user = $this->users->getAllUsers($this->auth_user->groupid);

				} else {

					if (!empty($input['group'])) {
						$user = $this->users->getGroupUsers($input['group']);
					} else {
						$user = $this->users->getAllUsers();
					}
				}
			}
		} else {
			if ($resource['id'] != 'self') {

				if ($this->auth_user->access != 'admin') { //if user is not admin, they can see only themselves

					$user = $this->users->getUser($this->auth_user->userid, $this->auth_user->groupid);

				} else {

					if ($this->auth_user->groupaccess == 'full') { //show any user if the user is admin of an admin group

						$user = $this->users->getUser($resource['id'], 'all');

					} else { //show only members from users group if user is admin if a non admin group

						$user = $this->users->getUser($resource['id'], $this->auth_user->groupid);
					}
				}
			} else {

				$user = $this->users->getUser($this->auth_user->userid, $this->auth_user->groupid);
			}
		}
		return $user;

	}


	public function put($input)
	{

		$resource 	= $this->getResourceFromUrl();
		$columns = array('usergroup', 'name', 'firstname', 'lastname', 'access', 'password');

		if ($this->auth_user->access != 'admin') { //name +password for self only

			$update = $this->users->updateUser($this->auth_user->userid, $input, $columns);

			return $update;

		} elseif ($resource['id'] != '') {

			if ($this->auth_user->groupaccess != 'full') {

				$groupid = $this->auth_user->groupid;
			}
			//need to add check here to see if user is in your group as only full access admins can change anybody...
			//for now we will just rely on them not being able to know uids outside their group

			$columns = array('usergroup', 'name', 'firstname', 'lastname', 'access', 'password');

			$update = $this->users->updateUser($resource['id'], $input, $columns);

			return $update;
		}

		return "You're not allowed to do that.";

	}

	public function post($input)
	{
		l::og('in the user post controller');
		l::og($input);
		$resource 	= $this->getResourceFromUrl();

		$defaults = [
			'username' 	=> false,
			'password' 	=> false,
			'group'     => false,
			'access' 	=> false,
		];

		$this->set_input_defaults($defaults, $input);

		l::og($this->auth_user);
		

		if ($this->auth_user->access == 'admin' && !empty($input['groupid'])) {
			l::og('yeah admin user!');
			if ($input['group'] != 'self') { //full admins create users anywhere
				l::og('adding user now');
				return $this->users->createUser($input['groupid'], $input);
			}

			if ($input['group'] == 'self') { //non full admins can only create users in their group

				return $this->users->createUser($this->auth_user->groupid, $input);
			}

		}
		return false;
	}



	public function delete()
	{

		$resource = $this->getResourceFromUrl();

		if ($this->auth_user->access == 'admin' && !empty($resource['id'])) {

			if ($this->auth_user->groupaccess == 'full' || $resource['id'] == $this->auth_user->userid) {

				$delete = $this->users->deleteUser($resource['id']);

				return $delete;
			}

		}

		return false;

	}

}
