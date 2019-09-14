<?php

class Course {

    public $courseCode;
    public $school;
    public $title;
    public $description;
    public $examDate;
    public $examStart;
    public $examEnd;
    
	public function __construct($courseCode = "", $school = "", $title = "", $description = "", $examDate = "", $examStart = "", $examEnd = "") {
		$this->courseCode = $courseCode;
		$this->school = $school;
        $this->title = $title;
        $this->description = $description;
		$this->examDate = $examDate;
        $this->examStart = $examStart;
        $this->examEnd = $examEnd;
	}
}