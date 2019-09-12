<?php

class User {

	public $userId;
	public $password;
	public $role;
	
	public function __construct($userId = "", $password = "", $role = 0) {
		$this->userId = $userId;
		$this->password = $password;
		$this->role = $role;
	}
}