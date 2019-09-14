***REMOVED***

class Section {

    public $courseCode;
    public $section;
    public $day;
    public $start;
    public $end;
    public $instructor;
    public $venue;
    public $size;
    
	public function __construct($courseCode = "", $section = "", $day = "", $start = "", $end = "", $instructor = "", $venue = "", $size = 0) {
		$this->courseCode = $courseCode;
		$this->section = $section;
        $this->day = $day;
        $this->start = $start;
		$this->end = $end;
        $this->instructor = $instructor;
        $this->venue = $venue;
        $this->size = $size;
	}
}