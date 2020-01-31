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

    public function sectionExists($courseCode, $section) {
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

    public function removeAll() {
        $sql = 'TRUNCATE TABLE sections';
        
        $connMgr = new ConnectionManager();
        $db = $connMgr->getConnection();
        
        $query = $db->prepare($sql);
        
        $query->execute();
        $count = $query->rowCount();
    } 

    public function add($section) {
        $sql = 'INSERT INTO sections (course,section,day,start,end,instructor,venue,size) VALUES (:course, :section, :day, :start, :end, :instructor, :venue, :size)';
        
        $connMgr = new ConnectionManager();       
        $db = $connMgr->getConnection();
         
        $query = $db->prepare($sql); 

        $query->bindParam(':course', $section->courseCode, PDO::PARAM_STR);
        $query->bindParam(':section', $section->section, PDO::PARAM_STR);
        $query->bindParam(':day', $section->day, PDO::PARAM_STR);
        $query->bindParam(':start', $section->start, PDO::PARAM_STR);
        $query->bindParam(':end', $section->end, PDO::PARAM_STR);
        $query->bindParam(':instructor', $section->instructor, PDO::PARAM_STR);
        $query->bindParam(':venue', $section->venue, PDO::PARAM_STR);
        $query->bindParam(':size', $section->size, PDO::PARAM_STR);


        $isAddOK = False;
        if ($query->execute()) {
            $isAddOK = True;
        }

        return $isAddOK;
    }

    public function retrieveSizeByCodeAndSection($courseCode, $section) {
		$sql = "SELECT size FROM sections WHERE sections.course = :courseCode AND sections.section = :section";

		$connMgr = new ConnectionManager();
		$db = $connMgr->getConnection();

		$query = $db->prepare($sql);
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $query->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
        $query->bindParam(':section', $section, PDO::PARAM_STR);

		$query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        
		return $result;
    }



}