<?php

class Student extends User {

	public $name;
	public $school;
	public $edollar;
	
	public function __construct($userid = "", $password = "", $name = "", $school = "", $edollar = 0.0) {
		parent::__construct($userid, $password, 0);
		$this->name = $name;
		$this->school = $school;
		$this->edollar = $edollar;
	}
}