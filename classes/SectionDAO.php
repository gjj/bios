<?php

class SectionDAO {
    public function retrieveAll() {
		$sql = "SELECT course, section, day, TIME_FORMAT(start, '%k%i') AS 'start', TIME_FORMAT(end, '%k%i') AS 'end', instructor, venue, size FROM sections";

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
		$query->setFetchMode(PDO::FETCH_ASSOC);

		$query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        
        $result = $this->updateDayOfWeek($result);
		
		// Returns my result set on success.
		return $result;
	}
    
    public function retrieveByCodeAndSection($courseCode, $section) {
		$sql = "SELECT course, section, day, TIME_FORMAT(start, '%k%i') AS 'start', TIME_FORMAT(end, '%k%i') AS 'end' FROM sections WHERE sections.course = :courseCode AND sections.section = :section";

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
        $query->bindParam(':section', $section, PDO::PARAM_STR);

		$query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        
        $result = $this->updateDayOfWeek($result);
		
		// Returns my result set on success.
		return $result;
    }

    public function retrieveByCode($courseCode) {
		$sql = "SELECT course, section, day, TIME_FORMAT(start, '%k%i') AS 'start', TIME_FORMAT(end, '%k%i') AS 'end', instructor, venue, size FROM sections WHERE sections.course = :courseCode";

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);

		$query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        
        $result = $this->updateDayOfWeek($result);
		
		// Returns my result set on success.
		return $result;
    }
    
    public function updateDayOfWeek($query) {
        $dayOfWeek = array(
            1 => "Monday",
            2 => "Tuesday",
            3 => "Wednesday",
            4 => "Thursday",
            5 => "Friday",
            6 => "Saturday",
            7 => "Sunday"
        );
        if (count($query)) {
            for ($i = 0; $i < count($query); $i++) {
                $query[$i]['day'] = $dayOfWeek[$query[$i]['day']];
            }
        }

        return $query;
    }
}