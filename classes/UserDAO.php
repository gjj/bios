***REMOVED***

class UserDAO
{
    public function login($userId, $password)
    {
        $sql = "SELECT user_id AS userid, password, role FROM users WHERE user_id = :userId AND password = :password";

        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);

        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        // Returns my result set i.e. userid, password, role (see SQL statement above) on success.
        return $result;
***REMOVED***

    public function retrieveById($userId)
    {
        $sql = "SELECT user_id AS userid, password, name, school, edollar AS edollar FROM users WHERE user_id = :userId";

        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);

        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        // Returns my result set i.e. userid, password, role (see SQL statement above) on success.
        return $result;
***REMOVED***

    public function retrieveAll()
    {
        $sql = "SELECT user_id AS userid, password, name, school, FORMAT(edollar, 1) AS edollar FROM users";

        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);

        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        // Returns my result set on success.
        return $result;
***REMOVED***

    public function retrieveStudentById($userId)
    {
        $sql = "SELECT user_id AS userid, password, name, school, FORMAT(edollar, 1) AS edollar FROM users WHERE user_id = :userId AND role = 0";

        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':userId', $userId, PDO::PARAM_STR);

        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        // Returns my result set i.e. userid, password, role (see SQL statement above) on success.
        return $result;
***REMOVED***

    public function retrieveAllStudents()
    {
        $sql = "SELECT user_id AS userid, password, name, school, FORMAT(edollar, 2) AS edollar FROM users WHERE role = 0 ";
        $sql .= "ORDER BY userid";

        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        $query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);

        $query->execute();
        
        $result = [];

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $row['edollar'] = (float)$row['edollar'];
            $result[] = $row;
    ***REMOVED***

        // Returns my result set on success.
        return $result;
***REMOVED***

    public function add($user)
    {
        $sql = "INSERT INTO `users` (user_id, password, name, school, edollar, role) VALUES (:user_id, :password, :name, :school, :edollar, 0)";

        //Establish DB Connection
        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        // prepare for execution of the stored procedure
        $query = $db->prepare($sql);

        $query->bindParam(':user_id', $user->userId, PDO::PARAM_STR);
        $query->bindParam(':password', $user->password, PDO::PARAM_STR);
        $query->bindParam(':name', $user->name, PDO::PARAM_STR);
        $query->bindParam(':school', $user->school, PDO::PARAM_STR);
        $query->bindParam(':edollar', $user->edollar, PDO::PARAM_STR);


        $isAddOK = False;
        if ($query->execute()) {
            $isAddOK = True;
    ***REMOVED***

        return $isAddOK;
***REMOVED***

    public function truncateAllTable()
    {
        // calling stored procedure command
        $sql = 'CALL truncate_tables();';

        //Establish DB Connection
        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();

        // prepare for execution of the stored procedure
        $query = $db->prepare($sql);
        $query->execute();
        $query->closeCursor();

***REMOVED***

}