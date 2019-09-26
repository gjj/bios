***REMOVED***

class User {

	public $userId;
	public $password;
	public $name;
	public $school;
	public $edollar;
	public $role;
	
	public function __construct($userId = "", $password = "", $name ="", $school ="", $edollar= "",$role = 0) {
		$this->userId = $userId;
		$this->password = $password;
		$this->name = $name;
		$this->school = $school;
		$this->edollar = $edollar;
		$this->role = $role; 
	}
}