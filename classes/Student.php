***REMOVED***

class Student extends User {

	public $name;
	public $school;
	public $edollar;
	
	public function __construct($userId = "", $password = "", $name = "", $school = "", $edollar = 0.0) {
		parent::__construct($userId, $password, 0);
		$this->userId = $userId;
		$this->password = $password;
		$this->edollar = $edollar;
	}
}